<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\LeaderDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LeaderLogbookController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SupervisorLogbookController;
use App\Http\Controllers\SubTargetController;
use App\Http\Controllers\KepalaDivisiLogbookController;
use App\Http\Controllers\KepalaDivisiMyLogbookController;
use App\Http\Controllers\KepalaDepartemenLogbookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TargetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ROOT & SSO HUB AUTH BRIDGE
|--------------------------------------------------------------------------
*/

Route::get('/hub-auth', function (\Illuminate\Http\Request $request) {
    $email = $request->query('email');
    $ts = $request->query('ts');
    $target = $request->query('target', '/dashboard');
    $sig = $request->query('sig');

    $secret = 'tsth2_shared_secret_hub_auth_2024';
    $expected = hash_hmac('sha256', "{$email}|{$ts}|{$target}", $secret);

    if (abs(time() - (int)$ts) > 300 || !hash_equals($expected, (string)$sig)) {
        return redirect()->route('login')->with('error', 'Sesi autentikasi SSO kadaluarsa atau tidak valid.');
    }

    $user = \App\Models\User::where('email', $email)->first();
    if (!$user) {
        if (str_contains($email, 'kadiv')) {
            $user = \App\Models\User::where('role', 'kepala_divisi')->first();
        } elseif (str_contains($email, 'kadep')) {
            $user = \App\Models\User::where('role', 'kepala_departemen')->first();
        } elseif (str_contains($email, 'admin')) {
            $user = \App\Models\User::where('role', 'admin')->first();
        } elseif (str_contains($email, 'pimpinan')) {
            $user = \App\Models\User::where('role', 'pimpinan')->first();
        }
    }

    if (!$user) {
        return redirect()->route('login')->with('error', 'Akun tidak ditemukan di Logbook.');
    }

    \Illuminate\Support\Facades\Auth::login($user, true);
    $request->session()->regenerate();

    return redirect($target);
})->name('hub.auth');

