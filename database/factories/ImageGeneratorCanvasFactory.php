<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ImageGeneratorCanvas;
use Faker\Generator as Faker;

$factory->define(ImageGeneratorCanvas::class, function (Faker $faker) {
    return [
        'width'  => $faker->numberBetween(640, 1920),
        'height' => $faker->numberBetween(640, 1920),
        'status' => 'pending',
        'color'  => '#'.$faker->numberBetween(100000, 999999),
    ];
});