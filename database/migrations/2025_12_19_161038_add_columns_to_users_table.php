<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom yang kurang
            if (!Schema::hasColumn('users', 'npk')) {
                $table->string('npk')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'departement')) {
                $table->string('departement')->nullable()->after('npk');
            }
            if (!Schema::hasColumn('users', 'section')) {
                $table->string('section')->nullable()->after('departement');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['npk', 'departement', 'section']);
        });
    }
}
