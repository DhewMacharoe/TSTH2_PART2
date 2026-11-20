<div class="grid gap-5 md:grid-cols-2">
    <label class="block text-xs font-bold text-[#38584d]">Nama tanaman *<input name="name" value="{{ old('name', $plant->name ?? '') }}" required class="admin-input"></label>

    <label class="block text-xs font-bold text-[#38584d]">Kode aksesi
        <input name="accession_code" value="{{ old('accession_code', $plant->accession_code ?? '') }}" maxlength="255" class="admin-input">
        <span class="mt-1 block text-[10px] font-normal text-[#718178]">Informasi internal admin; tidak ditampilkan di katalog publik.</span>
    </label>
    
    <label class="block text-xs font-bold text-[#38584d]">Kategori *<select name="category_id" required class="admin-input"><option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $plant->category_id ?? '') == $category->id)>{{ $category->name }}</option>@endforeach</select></label>
    
    @php
        $selectedLocation = old('location_type', $plant->location_type ?? 'greenhouse');
        $selectedLocations = is_array($selectedLocation)
            ? $selectedLocation
            : ($selectedLocation === 'both'
                ? ['greenhouse', 'outdoor']
                : [$selectedLocation === 'collection' ? 'outdoor' : $selectedLocation]);
    @endphp

    <fieldset class="block text-xs font-bold text-[#38584d]">
        <legend>Lokasi Katalog *</legend>
        <div class="mt-2 flex gap-4">
            <label class="flex items-center gap-2 font-normal">
                <input type="checkbox" name="location_type[]" value="greenhouse" @checked(in_array('greenhouse', $selectedLocations, true)) class="location-checkbox h-4 w-4 accent-[#003f32]">
                Greenhouse
            </label>
            <label class="flex items-center gap-2 font-normal">
                <input type="checkbox" name="location_type[]" value="outdoor" @checked(in_array('outdoor', $selectedLocations, true)) class="location-checkbox h-4 w-4 accent-[#003f32]">
                Outdoor
            </label>
        </div>
    </fieldset>

    <div class="space-y-2">
        <p class="text-xs font-bold text-[#38584d]">Populasi awal</p>
        <div class="grid grid-cols-2 gap-2">
            <label id="greenhouse-population-field" class="text-[10px] font-bold text-[#718178]">Greenhouse
                <input id="greenhouse-population" type="number" name="greenhouse_population" min="0" step="1" value="{{ old('greenhouse_population', $plant->greenhouse_population ?? 0) }}" class="admin-input" @if($plant->exists) readonly @endif>
            </label>
            <label id="outdoor-population-field" class="text-[10px] font-bold text-[#718178]">Outdoor
                <input id="outdoor-population" type="number" name="outdoor_population" min="0" step="1" value="{{ old('outdoor_population', $plant->outdoor_population ?? 0) }}" class="admin-input" @if($plant->exists) readonly @endif>
            </label>
        </div>
        @if($plant->exists)<span class="block text-[10px] font-normal text-[#718178]">Gunakan tombol Riwayat untuk mencatat perubahan populasi.</span>@endif
    </div>
    
    <label class="block text-xs font-bold text-[#38584d]">Nama latin *<input name="latin_name" value="{{ old('latin_name', $plant->latin_name ?? '') }}" required class="admin-input italic"></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Ordo *<input name="ordo" value="{{ old('ordo', $plant->ordo ?? '') }}" required class="admin-input"></label>
    <label class="block text-xs font-bold text-[#38584d]">Kingdom *<input name="kingdom" value="{{ old('kingdom', $plant->kingdom ?? '') }}" required class="admin-input"></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Famili *<input name="family" value="{{ old('family', $plant->family ?? '') }}" required class="admin-input"></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Genus *<input name="genus" value="{{ old('genus', $plant->genus ?? '') }}" required class="admin-input"></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Botanist *<input name="botanist" value="{{ old('botanist', $plant->botanist ?? '') }}" required class="admin-input"></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Gambar utama (ukuran bebas, tampil 1:1) @if(empty($plant?->image)) * @endif
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full rounded-lg bg-[#f5faf5] p-2 text-xs ring-1 ring-[#dbe9dd]" @if(empty($plant?->image)) required @endif>
        @if(!empty($plant?->image))
            <div class="mt-3 flex items-center gap-3 rounded-lg border border-[#dbe9dd] bg-[#f9fcf9] p-2">
                <img src="{{ route('filebase.show', ['path' => 'plants/' . $plant->image]) }}" alt="Gambar {{ $plant->name }}" class="h-16 w-16 rounded-md object-cover">
                <div class="min-w-0 flex-1">
                    <span class="block truncate text-[10px] font-normal text-[#718178]">{{ $plant->image }}</span>
                    <label class="mt-2 flex items-center gap-2 text-[10px] font-bold text-[#b04d3e]">
                        <input type="checkbox" name="remove_image" value="1" @checked(old('remove_image')) class="h-4 w-4 accent-[#b04d3e]">
                        Hapus gambar utama
                    </label>
                </div>
            </div>
        @endif
    </label>

    <div class="text-xs font-bold text-[#38584d]">
        <div class="flex items-center justify-between gap-3">
            <span>Foto tambahan (opsional; portrait atau lanskap)</span>
            <button type="button" id="add-plant-image" class="rounded-lg bg-[#e8f3ea] px-3 py-1.5 text-[10px] font-bold text-[#00634e] hover:bg-[#d9eddc]">+ Tambah foto</button>
        </div>
        <div id="plant-image-inputs" class="mt-2 space-y-2" data-max-images="12">
            <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" class="block w-full rounded-lg bg-[#f5faf5] p-2 text-xs ring-1 ring-[#dbe9dd]">
        </div>
        <p class="mt-1 text-[10px] font-normal text-[#718178]">Gunakan tombol di atas untuk menambahkan foto ke-2, ke-3, dan seterusnya.</p>
             @if(!empty($plant?->image_gallery))
         <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
             @foreach($plant->image_gallery as $galleryImage)
                 <div class="flex items-center gap-3 rounded-lg border border-[#dbe9dd] bg-[#f9fcf9] p-2">
                     <img src="{{ route('filebase.show', ['path' => 'plants/' . $galleryImage]) }}" alt="Foto tambahan {{ $loop->iteration }}" class="h-16 w-16 rounded-md object-cover">
                     <div class="min-w-0 flex-1">
                         <span class="block truncate text-[10px] font-normal text-[#718178]">{{ $galleryImage }}</span>
                         <label class="mt-1 flex items-center gap-2 text-[10px] font-bold text-[#b04d3e] cursor-pointer">
                             <input type="checkbox" name="remove_gallery_images[]" value="{{ $galleryImage }}" @checked(in_array($galleryImage, old('remove_gallery_images', []), true)) class="h-4 w-4 accent-[#b04d3e]">
                             Hapus
                         </label>
                     </div>
                 </div>
             @endforeach
         </div>
         <span class="mt-1 block text-[10px] font-normal text-[#718178]">Centang foto yang ingin dihapus. Upload foto baru akan menggantikan seluruh foto tambahan.</span>
     @endif
    </div>
</div>

<div class="mt-5 space-y-5">
    <label class="block text-xs font-bold text-[#38584d]">Deskripsi singkat *<textarea name="description_short" required rows="3" class="admin-input">{{ old('description_short', $plant->description_short ?? '') }}</textarea></label>
    
    <label class="block text-xs font-bold text-[#38584d]">Deskripsi lengkap *<textarea name="description_full" required rows="4" class="admin-input">{{ old('description_full', $plant->description_full ?? '') }}</textarea></label>
    
    @php
        $benefitItems = old('benefit_items', $plant->benefit_items ?: [['title' => '', 'description' => '']]);
    @endphp
    <div class="md:col-span-2">
        <div class="flex items-center justify-between gap-3">
            <p class="text-xs font-bold text-[#38584d]">Manfaat *</p>
            <button type="button" id="add-benefit-item" class="rounded-lg bg-[#e8f3ea] px-3 py-1.5 text-[10px] font-bold text-[#00634e] hover:bg-[#d9eddc]">+ Tambah manfaat</button>
        </div>
        <div id="benefit-items" class="mt-2 space-y-3">
            @foreach($benefitItems as $index => $benefit)
                <div class="benefit-item rounded-xl border border-[#dbe9dd] bg-[#f9fcf9] p-3">
                    <div class="flex items-start gap-2">
                        <input name="benefit_items[{{ $index }}][title]" value="{{ $benefit['title'] ?? '' }}" required maxlength="255" placeholder="Judul manfaat" class="admin-input font-semibold">
                        <button type="button" class="remove-benefit-item rounded-lg px-2 py-2 text-sm font-bold text-[#b04d3e] hover:bg-[#fff1ee]" aria-label="Hapus manfaat">×</button>
                    </div>
                    <label class="mt-2 flex items-center gap-2 text-[10px] font-bold text-[#557067]">
                        <input type="checkbox" class="benefit-description-toggle h-4 w-4 accent-[#003f32]" @checked(filled($benefit['description'] ?? ''))>
                        Gunakan penjelasan
                    </label>
                    <textarea name="benefit_items[{{ $index }}][description]" rows="2" placeholder="Penjelasan manfaat (opsional)" class="benefit-description admin-input mt-2 {{ filled($benefit['description'] ?? '') ? '' : 'hidden' }}">{{ $benefit['description'] ?? '' }}</textarea>
                </div>
            @endforeach
        </div>
        <p class="mt-1 text-[10px] font-normal text-[#718178]">Setiap judul manfaat dapat memiliki penjelasan tambahan yang tampil sebagai accordion di katalog publik.</p>
    </div>
    
    <!-- Input Kawasan Asal -->
    <label class="block text-xs font-bold text-[#38584d]">
        Kawasan Asal *
    </label>
    <input type="text" name="origin" value="{{ old('origin', $plant->origin ?? '') }}" placeholder="Contoh: Asia Tenggara Tropis & Kepulauan Nusantara" class="h-10 w-full rounded-lg border-0 bg-white px-4 text-sm ring-1 ring-[#dbe9dd] focus:ring-2 focus:ring-[#00634e] shadow-sm" required>

<!-- Input Sebaran Botani -->
    <label class="block text-xs font-bold text-[#38584d]">
        Sebaran Botani *
    </label>
    <textarea name="origin_distribution" rows="3" placeholder="Contoh: Tersebar luas di seluruh kepulauan Indonesia..." class="w-full rounded-lg border-0 bg-white p-4 text-sm ring-1 ring-[#dbe9dd] focus:ring-2 focus:ring-[#00634e] shadow-sm" required>{{ old('origin_distribution', $plant->origin_distribution ?? '') }}</textarea>
</div>

<label class="mt-5 flex items-center gap-2 text-xs font-bold text-[#38584d]">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plant->is_active ?? true)) class="h-4 w-4 accent-[#003f32]"> Tampilkan di katalog publik
</label>

