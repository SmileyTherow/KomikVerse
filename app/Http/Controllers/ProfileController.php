<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use App\Models\Genre;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show profile page
     */
    public function show()
    {
        /** @var User|null $user */
        $user = Auth::user();

        // cek kolom di tabel users (aman jika skema berbeda)
        $hasBirthdate = Schema::hasColumn('users', 'birthdate');
        $hasGender = Schema::hasColumn('users', 'gender');
        $hasBio = Schema::hasColumn('users', 'bio');
        $hasFavoriteGenres = Schema::hasColumn('users', 'favorite_genres');

        // load genres jika tabel ada
        try {
            $genres = Genre::orderBy('name')->get();
        } catch (\Throwable $e) {
            $genres = collect();
        }

        // parse favorite genres dari kolom JSON jika tersedia
        $userGenres = [];
        if ($hasFavoriteGenres && !empty($user->favorite_genres)) {
            $decoded = json_decode($user->favorite_genres, true);
            if (is_array($decoded)) {
                $userGenres = $decoded;
            }
        }

        // jika ada relasi many-to-many, gunakan relasi (lebih prioritas)
        if ($user instanceof User && method_exists($user, 'genres')) {
            try {
                $rel = $user->genres()->pluck('id')->toArray();
                if (is_array($rel)) {
                    $userGenres = $rel;
                }
            } catch (\Throwable $e) {
                // ignore if relation/pivot not available
            }
        }

        // load komik yang disukai user (liked comics) jika relasi tersedia
        $likedComics = collect();
        if ($user instanceof User && method_exists($user, 'likedComics')) {
            try {
                // eager load category + genres untuk tampilan
                $likedComics = $user->likedComics()->with(['category', 'genres'])->get();
            } catch (\Throwable $e) {
                $likedComics = collect();
            }
        }

        return view('profile', compact(
            'user',
            'genres',
            'hasBirthdate',
            'hasGender',
            'hasBio',
            'hasFavoriteGenres',
            'userGenres',
            'likedComics'
        ));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // build rules dynamically (safe if DB schema berbeda)
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];

        if (Schema::hasColumn('users', 'birthdate')) {
            $rules['birthdate'] = ['nullable', 'date'];
        }
        if (Schema::hasColumn('users', 'gender')) {
            $rules['gender'] = ['nullable', 'in:male,female,other'];
        }
        if (Schema::hasColumn('users', 'bio')) {
            $rules['bio'] = ['nullable', 'string', 'max:2000'];
        }

        // favorite_genres either stored as JSON column or relation
        if (Schema::hasColumn('users', 'favorite_genres')) {
            $rules['favorite_genres'] = ['nullable', 'array'];
            $rules['favorite_genres.*'] = ['integer', 'exists:genres,id'];
        } else {
            $rules['favorite_genres'] = ['nullable', 'array'];
            $rules['favorite_genres.*'] = ['integer'];
        }

        $data = $request->validate($rules);

        // assign safe fields
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? $user->phone;
        $user->address = $data['address'] ?? $user->address;

        if (isset($data['birthdate']) && Schema::hasColumn('users', 'birthdate')) {
            $user->birthdate = $data['birthdate'];
        }
        if (isset($data['gender']) && Schema::hasColumn('users', 'gender')) {
            $user->gender = $data['gender'];
        }
        if (isset($data['bio']) && Schema::hasColumn('users', 'bio')) {
            $user->bio = $data['bio'];
        }

        // handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public'); // storage/app/public/avatars
            // delete old avatar if exists
            if (!empty($user->avatar) && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $path;
        }

        // try save + sync favorite genres if relation exists
        try {
            // if favorite_genres column exists, save JSON
            if (Schema::hasColumn('users', 'favorite_genres')) {
                $user->favorite_genres = isset($data['favorite_genres']) ? json_encode($data['favorite_genres']) : null;
                $user->save();
            } else {
                // save and then attempt to sync relation if user->genres exists
                $user->save();
                if (method_exists($user, 'genres') && isset($data['favorite_genres'])) {
                    $user->genres()->sync($data['favorite_genres']);
                }
            }

            // ensure user refreshed
            $user->refresh();
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan profil: ' . $e->getMessage());
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
