<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLending extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lendings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('movie_id');
            $table->unsignedBigInteger('member_id');
            $table->date('lending_date');
            $table->date('return_date');
            $table->date('returned_date')->nullable();
            $table->float('lateness_charge')->nullable();
            $table->timestamps();

            // relation table
            $table->foreign('movie_id')
            ->references('id')->on('movies')
            ->onDelete('cascade');

            $table->foreign('member_id')
            ->references('id')->on('members')
            ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lendings');
    }
}
