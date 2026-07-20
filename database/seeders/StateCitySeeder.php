<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\City;

class StateCitySeeder extends Seeder
{
    public function run()
    {
        // 1. Create Bihar State
        $bihar = State::firstOrCreate(['name' => 'Bihar', 'is_active' => true]);

        // 2. Cities in Bihar
        $biharCities = [
            'Patna',
            'Gaya',
            'Bhagalpur',
            'Muzaffarpur',
            'Purnia',
            'Darbhanga',
            'Bihar Sharif',
            'Arrah',
            'Begusarai',
            'Katihar',
            'Munger',
            'Chhapra',
            'Danapur',
            'Bettiah',
            'Saharsa',
            'Hajipur',
            'Sasaram',
            'Dehri',
            'Siwan',
            'Motihari',
            'Nawada',
            'Bagaha',
            'Buxar',
            'Kishanganj',
            'Sitamarhi',
            'Jamalpur',
            'Jehanabad',
            'Aurangabad'
        ];

        foreach ($biharCities as $city) {
            City::firstOrCreate([
                'state_id' => $bihar->id,
                'name' => $city,
            ], [
                'is_active' => true,
            ]);
        }

        // Add other states for dummy data
        $otherStates = ['Delhi', 'Haryana', 'Uttar Pradesh', 'Maharashtra', 'Karnataka', 'Tamil Nadu', 'Rajasthan'];
        foreach ($otherStates as $stateName) {
            $state = State::firstOrCreate(['name' => $stateName, 'is_active' => true]);
            
            // Add a default city for each to ensure dummy data works
            City::firstOrCreate([
                'state_id' => $state->id,
                'name' => 'Capital City of ' . $stateName,
            ], [
                'is_active' => true,
            ]);
        }
    }
}
