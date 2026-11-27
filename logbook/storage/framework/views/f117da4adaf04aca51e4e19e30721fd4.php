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
            Dashboard Admin
        </h2>

     <?php $__env->endSlot(); ?>


    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">


                

                <div class="bg-white shadow rounded-lg p-6">

                    <p class="text-gray-500">
                        Total User
                    </p>

                    <h3 class="text-3xl font-bold mt-2">

                        <?php echo e($totalUsers); ?>


                    </h3>

                </div>


                

                <div class="bg-white shadow rounded-lg p-6">

                    <p class="text-gray-500">
                        Total Karyawan
                    </p>

                    <h3 class="text-3xl font-bold mt-2">

                        <?php echo e($totalEmployees); ?>


                    </h3>

                </div>

                 
        <div class="bg-white shadow rounded-lg p-6">

            <p class="text-gray-500">
                Kepala Departemen
            </p>

            <h3 class="text-3xl font-bold mt-2">
                <?php echo e($totalDepartmentHeads); ?>

            </h3>

        </div>



            
            <div class="bg-white shadow rounded-lg p-6">

                <p class="text-gray-500">
                    Total Departemen
                </p>

                <h3 class="text-3xl font-bold mt-2">
                    <?php echo e($totalDepartments); ?>

                </h3>

            </div>

            
            <div class="bg-white shadow rounded-lg p-6">

                <p class="text-gray-500">
                    Total Divisi
                </p>

                <h3 class="text-3xl font-bold mt-2">
                    <?php echo e($totalDivisions); ?>

                </h3>

            </div>

            </div>


            

            <div class="bg-white shadow rounded-lg p-6 mt-6">

            <h3 class="text-lg font-semibold mb-4">
                Menu Manajemen
            </h3>

<div class="flex flex-wrap gap-3">

    <a
        href="<?php echo e(route('admin.users.index')); ?>"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
    >
        Kelola Pengguna
    </a>

</div>            </div>


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
<?php endif; ?><?php /**PATH D:\Semester 7\Magang\TSTH2_PART2\logbook\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>