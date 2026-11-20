<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Kolom approval bertingkat & eskalasi pada permintaan_barangs
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('permintaan_barangs', 'target_approval')) {
                $table->string('target_approval')->default('kepala_divisi')->after('status');
            }
            if (!Schema::hasColumn('permintaan_barangs', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('target_approval');
            }
            if (!Schema::hasColumn('permintaan_barangs', 'approved_at')) {
                $table->dateTime('approved_at')->nullable()->after('approved_by');
            }
            if (!Schema::hasColumn('permintaan_barangs', 'approval_level')) {
                $table->string('approval_level')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('permintaan_barangs', 'escalated_at')) {
                $table->dateTime('escalated_at')->nullable()->after('approval_level');
            }
            if (!Schema::hasColumn('permintaan_barangs', 'escalation_reason')) {
                $table->text('escalation_reason')->nullable()->after('escalated_at');
            }
            if (!Schema::hasColumn('permintaan_barangs', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('escalation_reason');
            }
            if (!Schema::hasColumn('permintaan_barangs', 'rejected_at')) {
                $table->dateTime('rejected_at')->nullable()->after('rejected_by');
            }
            if (!Schema::hasColumn('permintaan_barangs', 'catatan_penolakan')) {
                $table->text('catatan_penolakan')->nullable()->after('rejected_at');
            }
        });

        // 2. Pencatatan input & edit pada barangs
        Schema::table('barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('barangs', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('lokasi_blok');
            }
            if (!Schema::hasColumn('barangs', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
        });

        // 3. Pencatatan input pada barang_batches
        Schema::table('barang_batches', function (Blueprint $table) {
            if (!Schema::hasColumn('barang_batches', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('batch_no');
            }
        });

        // 4. Pencatatan input pada satuan_barangs
        Schema::table('satuan_barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('satuan_barangs', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('nama_satuan');
            }
        });

        // 5. Pencatatan input pada satuan_konversis
        if (Schema::hasTable('satuan_konversis')) {
            Schema::table('satuan_konversis', function (Blueprint $table) {
                if (!Schema::hasColumn('satuan_konversis', 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('multiplier');
                }
            });
        }

        // 6. Tabel Log Aktivitas & Mutasi Gudang Terpadu
        if (!Schema::hasTable('aktivitas_gudangs')) {
            Schema::create('aktivitas_gudangs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_name')->default('Sistem');
                $table->string('user_role')->nullable();
                $table->string('modul')->default('gudang'); // barang, batch, satuan, konversi, permintaan
                $table->string('aksi'); // tambah, edit, hapus, approve, reject, eskalasi, pengambilan, pengembalian
                $table->string('tipe_mutasi')->default('netral'); // masuk, keluar, netral
                $table->unsignedBigInteger('barang_id')->nullable();
                $table->string('nama_barang')->nullable();
                $table->double('jumlah')->nullable();
                $table->string('satuan')->nullable();
                $table->text('keterangan');
                $table->string('ip_address')->nullable();
                $table->timestamps();

                $table->index(['modul', 'aksi']);
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_gudangs');

        Schema::table('satuan_konversis', function (Blueprint $table) {
            $table->dropColumn(['created_by']);
        });

        Schema::table('satuan_barangs', function (Blueprint $table) {
            $table->dropColumn(['created_by']);
        });

        Schema::table('barang_batches', function (Blueprint $table) {
            $table->dropColumn(['created_by']);
        });

        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('permintaan_barangs', function (Blueprint $table) {
            $table->dropColumn([
                'target_approval',
                'approved_by',
                'approved_at',
                'approval_level',
                'escalated_at',
                'escalation_reason',
                'rejected_by',
                'rejected_at',
                'catatan_penolakan',
            ]);
        });
    }
};
