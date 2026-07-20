<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientLogo;
use Illuminate\Support\Str;

class ClientLogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            'BIRLA OPEN MINDS',
            'D. GOENKA',
            'Mount Litera Zee School',
            'PW',
            'ALLEN',
            'VMC Classes'
        ];

        foreach ($clients as $client) {
            $filename = Str::slug($client) . '.png';
            
            ClientLogo::create([
                'name' => $client,
                'logo_path' => 'clients/' . $filename,
                'is_active' => true,
            ]);
        }
    }
}
