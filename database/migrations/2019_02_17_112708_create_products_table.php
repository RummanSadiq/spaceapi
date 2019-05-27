<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');

            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shops');

            $table->string('name');
            $table->text('description')->nullable();
            // $table->string('display_picture')->nullable();
            $table->integer('price');

            $table->integer('sale_price')->nullable();

            $table->timestamp('sale_starts_at')->nullable();
            $table->timestamp('sale_ends_at')->nullable();


            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');


            $table->boolean('is_active')->default(1);

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
