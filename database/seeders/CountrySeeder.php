<?php

namespace Database\Seeders;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = ['United States', 'Canada', 'India', 'Australia', 'United Kingdom'];
        
        foreach ($countries as $country) {
            Country::create(['name' => $country]);
        }
    }
}
