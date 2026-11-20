<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Division;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalEmployees = User::where('role', 'employee')->count();

        $totalDepartmentHeads = User::where('role', 'kepala_departemen')->count();
        $totalDivisionHeads = User::where('role', 'kepala_divisi')->count();

        $totalDepartments = Department::count();
        $totalDivisions = Division::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalEmployees',
            'totalDepartmentHeads',
            'totalDivisionHeads',
            'totalDepartments',
            'totalDivisions'
        ));
    }
}