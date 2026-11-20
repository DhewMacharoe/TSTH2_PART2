@if ($errors->any())
<div class="mb-6 rounded-lg bg-[#fff2e8] px-4 py-3 text-xs text-[#9b4b20]">
    <p class="font-bold">Periksa kembali form:</p>
    <ul class="mt-1 list-disc pl-4">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<style>
.admin-input {
    width: 100%;
    margin-top: 0.5rem;
    border-radius: 0.5rem;
    border-width: 0;
    background-color: #f5faf5;
    padding: 0.625rem;
    font-size: 0.875rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --tw-ring-offset-shadow: 0 0 #0000;
    --tw-ring-shadow: 0 0 #0000;
    box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    border: 1px solid #dbe9dd;
}
.admin-input:focus {
    outline: none;
    border-color: #00634e;
    ring: 2px #00634e;
}
</style>

<div class="grid gap-5 md:grid-cols-2">
    <label class="block text-xs font-bold text-[#38584d]">Nama tanaman *<input name="name" value="{{ old('name', $plant->name ?? '') }}" required class="admin-input"></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Kategori *<select name="category_id" required class="admin-input"><option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $plant->category_id ?? '') == $category->id)>{{ $category->name }}</option>@endforeach</select></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Lokasi Katalog *
        <select name="location_type" required class="admin-input">
            <option value="greenhouse" @selected(old('location_type', $plant->location_type ?? '') == 'greenhouse')>Greenhouse</option>
            <option value="collection" @selected(old('location_type', $plant->location_type ?? '') == 'collection')>Tanaman Koleksi (Outdoor)</option>
        </select>
    </label>

    <label class="block text-xs font-bold text-[#38584d]">Jumlah populasi
        <input type="number" name="population" min="0" step="1" value="{{ old('population', $plant->population ?? '') }}" class="admin-input">
    </label>
    
    <label class="block text-xs font-bold text-[#38584d]">Nama latin *<input name="latin_name" value="{{ old('latin_name', $plant->latin_name ?? '') }}" required class="admin-input italic"></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Ordo *<input name="ordo" value="{{ old('ordo', $plant->ordo ?? '') }}" required class="admin-input"></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Famili *<input name="family" value="{{ old('family', $plant->family ?? '') }}" required class="admin-input"></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Genus *<input name="genus" value="{{ old('genus', $plant->genus ?? '') }}" required class="admin-input"></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Botanis *<input name="botanist" value="{{ old('botanist', $plant->botanist ?? '') }}" required class="admin-input"></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Gambar utama (ukuran bebas, tampil 1:1) @if(empty($plant?->image)) * @endif
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full rounded-lg bg-[#f5faf5] p-2 text-xs border border-[#dbe9dd]" @if(empty($plant?->image)) required @endif>
        @if(!empty($plant?->image))
            <span class="mt-1 block text-[10px] font-normal text-[#718178]">Gambar saat ini: {{ $plant->image }}</span>
        @endif
    </label>

    <div class="text-xs font-bold text-[#38584d]">
        <div class="flex items-center justify-between gap-3">
            <span>Foto tambahan (opsional; portrait atau lanskap)</span>
            <button type="button" id="add-plant-image" class="rounded-lg bg-[#e8f3ea] px-3 py-1.5 text-[10px] font-bold text-[#00634e] hover:bg-[#d9eddc]">+ Tambah foto</button>
        </div>
        <div id="plant-image-inputs" class="mt-2 space-y-2" data-max-images="12">
            <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" class="block w-full rounded-lg bg-[#f5faf5] p-2 text-xs border border-[#dbe9dd]">
        </div>
        <p class="mt-1 text-[10px] font-normal text-[#718178]">Gunakan tombol di atas untuk menambahkan foto ke-2, ke-3, dan seterusnya.</p>
        @if(!empty($plant?->image_gallery))
            <span class="mt-1 block text-[10px] font-normal text-[#718178]">Foto tambahan saat ini: {{ count($plant->image_gallery) }}. Upload baru akan menggantikannya.</span>
        @endif
    </div>
</div>

<div class="mt-5 space-y-5">
    <label class="block text-xs font-bold text-[#38584d]">Deskripsi singkat *<textarea name="description_short" required rows="3" class="admin-input">{{ old('description_short', $plant->description_short ?? '') }}</textarea></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Deskripsi lengkap *<textarea name="description_full" required rows="4" class="admin-input">{{ old('description_full', $plant->description_full ?? '') }}</textarea></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Manfaat *<textarea name="benefits" required rows="4" class="admin-input">{{ old('benefits', $plant->benefits ?? '') }}</textarea></label>
    
    <label class="block text-xs font-bold text-[#38584d]">
        Kawasan Asal *
    </label>
    <input type="text" name="origin" value="{{ old('origin', $plant->origin ?? '') }}" placeholder="Contoh: Asia Tenggara Tropis & Kepulauan Nusantara" class="admin-input" required>

    <label class="block text-xs font-bold text-[#38584d]">
        Sebaran Botani *
    </label>
    <textarea name="origin_distribution" rows="3" placeholder="Contoh: Tersebar luas di seluruh kepulauan Indonesia..." class="admin-input" required>{{ old('origin_distribution', $plant->origin_distribution ?? '') }}</textarea>
</div>

<label class="mt-5 flex items-center gap-2 text-xs font-bold text-[#38584d]">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plant->is_active ?? true)) class="h-4 w-4 accent-[#003f32]"> Tampilkan di katalog publik
</label>

<div class="mt-7 flex justify-end gap-3">
    <a href="{{ route('web.management.katalog') }}" class="rounded-lg px-4 py-2.5 text-xs font-bold text-[#557067] hover:bg-[#eef6ef]">Batal</a>
    <button class="rounded-lg bg-[#003f32] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#00634e]">{{ $submitLabel }}</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const addButton = document.getElementById('add-plant-image');
        const inputContainer = document.getElementById('plant-image-inputs');
        const form = inputContainer?.closest('form');
        const maxFileSize = 10 * 1024 * 1024;

        if (!addButton || !inputContainer || !form) return;

        form.addEventListener('submit', (event) => {
            const oversizedInput = [...form.querySelectorAll('input[type="file"]')]
                .find((input) => input.files[0]?.size > maxFileSize);

            if (!oversizedInput) return;

            event.preventDefault();
            oversizedInput.focus();
            alert('Ukuran setiap gambar maksimal 10 MB. Isian form tetap tersimpan di halaman ini.');
        });

        addButton.addEventListener('click', () => {
            const maxImages = Number(inputContainer.dataset.maxImages);
            const imageInputs = inputContainer.querySelectorAll('input[type="file"]');

            if (imageInputs.length >= maxImages) {
                addButton.disabled = true;
                addButton.textContent = 'Maksimal 12 foto';
                return;
            }

            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'images[]';
            input.accept = 'image/jpeg,image/png,image/webp';
            input.className = 'block w-full rounded-lg bg-[#f5faf5] p-2 text-xs border border-[#dbe9dd]';
            inputContainer.appendChild(input);

            if (imageInputs.length + 1 >= maxImages) {
                addButton.disabled = true;
                addButton.textContent = 'Maksimal 12 foto';
            }
        });
    });
</script>
