<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('partida', function (Blueprint $table) {
            $table->increments('id');
            $table->string('estado');

            $table->integer('jugador1')->unsigned();
            $table->integer('jugador2')->unsigned();
            
            $table->foreign('jugador1')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jugador2')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        
        Schema::create('fichas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('posicion');
            $table->string('nombreFicha');

            $table->integer('jugador')->unsigned();
            $table->foreign('jugador')->references('id')->on('users')->onDelete('cascade');

            $table->integer('id')->unsigned();
            $table->foreign('id')->references('id')->on('partida')->onDelete('cascade');
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
        //
    }
}