Route::get('/sso-jump', function (\Illuminate\Http\Request $request) {
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }
    $portal = $request->query('portal', 'pengunjung');
    $target = $request->query('target', '/');
    $secret = 'tsth2_shared_secret_hub_auth_2024';
    $ts = time();
    $sig = hash_hmac('sha256', "{$user->email}|{$ts}|{$target}", $secret);
    $query = http_build_query([
        'email'  => $user->email,
        'ts'     => $ts,
        'target' => $target,
        'sig'    => $sig,
    ]);

    $baseUrl = match($portal) {
        'logbook'    => \App\Services\TsthUrl::logbook(),
        'warehouse'  => \App\Services\TsthUrl::gudang(),
        'katalog'    => \App\Services\TsthUrl::katalog(),
        'pengunjung' => \App\Services\TsthUrl::pengunjung(),
        default      => url('/'),
    };

    return redirect()->away($baseUrl . '/hub-auth?' . $query);
})->middleware('auth')->name('sso.jump');

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| SEMUA ROUTE PENGGUNA YANG SUDAH LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard umum
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile
    Route::get('/profile/create', [\App\Http\Controllers\ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile/create', [\App\Http\Controllers\ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/show', [\App\Http\Controllers\ProfileController::class,'show'])->name('profile.show');
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin'])->group(function () {

        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::resource('/admin/users', UserController::class)
            ->names('admin.users');

        Route::resource('/admin/departments', DepartmentController::class)
            ->names('admin.departments');

        Route::get(
            '/admin/departments/{department}/divisions',
            [DivisionController::class, 'index']
        )->name('admin.departments.divisions.index');

        Route::get(
            '/admin/departments/{department}/divisions/create',
            [DivisionController::class, 'create']
        )->name('admin.departments.divisions.create');

        Route::post(
            '/admin/departments/{department}/divisions',
            [DivisionController::class, 'store']
        )->name('admin.departments.divisions.store');

        Route::get(
            '/admin/departments/{department}/divisions/{division}/edit',
            [DivisionController::class, 'edit']
        )->name('admin.departments.divisions.edit');

        Route::put(
            '/admin/departments/{department}/divisions/{division}',
            [DivisionController::class, 'update']
        )->name('admin.departments.divisions.update');

        Route::delete(
            '/admin/departments/{department}/divisions/{division}',
            [DivisionController::class, 'destroy']
        )->name('admin.departments.divisions.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | TARGET DAN SUB-TARGET
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin,kepala_departemen'])->group(function () {

        Route::resource('/admin/targets', TargetController::class)
            ->names('admin.targets');

        Route::get(
            '/admin/targets/{target}/sub-targets',
            [SubTargetController::class, 'index']
        )->name('admin.targets.sub-targets.index');

        Route::get(
            '/admin/targets/{target}/sub-targets/create',
            [SubTargetController::class, 'create']
        )->name('admin.targets.sub-targets.create');

        Route::post(
            '/admin/targets/{target}/sub-targets',
            [SubTargetController::class, 'store']
        )->name('admin.targets.sub-targets.store');

        Route::get(
            '/admin/targets/{target}/sub-targets/{subTarget}/edit',
            [SubTargetController::class, 'edit']
        )->name('admin.targets.sub-targets.edit');

        Route::put(
            '/admin/targets/{target}/sub-targets/{subTarget}',
            [SubTargetController::class, 'update']
        )->name('admin.targets.sub-targets.update');

        Route::delete(
            '/admin/targets/{target}/sub-targets/{subTarget}',
            [SubTargetController::class, 'destroy']
        )->name('admin.targets.sub-targets.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | PIMPINAN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:pimpinan'])->group(function () {

        Route::get('/pimpinan/dashboard', [LeaderDashboardController::class, 'index'])
            ->name('pimpinan.dashboard');

        Route::post(
            '/pimpinan/dashboard/summarize',
            [LeaderDashboardController::class, 'summarize']
        )->name('pimpinan.dashboard.summarize');

        Route::get(
            '/pimpinan/employees',
            [DashboardController::class, 'pimpinanEmployees']
        )->name('pimpinan.employees.index');

        Route::get(
            '/pimpinan/employees/{user}',
            [DashboardController::class, 'pimpinanEmployeeDetail']
        )->name('pimpinan.employees.show');
    });

    /*
    |--------------------------------------------------------------------------
    | KEPALA DEPARTEMEN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:kepala_departemen'])->group(function () {

        Route::get(
            '/kepala-departemen/dashboard',
            [DashboardController::class, 'kepalaDepartemen']
        )->name('kepala_departemen.dashboard');

        // Manajemen Project
        Route::resource(
            '/kepala-departemen/projects',
            ProjectController::class
        )
            ->only([
                'index',
                'create',
                'store',
                'show',
                'edit',
                'update',
            ])
            ->names('kepala_departemen.projects');

        // Review Logbook Kepala Divisi
        Route::get(
            '/kepala-departemen/logbooks',
            [KepalaDepartemenLogbookController::class, 'index']
        )->name('kepala_departemen.logbooks.index');

        Route::get(
            '/kepala-departemen/logbooks/{logbook}',
            [KepalaDepartemenLogbookController::class, 'show']
        )->name('kepala_departemen.logbooks.show');

        Route::put(
            '/kepala-departemen/logbooks/{logbook}/review',
            [KepalaDepartemenLogbookController::class, 'review']
        )->name('kepala_departemen.logbooks.review');
    });

            Route::delete(
            '/kepala-departemen/projects/{project}',
            [ProjectController::class, 'destroy']
        )->name('kepala_departemen.projects.destroy');

    
    /*
    |--------------------------------------------------------------------------
    | KEPALA DIVISI
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:kepala_divisi'])->group(function () {

        Route::get(
            '/kepala-divisi/dashboard',
            [DashboardController::class, 'kepalaDivisi']
        )->name('kepala_divisi.dashboard');

        Route::resource('/kepala-divisi/tasks', TaskController::class)
            ->names('kepala_divisi.tasks');

        // Logbook Saya (Logbook Pribadi Kepala Divisi)
        Route::resource('/kepala-divisi/my-logbooks', KepalaDivisiMyLogbookController::class)
            ->parameters(['my-logbooks' => 'logbook'])
            ->names('kepala_divisi.my_logbooks');

        Route::get(
            '/kepala-divisi/logbooks',
            [KepalaDivisiLogbookController::class, 'index']
        )->name('kepala_divisi.logbooks.index');

        Route::get(
            '/kepala-divisi/logbooks/{logbook}',
            [KepalaDivisiLogbookController::class, 'show']
        )->name('kepala_divisi.logbooks.show');

        Route::put(
            '/kepala-divisi/logbooks/{logbook}/review',
            [KepalaDivisiLogbookController::class, 'review']
        )->name('kepala_divisi.logbooks.review');
    });

    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:employee'])->group(function () {

        Route::get(
            '/employee/dashboard',
            [DashboardController::class, 'employee']
        )->name('employee.dashboard');

        Route::get(
            '/employee/tasks',
            [DashboardController::class, 'employeeTasks']
        )->name('employee.tasks.index');

        Route::resource('/employee/logbooks', LogbookController::class)
            ->names('employee.logbooks');
    });

    /*
    |--------------------------------------------------------------------------
    | LEGACY LEADER
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:leader'])->group(function () {

        Route::get('/leader/dashboard', [LeaderDashboardController::class, 'index'])
            ->name('leader.dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | MONITORING LOGBOOK LEADER
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/leader/logbooks',
        [LeaderLogbookController::class, 'index']
    )->name('leader.logbooks.index');

    Route::get(
        '/leader/employees/{user}/logbooks',
        [LeaderLogbookController::class, 'employeeDetail']
    )->name('leader.employees.logbooks');

    Route::get(
        '/leader/logbooks/summary/{userId}',
        [LeaderLogbookController::class, 'summary']
    )->name('leader.logbooks.summary');

    Route::get(
        '/leader/employees/{user}/monthly-summary',
        [LeaderLogbookController::class, 'monthlySummary']
    )->name('leader.employees.monthly-summary');

    Route::get(
        '/leader/logbooks/{logbook}',
        [LeaderLogbookController::class, 'show']
    )->name('leader.logbooks.show');

    Route::post(
        '/leader/logbooks/{logbook}/comments',
        [CommentController::class, 'store']
    )->name('leader.logbooks.comments.store');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
