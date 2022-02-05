<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\DeviceRFID\Models\Entities\ReaderState;
use WalkerChiu\DeviceRFID\Models\Entities\ReaderStateLang;

$factory->define(ReaderState::class, function (Faker $faker) {
    return [
        'serial'     => $faker->isbn10,
        'identifier' => $faker->slug,
        'mean'       => $faker->slug
    ];
});

$factory->define(ReaderStateLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description']),
        'value' => $faker->sentence
    ];
});
