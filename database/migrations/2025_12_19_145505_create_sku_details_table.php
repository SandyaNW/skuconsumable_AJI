<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkuDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sku_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sku_submission_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->string('specification')->nullable();
            $table->string('product_code')->nullable();
            $table->string('sku')->nullable();
            $table->integer('qty');
            $table->string('uom');
            $table->string('category')->nullable();
            $table->integer('usage')->default(0)->nullable(); // Menampung Usage/Month
            $table->text('keperluan')->nullable();
            $table->string('lampiran_foto')->nullable(); // Simpan path file di sini
            $table->date('due_date')->nullable();
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
        Schema::dropIfExists('sku_details');
    }
}
