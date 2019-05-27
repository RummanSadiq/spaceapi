<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');

            // Store Owner
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('shop_type_id')->unsigned();
            $table->foreign('shop_type_id')->references('id')->on('shop_types');

            $table->integer('address_id')->unsigned()->nullable();
            $table->foreign('address_id')->references('id')->on('addresses');

            $table->string('name');
            $table->string('contact')->nullable();
            // $table->string('display_picture')->nullable();
            $table->boolean('wifi')->nullable();
            $table->boolean('try_room')->nullable();
            $table->boolean('card_payment')->nullable();
            $table->boolean('wheel_chair')->nullable();
            $table->boolean('wash_room')->nullable();
            $table->boolean('delivery')->nullable();

            $table->text('return_policy')->nullable();

            $table->time('open_at')->nullable();
            $table->time('close_at')->nullable();


            $table->timestamp('approved_at')->nullable();


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
        Schema::dropIfExists('shops');
    }
}
