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
        <h2 class="font-semibold text-xl text-gray-800">
            Tambah Divisi
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Tambah Divisi Baru
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Tambahkan Divisi ke Departemen
                        <span class="font-medium text-gray-700">
                            <?php echo e($department->name); ?>

                        </span>.
                    </p>
                </div>

                <form
                    action="<?php echo e(route('admin.departments.divisions.store', $department)); ?>"
                    method="POST"
                >
                    <?php echo csrf_field(); ?>

                    
                    <div class="mb-5">
                        <label
                            for="department"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Departemen
                        </label>

                        <input
                            type="text"
                            id="department"
                            value="<?php echo e($department->name); ?>"
                            readonly
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed"
                        >
                    </div>

                    
                    <div class="mb-6">
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Nama Divisi
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?php echo e(old('name')); ?>"
                            required
                            autofocus
                            autocomplete="off"
                            placeholder="Contoh: Komunikasi"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                   focus:ring-2 focus:ring-blue-500
                                   focus:border-blue-500"
                        >

                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600">
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="flex items-center justify-end gap-3">

                        <a
                            href="<?php echo e(route('admin.departments.index')); ?>"
                            class="inline-flex items-center gap-2 px-4 py-2
                                bg-blue-600 hover:bg-blue-700
                                text-white rounded-lg font-medium transition"
                        >
                            Kembali ke Manajemen Departemen
                        </a>

                        <button
                            type="submit"
                            class="px-4 py-2
                                bg-blue-600 hover:bg-blue-700
                                text-white rounded-lg font-medium transition"
                        >
                            Simpan Divisi
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\Semester 7\Magang\TSTH2_PART2\logbook\resources\views/admin/divisions/create.blade.php ENDPATH**/ ?>