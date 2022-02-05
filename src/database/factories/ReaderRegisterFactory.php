<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\Core\Models\Constants\DataType;
use WalkerChiu\DeviceRFID\Models\Entities\ReaderRegister;
use WalkerChiu\DeviceRFID\Models\Entities\ReaderRegisterLang;

$factory->define(ReaderRegister::class, function (Faker $faker) {
    return [
        'serial'     => $faker->isbn10,
        'identifier' => $faker->slug,
        'mean'       => $faker->slug,
        'data_type'  => $faker->randomElement(DataType::getCodes())
    ];
});

$factory->define(ReaderRegisterLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description']),
        'value' => $faker->sentence
    ];
});
