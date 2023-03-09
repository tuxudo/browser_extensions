<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Browser_extensions_model::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word(),
        'extension_id' => $faker->word(),
        'browser' => $faker->randomElement(['Firefox', 'Google Chrome']),
        'date_installed' => $faker->dateTimeBetween('-4 months', 'now'),
        'version' => $faker->numberBetween(0, 100),
        'enabled' => $faker->boolean(),
        'description' => $faker->company(),
        'developer' => $faker->word(),
        'user' => $faker->word(),
        'extension_path' => $faker->word(),
    ];
});

