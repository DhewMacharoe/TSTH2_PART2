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
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800">
                    Manajemen Departemen
                </h2>

                <a
                    href="<?php echo e(route('admin.departments.create')); ?>"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                >
                    + Tambah Departemen
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>


    <div class="py-12 px-4 lg:px-8">

        <div class="max-w-7xl mx-auto">

            
            <?php if(session('success')): ?>
                <div
                    class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg"
                >
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>


            
            <?php if(session('error')): ?>
                <div
                    class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg"
                >
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>


            <div class="bg-white shadow-sm rounded-lg overflow-hidden">

                
                <div class="px-6 py-4 border-b border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Daftar Departemen
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Kelola departemen yang digunakan dalam struktur organisasi.
                    </p>

                </div>


                
                <div class="overflow-x-auto">

                    <table class="w-full text-sm text-left">

                        <thead class="bg-gray-50 border-b border-gray-200">

                            <tr>

                                <th class="px-6 py-3 font-semibold text-gray-700">
                                    No
                                </th>

                                <th class="px-6 py-3 font-semibold text-gray-700">
                                    Departemen
                                </th>

                                <th class="px-6 py-3 font-semibold text-gray-700 text-center">
                                    Divisi
                                </th>

                                <th class="px-6 py-3 font-semibold text-gray-700 text-center">
                                    User
                                </th>

                                <th class="px-6 py-3 font-semibold text-gray-700 text-center">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200">

                            <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                <tr class="hover:bg-gray-50">

                                    
                                    <td class="px-6 py-4 text-gray-600">
                                        <?php echo e($departments->firstItem() + $loop->index); ?>

                                    </td>


                                    
                                    <td class="px-6 py-4">

                                        <div class="font-medium text-gray-900">
                                            <?php echo e($department->name); ?>

                                        </div>

                                    </td>


                                    
                                    <td class="px-6 py-4 text-center">

                                        <span
                                            class="inline-flex items-center justify-center min-w-8 px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold"
                                        >
                                            <?php echo e($department->divisions_count); ?>

                                        </span>

                                    </td>


                                    
                                    <td class="px-6 py-4 text-center">

                                        <span
                                            class="inline-flex items-center justify-center min-w-8 px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold"
                                        >
                                            <?php echo e($department->users_count); ?>

                                        </span>

                                    </td>

                    
                    <td class="px-6 py-4">

                        <div class="flex items-center justify-center gap-2">

                            
                            <a
                                href="<?php echo e(route('admin.departments.divisions.index', $department)); ?>"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg text-xs font-medium transition"
                            >
                                Kelola Divisi
                            </a>


                            
                            <a
                                href="<?php echo e(route('admin.departments.edit', $department)); ?>"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs font-medium transition"
                            >
                                Edit
                            </a>


                            
                            <?php if(
                                $department->divisions_count === 0 &&
                                $department->users_count === 0
                            ): ?>

                                <button
                                    type="button"
                                    onclick="openDeleteModal(
                                        <?php echo e($department->id); ?>,
                                        '<?php echo e(addslashes($department->name)); ?>'
                                    )"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-xs font-medium transition"
                                >
                                    Hapus
                                </button>

                            <?php else: ?>

                                <span
                                    class="bg-gray-200 text-gray-500 px-3 py-2 rounded-lg text-xs font-medium cursor-not-allowed"
                                    title="Departemen masih digunakan"
                                >
                                    Hapus
                                </span>

                            <?php endif; ?>

                        </div>

                    </td>
                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                <tr>

                                    <td
                                        colspan="5"
                                        class="px-6 py-10 text-center text-gray-500"
                                    >
                                        Belum ada Departemen yang tersedia.
                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>


                
                <?php if($departments->hasPages()): ?>

                    <div class="px-6 py-4 border-t border-gray-200">
                        <?php echo e($departments->links()); ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>


    
    <div
        id="deleteModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4"
    >

        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">

            <div class="flex items-start gap-4">

                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <svg
                        class="w-5 h-5 text-red-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86l-8.1 14a2 2 0 001.71 2.57h16.2a2 2 0 001.71-2.57l-8.1-14a2 2 0 00-3.42 0z"
                        />
                    </svg>
                </div>


                <div>

                    <h3 class="text-lg font-semibold text-gray-900">
                        Hapus Departemen?
                    </h3>

                    <p class="mt-2 text-sm text-gray-600">
                        Apakah Anda yakin ingin menghapus
                        <span
                            id="deleteDepartmentName"
                            class="font-semibold text-gray-900"
                        ></span>?
                    </p>

                    <p class="mt-2 text-xs text-gray-500">
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>

                </div>

            </div>


            <div class="flex justify-end gap-3 mt-6">

                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition"
                >
                    Batal
                </button>


                <form
                    id="deleteDepartmentForm"
                    method="POST"
                >

                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition"
                    >
                        Ya, Hapus
                    </button>

                </form>

            </div>

        </div>

    </div>


    
    <script>
        function openDeleteModal(id, name) {

            const modal =
                document.getElementById('deleteModal');

            const nameElement =
                document.getElementById('deleteDepartmentName');

            const form =
                document.getElementById('deleteDepartmentForm');


            nameElement.textContent = name;

            form.action =
                `/admin/departments/${id}`;


            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }


        function closeDeleteModal() {

            const modal =
                document.getElementById('deleteModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }


        /*
         * Tutup modal ketika klik area luar modal.
         */
        document
            .getElementById('deleteModal')
            .addEventListener('click', function (event) {

                if (event.target === this) {
                    closeDeleteModal();
                }

            });


        /*
         * Tutup modal dengan tombol Escape.
         */
        document.addEventListener('keydown', function (event) {

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
<?php endif; ?><?php /**PATH D:\Semester 7\Magang\TSTH2_PART2\logbook\resources\views/admin/departments/index.blade.php ENDPATH**/ ?>