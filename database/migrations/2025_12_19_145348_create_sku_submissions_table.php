<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkuSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sku_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('npk');
            $table->string('departement');
            $table->string('section')->nullable();
            $table->text('remarks')->nullable();
            $table->date('issue_date');
            // Kolom Status Approval: 0=Draft, 1=Pending Dept Head, 2=Approved by FA, 3=Rejected
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sku_submissions');
    }
}
