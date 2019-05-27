<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('url');

            $table->integer('parent_id')->unsigned();

            // $table->integer('user_id')->unsigned();
            // $table->integer('product_id')->unsigned();
            // $table->integer('post_id')->unsigned();
            // $table->integer('shop_id')->unsigned();
            // $table->integer('review_id')->unsigned();

            // $table->foreign(['user_id', 'product_id', 'post_id', 'shop_id', 'review_id'])->references('id') - on(['users', 'products', 'posts', 'shops', 'reviews']);

            $table->string('type');

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
        Schema::dropIfExists('attachments');
    }
}
