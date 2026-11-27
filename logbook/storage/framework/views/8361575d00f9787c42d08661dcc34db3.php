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
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">
                    Manajemen User
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    Kelola hak akses dan pembagian divisi kerja.
                </p>
            </div>

            <a
                href="<?php echo e(route('admin.users.create')); ?>"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition flex items-center gap-1.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </a>
        </div>
    </div>
 <?php $__env->endSlot(); ?>






<div class="py-10 px-4 lg:px-8">

    <div class="max-w-7xl mx-auto space-y-5">

        
        
        

        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2.5 shadow-sm">
                <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div><?php echo e(session('success')); ?></div>
            </div>
        <?php endif; ?>

        <?php if(session('warning')): ?>
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2.5 shadow-sm">
                <svg class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div><?php echo e(session('warning')); ?></div>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2.5 shadow-sm">
                <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div><?php echo e(session('error')); ?></div>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>


        
        
        

        <div class="bg-white shadow rounded-2xl p-6 overflow-x-auto">

            <table class="w-full text-sm">

                <thead>
                    <tr class="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="text-left py-3 px-3">No</th>
                        <th class="text-left py-3 px-3">Kode Karyawan</th>
                        <th class="text-left py-3 px-3">Nama</th>
                        <th class="text-left py-3 px-3">Email</th>
                        <th class="text-left py-3 px-3">Role</th>
                        <th class="text-left py-3 px-3">Departemen</th>
                        <th class="text-left py-3 px-3">Divisi</th>
                        <th class="text-center py-3 px-3">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr class="hover:bg-blue-50/40 transition">

                            
                            <td class="py-3.5 px-3 text-gray-500 font-mono text-xs">
                                <?php echo e($users->firstItem() + $loop->index); ?>

                            </td>

                            
                            <td class="py-3.5 px-3">
                                <?php if($user->employee_code): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded font-mono text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 tracking-wider">
                                        <?php echo e($user->employee_code); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">-</span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="py-3.5 px-3 font-semibold text-gray-900">
                                <?php echo e($user->name); ?>

                            </td>

                            
                            <td class="py-3.5 px-3 text-gray-600 font-mono text-xs">
                                <?php echo e($user->email); ?>

                            </td>

                            
                            <td class="py-3.5 px-3">
                                <?php switch($user->role):
                                    case ('pic_gudang'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                            PIC Gudang (GA)
                                        </span>
                                        <?php break; ?>

                                    <?php case ('pic_lab'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            PIC Lab (RdI)
                                        </span>
                                        <?php break; ?>

                                    <?php case ('kepala_departemen'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                            Kepala Departemen
                                        </span>
                                        <?php break; ?>

                                    <?php case ('kepala_divisi'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-800 border border-cyan-200">
                                            Kepala Divisi
                                        </span>
                                        <?php break; ?>

                                    <?php case ('employee'): ?>
                                    <?php case ('karyawan'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                            Karyawan
                                        </span>
                                        <?php break; ?>

                                    <?php case ('pimpinan'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                            Pimpinan
                                        </span>
                                        <?php break; ?>

                                    <?php case ('admin'): ?>
                                    <?php case ('web_admin'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                            Admin
                                        </span>
                                        <?php break; ?>

                                    <?php default: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            <?php echo e($user->role); ?>

                                        </span>
                                <?php endswitch; ?>
                            </td>

                            
                            <td class="py-3.5 px-3 text-gray-700">
                                <?php echo e($user->department?->name ?? '-'); ?>

                            </td>

                            
                            <td class="py-3.5 px-3 text-gray-700">
                                <?php echo e($user->divisionRelation?->name ?? '-'); ?>

                            </td>

                            
                            <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">

                                    
                                    <a
                                        href="<?php echo e(route('admin.users.edit', $user)); ?>"
                                        class="bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1.5 rounded-lg border border-blue-200 transition"
                                    >
                                        Edit
                                    </a>

                                    
                                    <?php if($user->id !== auth()->id()): ?>
                                        <form
                                            action="<?php echo e(route('admin.users.destroy', $user)); ?>"
                                            method="POST"
                                            class="inline delete-user-form"
                                        >
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button
                                                type="button"
                                                onclick="openDeleteModal(this)"
                                                class="bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1.5 rounded-lg border border-red-200 transition"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                </div>
                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Belum ada data pengguna ditemukan.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

            <?php if($users->hasPages()): ?>
                <div class="mt-6 border-t border-gray-100 pt-4">
                    <?php echo e($users->links()); ?>

                </div>
            <?php endif; ?>

        </div>

    </div>

</div>










<div
    id="deleteModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4"
>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
        <div class="flex justify-center mb-4">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 2.64h15.58a2 2 0 001.74-2.64l-7.82-14a2 2 0 00-3.42 0z" />
                </svg>
            </div>
        </div>

        <h2 class="text-lg font-bold text-gray-800 text-center">Hapus Pengguna?</h2>
        <p class="text-gray-500 text-center mt-1.5 text-xs leading-relaxed">
            Apakah Anda yakin ingin menghapus akun ini? Data logbook dan aktivitas terkait mungkin terpengaruh.
        </p>

        <div class="flex justify-center gap-2.5 mt-5">
            <button
                type="button"
                onclick="closeDeleteModal()"
                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-xs font-medium hover:bg-gray-100 transition"
            >
                Batal
            </button>
            <button
                type="button"
                onclick="confirmDeleteUser()"
                class="px-5 py-2 rounded-lg bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition"
            >
                Ya, Hapus
            </button>
        </div>
    </div>
</div>






<script>
    let deleteForm = null;

    // Delete Modal
    function openDeleteModal(button) {
        deleteForm = button.closest('.delete-user-form');
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        deleteForm = null;
    }

    function confirmDeleteUser() {
        if (deleteForm) {
            deleteForm.submit();
        }
    }

    document.addEventListener('click', function(event) {
        if (event.target === document.getElementById('deleteModal')) closeDeleteModal();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
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
<?php /**PATH D:\Semester 7\Magang\TSTH2_PART2\logbook\resources\views/admin/users/index.blade.php ENDPATH**/ ?>