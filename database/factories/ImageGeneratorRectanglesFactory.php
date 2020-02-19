<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ImageGeneratorRectangles;
use Faker\Generator as Faker;

$factory->define(ImageGeneratorRectangles::class, function (Faker $faker) {
    return [
        'width'  => $faker->numberBetween(5, 10),
        'height' => $faker->numberBetween(5, 10),
        'x'      => $faker->numberBetween(5, 10),
        'y'      => $faker->numberBetween(5, 10),
        'color'  => '#'.$faker->numberBetween(100000, 999999),
        'rect_id' => 'my-id'.$faker->randomNumber(),
    ];
});
