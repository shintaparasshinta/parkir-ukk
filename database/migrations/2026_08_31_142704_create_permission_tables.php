<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar Migration
use Illuminate\Database\Schema\Blueprint; // Mengimpor Blueprint
use Illuminate\Support\Facades\Schema; // Mengimpor facade Schema

return new class extends Migration // Anonymous class turunan Migration (dari package Spatie)
{
    /**
     * Run the migrations.
     */
    public function up(): void // Method dijalankan saat migrate
    {
        $teams = config('permission.teams'); // Ambil konfigurasi apakah fitur teams aktif
        $tableNames = config('permission.table_names'); // Ambil nama-nama tabel dari config Spatie
        $columnNames = config('permission.column_names'); // Ambil nama-nama kolom dari config Spatie
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id'; // Nama kolom pivot role (default: role_id)
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id'; // Nama kolom pivot permission (default: permission_id)

        throw_if(empty($tableNames), 'Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.'); // Validasi config ter-load
        throw_if($teams && empty($columnNames['team_foreign_key'] ?? null), 'Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.'); // Validasi config team

        /**
         * See `docs/prerequisites.md` for suggested lengths on 'name' and 'guard_name' if "1071 Specified key was too long" errors are encountered.
         */
        Schema::create($tableNames['permissions'], static function (Blueprint $table) { // Membuat tabel permissions
            $table->id(); // Primary key
            $table->string('name'); // Nama permission (misal: 'hapus_transaksi')
            $table->string('guard_name'); // Guard yang berlaku (misal: 'web')
            $table->timestamps(); // created_at & updated_at

            $table->unique(['name', 'guard_name']); // Kombinasi name + guard harus unik
        });

        /**
         * See `docs/prerequisites.md` for suggested lengths on 'name' and 'guard_name' if "1071 Specified key was too long" errors are encountered.
         */
        Schema::create($tableNames['roles'], static function (Blueprint $table) use ($teams, $columnNames) { // Membuat tabel roles
            $table->id(); // Primary key
            if ($teams || config('permission.testing')) { // Jika fitur teams aktif atau testing
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable(); // Kolom team foreign key
                $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index'); // Index untuk kolom team
            }
            $table->string('name'); // Nama role (misal: 'admin', 'petugas', 'owner')
            $table->string('guard_name'); // Guard yang berlaku
            $table->timestamps(); // created_at & updated_at
            if ($teams || config('permission.testing')) { // Jika teams aktif
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']); // Unik per team
            } else {
                $table->unique(['name', 'guard_name']); // Unik secara global
            }
        });

        Schema::create($tableNames['model_has_permissions'], static function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission, $teams) { // Tabel pivot: model (User) punya permission langsung
            $table->unsignedBigInteger($pivotPermission); // FK ke permissions

            $table->string('model_type'); // Tipe model (App\Models\User)
            $table->unsignedBigInteger($columnNames['model_morph_key']); // ID model (user_id)
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index'); // Composite index

            $table->foreign($pivotPermission)
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->cascadeOnDelete(); // Hapus pivot jika permission dihapus
            if ($teams) { // Jika teams aktif
                $table->unsignedBigInteger($columnNames['team_foreign_key']); // FK team
                $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');

                $table->primary([$columnNames['team_foreign_key'], $pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary'); // Composite primary key dengan team
            } else {
                $table->primary([$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary'); // Composite primary key tanpa team
            }
        });

        Schema::create($tableNames['model_has_roles'], static function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole, $teams) { // Tabel pivot: model (User) punya role
            $table->unsignedBigInteger($pivotRole); // FK ke roles

            $table->string('model_type'); // Tipe model (App\Models\User)
            $table->unsignedBigInteger($columnNames['model_morph_key']); // ID model (user_id)
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index'); // Composite index

            $table->foreign($pivotRole)
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->cascadeOnDelete(); // Hapus pivot jika role dihapus
            if ($teams) { // Jika teams aktif
                $table->unsignedBigInteger($columnNames['team_foreign_key']); // FK team
                $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');

                $table->primary([$columnNames['team_foreign_key'], $pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary'); // Composite primary key dengan team
            } else {
                $table->primary([$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary'); // Composite primary key tanpa team
            }
        });

        Schema::create($tableNames['role_has_permissions'], static function (Blueprint $table) use ($tableNames, $pivotRole, $pivotPermission) { // Tabel pivot: role punya permission
            $table->unsignedBigInteger($pivotPermission); // FK ke permissions
            $table->unsignedBigInteger($pivotRole); // FK ke roles

            $table->foreign($pivotPermission)
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->cascadeOnDelete(); // Hapus pivot jika permission dihapus

            $table->foreign($pivotRole)
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->cascadeOnDelete(); // Hapus pivot jika role dihapus

            $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary'); // Composite primary key
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null) // Pilih store cache (default atau custom)
            ->forget(config('permission.cache.key')); // Hapus cache permission agar di-refresh
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Method dijalankan saat rollback
    {
        $tableNames = config('permission.table_names'); // Ambil nama tabel dari config

        throw_if(empty($tableNames), 'Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.'); // Validasi config

        Schema::dropIfExists($tableNames['role_has_permissions']); // Hapus pivot role_has_permissions (paling akhir)
        Schema::dropIfExists($tableNames['model_has_roles']); // Hapus pivot model_has_roles
        Schema::dropIfExists($tableNames['model_has_permissions']); // Hapus pivot model_has_permissions
        Schema::dropIfExists($tableNames['roles']); // Hapus tabel roles
        Schema::dropIfExists($tableNames['permissions']); // Hapus tabel permissions
    }
};