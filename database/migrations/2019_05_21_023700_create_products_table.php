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
        if ( !Schema::hasTable('products') ) {

            Schema::create('products', function(Blueprint $table) {

                $table->bigIncrements('id');
                $table->integer('user_id');
                $table->string('name');
                $table->integer('price');
                $table->integer('quantity');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('user_id')
                      ->references('id')
                      ->on('users');
                      // ->onDelete('cascade');

                $table->engine = 'InnoDB';
                $table->charset = 'utf8';
                $table->collation = 'utf8_unicode_ci';
            });
        }
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
