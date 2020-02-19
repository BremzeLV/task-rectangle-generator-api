<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageGeneratorsCanvasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_generators_canvas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('width');
            $table->integer('height');
            $table->string('color', 7);
            $table->string('status', 15);
            $table->string('generated_image_url')->nullable();
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
        Schema::dropIfExists('image_generators_canvas');
    }
}
