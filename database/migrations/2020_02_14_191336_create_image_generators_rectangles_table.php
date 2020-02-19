<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageGeneratorsRectanglesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_generators_rectangles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('width');
            $table->integer('height');
            $table->integer('x');
            $table->integer('y');
            $table->string('color', 7);
            $table->integer('gen_job_id');
            $table->string('rect_id');
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
        Schema::dropIfExists('image_generators_rectangles');
    }
}
