<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');

            //Publisher id
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            //Shop or Product Id
            $table->integer('parent_id')->unsigned();
            // $table->foreign('parent_id')->references('id')->on('stores');


            $table->float('rating');
            $table->text('description')->nullable();

            $table->string('type');

            $table->boolean('is_active')->default(1);

            $table->integer('votes')->default(0);
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
        Schema::dropIfExists('reviews');
    }
}
