@extends('admin.layout')

@section('title', 'Tambah Komik Baru')

@section('content')
<div class="mb-4">
    <h2 class="text-2xl font-bold text-gray-800">Tambah Komik Baru</h2>
    <p class="text-gray-600">Isi form berikut untuk menambahkan komik ke koleksi</p>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <form id="multi-step-form" action="{{ route('admin.comics.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Form Header -->
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Tambah Komik Baru</h3>
                </div>
                <div>
                    <button type="button" onclick="resetForm()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-medium">Reset</button>
                    <button type="submit" id="final-submit" class="px-4 py-2 gradient-bg hover:opacity-90 text-white rounded-lg font-medium hidden">Tambah Komik</button>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Step indicators (visual) -->
            <div class="flex items-center justify-center mb-6">
                <div class="flex items-center">
                    <div id="circle-1" class="step-active w-8 h-8 rounded-full flex items-center justify-center font-bold">1</div>
                    <div class="ml-2 font-medium">Informasi Dasar</div>
                </div>
                <div class="h-1 w-16 bg-gray-300 mx-2"></div>
                <div class="flex items-center">
                    <div id="circle-2" class="step-inactive w-8 h-8 rounded-full flex items-center justify-center font-bold">2</div>
                    <div class="ml-2 text-gray-500">Detail & Sinopsis</div>
                </div>
                <div class="h-1 w-16 bg-gray-300 mx-2"></div>
                <div class="flex items-center">
                    <div id="circle-3" class="step-inactive w-8 h-8 rounded-full flex items-center justify-center font-bold">3</div>
                    <div class="ml-2 text-gray-500">Upload & Stok</div>
                </div>
            </div>

            <!-- STEP 1 -->
            <div id="step-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Judul Komik <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full px-4 py-3 border rounded-lg" placeholder="Contoh: One Piece Vol. 101" required>
                        @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Penulis</label>
                        <input type="text" name="author" value="{{ old('author') }}" class="w-full px-4 py-3 border rounded-lg" placeholder="Eiichiro Oda">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Penerbit</label>
                        <input type="text" name="publisher" value="{{ old('publisher') }}" class="w-full px-4 py-3 border rounded-lg" placeholder="Shonen Jump">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Tahun Terbit</label>
                        <input type="number" name="year" value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y') }}" class="w-full px-4 py-3 border rounded-lg">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="nextStep()" class="px-6 py-3 gradient-bg hover:opacity-90 text-white rounded-lg font-medium">
                        Lanjut ke Step 2 <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 2 -->
            <div id="step-2" class="hidden">
                <div class="mb-8">
                    <label class="block text-gray-700 mb-2 font-medium">Genre</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($genres as $g)
                            <label class="inline-flex items-center px-3 py-2 bg-gray-50 rounded-lg border">
                                <input type="checkbox" name="genres[]" value="{{ $g->id }}" class="mr-2" {{ in_array($g->id, old('genres', [])) ? 'checked' : '' }}>
                                <span>{{ $g->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 mb-2 font-medium">Sinopsis <span class="text-red-500">*</span></label>
                    <textarea id="synopsis" name="synopsis" rows="6" class="w-full px-4 py-3 border rounded-lg" required>{{ old('synopsis') }}</textarea>
                    <div class="flex justify-between text-sm text-gray-500 mt-2">
                        <span>Minimal 100 karakter</span>
                        <span id="char-count">0/100 karakter</span>
                    </div>
                    @error('synopsis') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-between">
                    <button type="button" onclick="prevStep()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </button>
                    <button type="button" onclick="nextStep()" class="px-6 py-3 gradient-bg hover:opacity-90 text-white rounded-lg font-medium">
                        Lanjut ke Step 3 <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 3 -->
            <div id="step-3" class="hidden">
                <div class="mb-8">
                    <label class="block text-gray-700 mb-2 font-medium">Cover Komik <span class="text-red-500">*</span></label>
                    <input type="file" id="cover-upload" name="cover" accept="image/*" class="w-full" required>
                    <div id="cover-preview" class="mt-4 hidden">
                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <img id="preview-img" class="w-16 h-20 object-cover rounded mr-4">
                                <div>
                                    <p id="image-name" class="font-medium"></p>
                                    <p id="image-size" class="text-sm text-gray-500"></p>
                                </div>
                            </div>
                            <button type="button" onclick="removeCover()" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Jumlah Stok <span class="text-red-500">*</span></label>
                        <input type="number" id="stock-input" name="stock" value="{{ old('stock', 1) }}" min="1" class="w-32 px-4 py-3 border rounded-lg" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Status</label>
                        <select name="status" class="w-full px-4 py-3 border rounded-lg">
                            <option value="available" selected>Tersedia</option>
                            <option value="coming_soon">Segera Hadir</option>
                            <option value="out_of_stock">Stok Habis</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <button type="button" onclick="prevStep()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </button>
                    <div class="flex space-x-3">
                        <button type="button" onclick="saveDraft()" class="px-6 py-3 bg-amber-100 hover:bg-amber-200 text-amber-800 rounded-lg font-medium">
                            <i class="fas fa-save mr-2"></i> Simpan Draft
                        </button>
                        <button type="button" onclick="submitForm()" class="px-6 py-3 gradient-bg hover:opacity-90 text-white rounded-lg font-medium">
                            <i class="fas fa-check mr-2"></i> Tambah Komik
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
        <div class="flex flex-wrap justify-between">
            <div class="text-center px-4">
                <div class="text-2xl font-bold text-blue-600">--</div>
                <div class="text-sm text-gray-600">Total Komik</div>
            </div>
            <div class="text-center px-4">
                <div class="text-2xl font-bold text-green-600">--</div>
                <div class="text-sm text-gray-600">Sedang Dipinjam</div>
            </div>
            <div class="text-center px-4">
                <div class="text-2xl font-bold text-red-600">--</div>
                <div class="text-sm text-gray-600">Terlambat</div>
            </div>
            <div class="text-center px-4">
                <div class="text-2xl font-bold text-purple-600">--</div>
                <div class="text-sm text-gray-600">Menunggu Approval</div>
            </div>
        </div>
    </div>
</div>

<!-- JS for steps + preview -->
<script>
    let currentStep = 1;

    function nextStep() {
        if (currentStep === 1) {
            // basic validation
            const title = document.getElementById('title').value.trim();
            if (!title) { alert('Judul wajib diisi'); return; }
        }
        if (currentStep === 2) {
            const syn = document.getElementById('synopsis').value.trim();
            if (syn.length < 100) { alert('Sinopsis minimal 100 karakter'); return; }
        }

        if (currentStep < 3) {
            document.getElementById('step-' + currentStep).classList.add('hidden');
            currentStep++;
            document.getElementById('step-' + currentStep).classList.remove('hidden');
            updateStepIndicator();
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            document.getElementById('step-' + currentStep).classList.add('hidden');
            currentStep--;
            document.getElementById('step-' + currentStep).classList.remove('hidden');
            updateStepIndicator();
        }
    }

    function updateStepIndicator() {
        for (let i=1;i<=3;i++) {
            const el = document.getElementById('circle-'+i);
            if (!el) continue;
            if (i <= currentStep) {
                el.className = 'step-active w-8 h-8 rounded-full flex items-center justify-center font-bold';
            } else {
                el.className = 'step-inactive w-8 h-8 rounded-full flex items-center justify-center font-bold';
            }
        }
    }

    // Char count for synopsis
    const textarea = document.getElementById('synopsis');
    const charCount = document.getElementById('char-count');
    if (textarea) {
        textarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = `${count}/100 karakter`;
            charCount.classList.toggle('text-green-500', count >= 100);
            charCount.classList.toggle('text-red-500', count < 100);
        });
    }

    // Cover preview
    const coverInput = document.getElementById('cover-upload');
    if (coverInput) {
        coverInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('preview-img').src = ev.target.result;
                document.getElementById('image-name').textContent = file.name;
                document.getElementById('image-size').textContent = formatFileSize(file.size);
                document.getElementById('cover-preview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        });
    }

    function removeCover() {
        coverInput.value = '';
        document.getElementById('cover-preview').classList.add('hidden');
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' Bytes';
        else if (bytes < 1048576) return (bytes/1024).toFixed(1) + ' KB';
        else return (bytes/1048576).toFixed(1) + ' MB';
    }

    function saveDraft() {
        alert('Draft disimpan (demo)');
    }

    function resetForm() {
        if (!confirm('Reset form?')) return;
        document.getElementById('multi-step-form').reset();
        removeCover();
        document.getElementById('step-3').classList.add('hidden');
        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-1').classList.remove('hidden');
        currentStep = 1;
        updateStepIndicator();
    }

    function submitForm() {
        // final validation
        if (!confirm('Tambah komik sekarang?')) return;
        // show the real submit button and click it (final submit)
        document.getElementById('final-submit').classList.remove('hidden');
        document.getElementById('final-submit').click();
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateStepIndicator();
        // init char count
        if (textarea) charCount.textContent = `${textarea.value.length}/100 karakter`;
    });
</script>
@endsection
