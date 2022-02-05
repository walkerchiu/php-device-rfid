<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\DeviceRFID\Models\Entities\Card;
use WalkerChiu\DeviceRFID\Models\Entities\CardLang;

$factory->define(Card::class, function (Faker $faker) {
    return [
        'serial'     => $faker->isbn10,
        'identifier' => $faker->slug,
        'username'   => $faker->username,
        'is_black'   => $faker->boolean,
        'is_enabled' => false,
        'begin_at'   => '2019-01-01 00:00:00',
        'end_at'     => '2019-01-01 01:00:00'
    ];
});

$factory->define(CardLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description']),
        'value' => $faker->sentence
    ];
});
