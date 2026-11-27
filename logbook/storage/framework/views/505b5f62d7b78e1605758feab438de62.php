<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>





 <?php $__env->slot('header', null, []); ?> 
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800">
            Tambah User Baru
        </h2>
        <a
            href="<?php echo e(route('admin.users.index')); ?>"
            class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1 transition"
        >
            &larr; Kembali ke Daftar User
        </a>
    </div>
 <?php $__env->endSlot(); ?>






<div class="py-12">

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded-lg p-6">

            <form
                action="<?php echo e(route('admin.users.store')); ?>"
                method="POST"
                autocomplete="off"
            >
                <?php echo csrf_field(); ?>

                
                
                

                <div class="mb-4">
                    <label for="name" class="block mb-2 font-medium text-gray-700">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="<?php echo e(old('name')); ?>"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Masukkan nama user"
                        required
                    >

                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>


                
                
                

                <div class="mb-4">
                    <label for="email" class="block mb-2 font-medium text-gray-700">
                        Email <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="<?php echo e(old('email')); ?>"
                        autocomplete="off"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Masukkan email user"
                        required
                    >

                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>


                
                
                

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="password" class="block mb-2 font-medium text-gray-700">
                            Password <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            autocomplete="new-password"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Minimal 8 karakter"
                            required
                        >

                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block mb-2 font-medium text-gray-700">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Ulangi password"
                            required
                        >
                    </div>
                </div>


                
                
                

                <div class="mb-4">
                    <label for="status" class="block mb-2 font-medium text-gray-700">
                        Status Akun
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="approved" <?php if(old('status', 'approved') === 'approved'): echo 'selected'; endif; ?>>
                            Disetujui (Approved) - Pengguna langsung aktif
                        </option>
                        <option value="pending" <?php if(old('status') === 'pending'): echo 'selected'; endif; ?>>
                            Menunggu Persetujuan (Pending)
                        </option>
                    </select>

                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>


                
                
                

                <div class="mb-5 p-4 rounded-xl bg-gray-50 border border-gray-200">
                    <label for="role-select" class="block mb-1.5 font-bold text-gray-800">
                        Pilih Role Pengguna <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-3">
                        Pilih jenis role: PIC (Gudang/Lab), Kepala Departemen, Kepala Divisi, atau Karyawan Lapangan.
                    </p>

                    <select
                        name="role"
                        id="role-select"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 font-medium"
                        required
                    >
                        <option value="">-- Pilih Role --</option>

                        <optgroup label="1. PIC Gudang & Lab (Warehouse)">
                            <option value="pic_gudang" <?php if(old('role') === 'pic_gudang'): echo 'selected'; endif; ?>>
                                PIC Gudang (General Affair)
                            </option>
                            <option value="pic_lab" <?php if(old('role') === 'pic_lab'): echo 'selected'; endif; ?>>
                                PIC Lab (Riset dan Inovasi)
                            </option>
                        </optgroup>

                        <optgroup label="2. Struktural Organisasi">
                            <option value="kepala_departemen" <?php if(old('role') === 'kepala_departemen'): echo 'selected'; endif; ?>>
                                Kepala Departemen
                            </option>
                            <option value="kepala_divisi" <?php if(old('role') === 'kepala_divisi'): echo 'selected'; endif; ?>>
                                Kepala Divisi
                            </option>
                        </optgroup>

                        <optgroup label="3. Operasional">
                            <option value="employee" <?php if(old('role') === 'employee'): echo 'selected'; endif; ?>>
                                Karyawan / Employee
                            </option>
                        </optgroup>

                        <optgroup label="4. Sistem & Eksekutif">
                            <option value="pimpinan" <?php if(old('role') === 'pimpinan'): echo 'selected'; endif; ?>>
                                Pimpinan / Direktur
                            </option>
                            <option value="admin" <?php if(old('role') === 'admin'): echo 'selected'; endif; ?>>
                                Admin Sistem Logbook
                            </option>
                        </optgroup>
                    </select>

                    
                    <div id="role-hint" class="mt-2.5 hidden">
                        <div id="hint-pic-gudang" class="p-2.5 rounded-lg bg-amber-50 border border-amber-200 text-xs text-amber-900 hidden flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                            <span><strong>PIC Gudang:</strong> Mengelola inventaris & barang operasional General Affair pada Portal Warehouse (Port 8002).</span>
                        </div>
                        <div id="hint-pic-lab" class="p-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 hidden flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                            <span><strong>PIC Lab:</strong> Mengelola sampel & inventaris lab Riset dan Inovasi pada Portal Warehouse (Port 8002).</span>
                        </div>
                        <div id="hint-kadep" class="p-2.5 rounded-lg bg-blue-50 border border-blue-200 text-xs text-blue-900 hidden flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                            <span><strong>Kepala Departemen:</strong> Membawahi seluruh divisi dalam satu departemen, memantau proyek & me-review logbook.</span>
                        </div>
                        <div id="hint-kadiv" class="p-2.5 rounded-lg bg-cyan-50 border border-cyan-200 text-xs text-cyan-900 hidden flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-cyan-500 shrink-0"></span>
                            <span><strong>Kepala Divisi:</strong> Membuat target & task serta me-review logbook karyawan di divisinya.</span>
                        </div>
                        <div id="hint-employee" class="p-2.5 rounded-lg bg-green-50 border border-green-200 text-xs text-green-900 hidden flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                            <span><strong>Karyawan:</strong> Menerima tugas dari Kepala Divisi dan mencatat aktivitas harian pada Logbook.</span>
                        </div>
                    </div>

                    <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>


                
                
                

                <div class="mb-4" id="department-container">
                    <label for="department-select" class="block mb-2 font-medium text-gray-700">
                        Department
                        <span id="department-required" class="text-red-500 hidden">*</span>
                    </label>

                    <select
                        name="department_id"
                        id="department-select"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Pilih Department</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option
                                value="<?php echo e($department->id); ?>"
                                <?php if((string) old('department_id') === (string) $department->id): echo 'selected'; endif; ?>
                            >
                                <?php echo e($department->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>


                
                
                

                <div class="mb-6" id="division-container">
                    <label for="division-select" class="block mb-2 font-medium text-gray-700">
                        Division
                        <span id="division-required" class="text-red-500 hidden">*</span>
                    </label>

                    <select
                        name="division_id"
                        id="division-select"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Pilih Division</option>
                        <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option
                                value="<?php echo e($division->id); ?>"
                                data-department="<?php echo e($division->department_id); ?>"
                                <?php if((string) old('division_id') === (string) $division->id): echo 'selected'; endif; ?>
                            >
                                <?php echo e($division->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <p id="division-not-needed" class="text-xs text-gray-500 mt-1 hidden">
                        Role ini tidak membutuhkan pemilihan divisi individual.
                    </p>

                    <?php $__errorArgs = ['division_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>


                
                
                

                <div class="mb-6" id="join-year-container">
                    <label for="join_year" class="block mb-2 font-medium text-gray-700">
                        Tahun Masuk
                        <span class="text-red-500" id="join-year-required">*</span>
                        <span class="text-gray-400 text-sm font-normal">
                            (untuk pembentukan kode karyawan otomatis)
                        </span>
                    </label>

                    <input
                        type="number"
                        name="join_year"
                        id="join_year"
                        value="<?php echo e(old('join_year', date('Y'))); ?>"
                        min="2000"
                        max="<?php echo e(date('Y') + 1); ?>"
                        class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        placeholder="<?php echo e(date('Y')); ?>"
                    >

                    <p class="text-gray-500 text-xs mt-1">
                        Format kode: <code class="bg-gray-100 px-1 rounded font-semibold text-blue-700">DDDKDYYNNN</code> (Kepala Dept) atau <code class="bg-gray-100 px-1 rounded font-semibold text-blue-700">DDDBBYYNNN</code> (Kepala Divisi & Employee).
                    </p>

                    <?php $__errorArgs = ['join_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>


                
                
                

                <div
                    class="mb-6 p-4 rounded-lg bg-blue-50 border border-blue-200 hidden"
                    id="code-preview-container"
                >
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-semibold text-blue-900">
                            Preview Format Kode Unik Otomatis
                        </span>
                    </div>

                    <div class="mt-2 flex items-baseline gap-3 flex-wrap">
                        <span
                            id="code-preview-badge"
                            class="font-mono text-base font-bold bg-white px-3 py-1 rounded border border-blue-300 text-blue-700 tracking-wider shadow-sm"
                        >
                            -
                        </span>
                        <span class="text-xs text-blue-700" id="code-preview-desc">
                            Nomor urut (NNN) dimulai dari 001 akan dibuat otomatis oleh backend.
                        </span>
                    </div>
                </div>


                
                
                

                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <a
                        href="<?php echo e(route('admin.users.index')); ?>"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition"
                    >
                        Simpan User
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>






<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role-select');
    const departmentContainer = document.getElementById('department-container');
    const departmentSelect = document.getElementById('department-select');
    const departmentRequired = document.getElementById('department-required');

    const divisionContainer = document.getElementById('division-container');
    const divisionSelect = document.getElementById('division-select');
    const divisionRequired = document.getElementById('division-required');
    const divisionNotNeeded = document.getElementById('division-not-needed');

    const joinYearContainer = document.getElementById('join-year-container');
    const joinYearInput = document.getElementById('join_year');
    const joinYearRequired = document.getElementById('join-year-required');

    const codePreviewContainer = document.getElementById('code-preview-container');
    const codePreviewBadge = document.getElementById('code-preview-badge');
    const codePreviewDesc = document.getElementById('code-preview-desc');

    const roleHint = document.getElementById('role-hint');
    const hints = {
        'pic_gudang': document.getElementById('hint-pic-gudang'),
        'pic_lab': document.getElementById('hint-pic-lab'),
        'kepala_departemen': document.getElementById('hint-kadep'),
        'kepala_divisi': document.getElementById('hint-kadiv'),
        'employee': document.getElementById('hint-employee'),
    };

    const divisionOptions = Array.from(divisionSelect.querySelectorAll('option'));

    function getDepartmentCode(name) {
        if (!name) return '';
        const n = name.toLowerCase();
        if (n.includes('general')) return '01G';
        if (n.includes('riset') || n.includes('hilirisasi riset')) return '02R';
        return '';
    }

    function getDivisionCode(name) {
        if (!name) return '';
        const n = name.toLowerCase();
        if (n.includes('administrasi')) return 'AD';
        if (n.includes('operasional')) return 'OP';
        if (n.includes('genomic')) return 'GE';
        if (n.includes('hilirisasi')) return 'HI';
        if (n.includes('gh') || n.includes('sh')) return 'GS';
        return '';
    }

    function updateCodePreview() {
        const role = roleSelect.value;
        const joinYear = (joinYearInput.value || '').trim();
        const yy = joinYear.length >= 2 ? joinYear.slice(-2) : 'YY';

        const deptOption = departmentSelect.options[departmentSelect.selectedIndex];
        const deptName = deptOption && deptOption.value ? deptOption.text : '';
        const ddd = getDepartmentCode(deptName) || 'DDD';

        if (role === 'kepala_departemen') {
            if (ddd !== 'DDD' && joinYear.length >= 4) {
                codePreviewBadge.textContent = `${ddd}KD${yy}NNN`;
                codePreviewDesc.textContent = `Kode unik Kepala Departemen ${deptName} (nomor urut NNN dibuat otomatis).`;
                codePreviewContainer.classList.remove('hidden');
            } else {
                codePreviewContainer.classList.add('hidden');
            }
            return;
        }

        if (role === 'kepala_divisi' || role === 'employee') {
            const divOption = divisionSelect.options[divisionSelect.selectedIndex];
            const divName = divOption && divOption.value ? divOption.text : '';
            const bb = getDivisionCode(divName) || 'BB';

            if (ddd !== 'DDD' && bb !== 'BB' && joinYear.length >= 4) {
                const label = role === 'kepala_divisi' ? 'Kepala Divisi' : 'Karyawan';
                codePreviewBadge.textContent = `${ddd}${bb}${yy}NNN`;
                codePreviewDesc.textContent = `Kode unik ${label} ${deptName} - ${divName} (nomor urut NNN dibuat otomatis).`;
                codePreviewContainer.classList.remove('hidden');
            } else {
                codePreviewContainer.classList.add('hidden');
            }
            return;
        }

        codePreviewContainer.classList.add('hidden');
    }

    function filterDivisions() {
        const selectedDepartment = departmentSelect.value;
        const currentDivision = divisionSelect.value;
        let matched = false;

        divisionSelect.innerHTML = '';
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = selectedDepartment ? 'Pilih Division' : 'Pilih Department Dahulu';
        divisionSelect.appendChild(defaultOption);

        divisionOptions.forEach(option => {
            if (!option.value) return;
            if (option.dataset.department === selectedDepartment) {
                const clone = option.cloneNode(true);
                if (clone.value === currentDivision) {
                    clone.selected = true;
                    matched = true;
                }
                divisionSelect.appendChild(clone);
            }
        });

        if (!matched && currentDivision !== '') {
            divisionSelect.value = '';
        }

        updateCodePreview();
    }

    function handleRoleChange() {
        const role = roleSelect.value;

        // Tampilkan hint
        roleHint.classList.remove('hidden');
        Object.keys(hints).forEach(key => {
            if (hints[key]) hints[key].classList.add('hidden');
        });
        if (hints[role]) {
            hints[role].classList.remove('hidden');
        } else {
            roleHint.classList.add('hidden');
        }

        // PIC GUDANG & PIC LAB
        if (role === 'pic_gudang' || role === 'pic_lab') {
            departmentContainer.classList.remove('hidden');
            departmentRequired.classList.remove('hidden');
            departmentSelect.required = false;

            // Auto-select department
            if (role === 'pic_gudang') {
                Array.from(departmentSelect.options).forEach(opt => {
                    if (opt.text.toLowerCase().includes('general')) opt.selected = true;
                });
            } else if (role === 'pic_lab') {
                Array.from(departmentSelect.options).forEach(opt => {
                    if (opt.text.toLowerCase().includes('riset')) opt.selected = true;
                });
            }

            divisionContainer.classList.add('hidden');
            divisionSelect.value = '';
            divisionSelect.required = false;

            joinYearContainer.classList.add('hidden');
            joinYearInput.required = false;
            codePreviewContainer.classList.add('hidden');
            return;
        }

        // KEPALA DEPARTEMEN
        if (role === 'kepala_departemen') {
            departmentContainer.classList.remove('hidden');
            departmentRequired.classList.remove('hidden');
            departmentSelect.required = true;

            divisionContainer.classList.add('hidden');
            divisionSelect.value = '';
            divisionSelect.required = false;

            joinYearContainer.classList.remove('hidden');
            joinYearRequired.classList.remove('hidden');
            joinYearInput.required = true;

            filterDivisions();
            updateCodePreview();
            return;
        }

        // KEPALA DIVISI & EMPLOYEE
        if (role === 'kepala_divisi' || role === 'employee') {
            departmentContainer.classList.remove('hidden');
            departmentRequired.classList.remove('hidden');
            departmentSelect.required = true;

            divisionContainer.classList.remove('hidden');
            divisionRequired.classList.remove('hidden');
            divisionSelect.required = true;
            divisionNotNeeded.classList.add('hidden');

            joinYearContainer.classList.remove('hidden');
            joinYearRequired.classList.remove('hidden');
            joinYearInput.required = true;

            filterDivisions();
            updateCodePreview();
            return;
        }

        // ADMIN & PIMPINAN
        departmentContainer.classList.add('hidden');
        departmentRequired.classList.add('hidden');
        departmentSelect.value = '';
        departmentSelect.required = false;

        divisionContainer.classList.add('hidden');
        divisionRequired.classList.add('hidden');
        divisionSelect.value = '';
        divisionSelect.required = false;

        joinYearContainer.classList.add('hidden');
        joinYearInput.required = false;
        codePreviewContainer.classList.add('hidden');
    }

    roleSelect.addEventListener('change', handleRoleChange);
    departmentSelect.addEventListener('change', function () {
        filterDivisions();
        updateCodePreview();
    });
    divisionSelect.addEventListener('change', updateCodePreview);
    joinYearInput.addEventListener('input', updateCodePreview);

    // Inisialisasi awal
    handleRoleChange();
});
</script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH D:\Semester 7\Magang\TSTH2_PART2\logbook\resources\views/admin/users/create.blade.php ENDPATH**/ ?>