<div class="mt-7 flex justify-end gap-3">
    <a href="{{ route('admin.plants.index') }}" class="rounded-lg px-4 py-2.5 text-xs font-bold text-[#557067] hover:bg-[#eef6ef]">Batal</a>
    <button class="rounded-lg bg-[#003f32] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#00634e]">{{ $submitLabel }}</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const locationCheckboxes = [...document.querySelectorAll('.location-checkbox')];
        const benefitItemsContainer = document.getElementById('benefit-items');
        const addBenefitButton = document.getElementById('add-benefit-item');
        const populationFields = {
            greenhouse: {
                field: document.getElementById('greenhouse-population-field'),
                input: document.getElementById('greenhouse-population'),
            },
            outdoor: {
                field: document.getElementById('outdoor-population-field'),
                input: document.getElementById('outdoor-population'),
            },
        };

        const updatePopulationFields = () => {
            const selectedLocations = locationCheckboxes
                .filter((checkbox) => checkbox.checked)
                .map((checkbox) => checkbox.value);

            Object.entries(populationFields).forEach(([location, elements]) => {
                const isVisible = selectedLocations.includes(location);
                elements.field.classList.toggle('hidden', !isVisible);
                elements.field.setAttribute('aria-hidden', String(!isVisible));
                elements.input.disabled = !isVisible;
            });
        };

        const updateBenefitDescription = (toggle) => {
            const description = toggle.closest('.benefit-item')?.querySelector('.benefit-description');
            description?.classList.toggle('hidden', !toggle.checked);
            if (!toggle.checked && description) {
                description.value = '';
            }
        };

        const reindexBenefitItems = () => {
            benefitItemsContainer?.querySelectorAll('.benefit-item').forEach((item, index) => {
                item.querySelector('input[name*="[title]"]').name = `benefit_items[${index}][title]`;
                item.querySelector('textarea[name*="[description]"]').name = `benefit_items[${index}][description]`;
            });
        };

        addBenefitButton?.addEventListener('click', () => {
            const index = benefitItemsContainer.querySelectorAll('.benefit-item').length;
            const item = document.createElement('div');
            item.className = 'benefit-item rounded-xl border border-[#dbe9dd] bg-[#f9fcf9] p-3';
            item.innerHTML = `
                <div class="flex items-start gap-2">
                    <input name="benefit_items[${index}][title]" required maxlength="255" placeholder="Judul manfaat" class="admin-input font-semibold">
                    <button type="button" class="remove-benefit-item rounded-lg px-2 py-2 text-sm font-bold text-[#b04d3e] hover:bg-[#fff1ee]" aria-label="Hapus manfaat">×</button>
                </div>
                <label class="mt-2 flex items-center gap-2 text-[10px] font-bold text-[#557067]">
                    <input type="checkbox" class="benefit-description-toggle h-4 w-4 accent-[#003f32]">
                    Gunakan penjelasan
                </label>
                <textarea name="benefit_items[${index}][description]" rows="2" placeholder="Penjelasan manfaat (opsional)" class="benefit-description admin-input mt-2 hidden"></textarea>`;
            benefitItemsContainer.appendChild(item);
        });

        benefitItemsContainer?.addEventListener('change', (event) => {
            if (event.target.classList.contains('benefit-description-toggle')) {
                updateBenefitDescription(event.target);
            }
        });

        benefitItemsContainer?.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-benefit-item')) {
                const items = benefitItemsContainer.querySelectorAll('.benefit-item');
                if (items.length > 1) {
                    event.target.closest('.benefit-item').remove();
                    reindexBenefitItems();
                }
            }
        });

        locationCheckboxes.forEach((checkbox) => checkbox.addEventListener('change', updatePopulationFields));
        updatePopulationFields();

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
            Swal.fire({
                icon: 'warning',
                title: 'Ukuran gambar terlalu besar',
                text: 'Ukuran setiap gambar maksimal 10 MB. Isian form tetap tersimpan di halaman ini.',
                confirmButtonColor: '#003f32',
            });
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
            input.className = 'block w-full rounded-lg bg-[#f5faf5] p-2 text-xs ring-1 ring-[#dbe9dd]';
            inputContainer.appendChild(input);

            if (imageInputs.length + 1 >= maxImages) {
                addButton.disabled = true;
                addButton.textContent = 'Maksimal 12 foto';
            }
        });
    });
</script>