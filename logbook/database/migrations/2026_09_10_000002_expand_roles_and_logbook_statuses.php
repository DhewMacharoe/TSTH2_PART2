<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin','employee','leader','supervisor') NOT NULL DEFAULT 'employee'");
            DB::statement("ALTER TABLE logbooks MODIFY status ENUM('pending','reviewed','approved','rejected') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        DB::table('logbooks')->whereIn('status', ['approved', 'rejected'])->update(['status' => 'reviewed']);
        DB::table('users')->where('role', 'supervisor')->update(['role' => 'employee']);
        
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin','employee','leader') NOT NULL DEFAULT 'employee'");
            DB::statement("ALTER TABLE logbooks MODIFY status ENUM('pending','reviewed') NOT NULL DEFAULT 'pending'");
        }
    }
};