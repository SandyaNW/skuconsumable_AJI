<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdPengajuanToSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sku_submissions', function (Blueprint $table) {
            $table->string('id_pengajuan')->unique()->nullable()->after('id');
            $table->integer('dept_id')->nullable()->after('npk');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sku_submissions', function (Blueprint $table) {
            $table->dropColumn(['id_pengajuan', 'dept_id']);
        });
    }
}
