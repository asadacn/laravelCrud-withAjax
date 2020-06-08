<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'contact'=>$faker->phoneNumber,
        'email'=>$faker->email,
        'address'=>$faker->streetAddress
    ];
});
