<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateFixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = ['Maharashtra', 'Delhi', 'Karnataka', 'Gujarat', 'Tamil Nadu', 'Uttar Pradesh', 'West Bengal', 'Rajasthan', 'Kerala', 'Punjab', 'Haryana', 'Bihar'];
        
        $locations = \App\Models\Location::all();
        
        foreach($locations as $index => $location) {
            if(isset($states[$index])) {
                $location->update(['state' => $states[$index]]);
            }
        }
    }
}
