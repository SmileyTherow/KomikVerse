<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code_hash',
        'expires_at',
        'used',
        'attempts',
    ];

    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at ? $this->expires_at->isPast() : false;
    }

    // generate numeric code as string (6 digits)
    public static function generateCode(): string
    {
        return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    // create & store hashed OTP for a user and return the plain code for sending
    public static function createForUser(int $userId, int $minutes = 15): string
    {
        $code = self::generateCode();
        self::create([
            'user_id' => $userId,
            'code_hash' => Hash::make($code),
            'expires_at' => Carbon::now()->addMinutes($minutes),
            'used' => false,
            'attempts' => 0,
        ]);
        return $code;
    }

    // verify a plain code for the latest unused otp of a user
    public static function verifyCode(int $userId, string $code, int $maxAttempts = 5): array
    {
        $otp = self::where('user_id', $userId)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$otp) {
            return ['ok' => false, 'message' => 'OTP not found.'];
        }

        if ($otp->attempts >= $maxAttempts) {
            return ['ok' => false, 'message' => 'Maximum OTP attempts exceeded.'];
        }

        if ($otp->isExpired()) {
            return ['ok' => false, 'message' => 'OTP expired.'];
        }

        if (!Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');
            return ['ok' => false, 'message' => 'OTP invalid.'];
        }

        // mark used
        $otp->used = true;
        $otp->save();

        return ['ok' => true, 'otp' => $otp];
    }
}
