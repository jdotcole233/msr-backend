<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblActorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_actors', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->references('id')
            ->on('users')->cascadeOnDelete();
            $table->string('name', 255)->nullable();
            $table->string('contactPhone', 20)->nullable();
            $table->enum('ageGroup', ['15-29', '30+', 'mixed'])->default(null);
            $table->boolean('isMale')->nullable();
            $table->string('region', 255)->nullable();
            $table->string('townCity', 255)->nullable();
            $table->string('district', 255)->nullable();
            $table->integer('status')->nullable();
            $table->string('lastUpdatedByName')->nullable();
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
        Schema::dropIfExists('tbl_actors');
    }
}
