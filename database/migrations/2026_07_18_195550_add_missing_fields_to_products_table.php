<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('usage_month')->nullable()->after('sku_submission_id');
            $table->integer('moq')->nullable()->after('usage_month');
            $table->integer('lot')->nullable()->after('moq');
            $table->integer('min')->nullable()->after('lot');
            $table->integer('rop')->nullable()->after('min');
            $table->integer('max')->nullable()->after('rop');
            $table->string('status')->default('active')->after('max');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['usage_month', 'moq', 'lot', 'min', 'rop', 'max', 'status']);
        });
    }
}
