<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('sku_code')->unique();
            $table->string('item_name');
            $table->text('specification')->nullable();
            $table->string('uom');
            $table->string('category');

            // Penting untuk sistem (Jangan dihapus)
            $table->enum('input_source', ['submission', 'existing'])->default('existing');
            $table->unsignedBigInteger('sku_submission_id')->nullable();
            
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
        Schema::dropIfExists('products');
    }
}
