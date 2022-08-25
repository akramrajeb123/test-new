<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // GABES
        DB::table('store')->insert([
            'label' => "HA Gabès"
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tincidunt ligula enim, ut aliquet sapien viverra nec." ,
            'ville_id' => 1,
            'lat' => "33.8866",
            'lnt' => "10.1047",
            'address' => "" ,
            'type' => "BOUTIQUE",
        ]);
        DB::table('store')->insert([
            'label' => "Batam Gabes",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tincidunt ligula enim, ut aliquet sapien viverra nec." ,
            'ville_id' => 1,
            'lat' => "33.8785",
            'lnt' => "10.0933",
            'address' => "V3HV+98J, Boulevard Mohammed Ali, Gabes" ,
            'type' => "MAGASIN",
        ]);

        // SOUSSE
        DB::table('store')->insert([
            'label' => "HA Gabès",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tincidunt ligula enim, ut aliquet sapien viverra nec." ,
            'ville_id' => 1,
            'lat' => "33.8866",
            'lnt' => "10.1047",
            'address' => "" ,
            'type' => "BOUTIQUE",
        ]);
        DB::table('store')->insert([
            'label' => "Batam Gabes",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tincidunt ligula enim, ut aliquet sapien viverra nec." ,
            'ville_id' => 1,
            'lat' => "33.8785",
            'lnt' => "10.0933",
            'address' => "V3HV+98J, Boulevard Mohammed Ali, Gabes" ,
            'type' => "MAGASIN"
        ]);
        // TUNIS
        DB::table('store')->insert([
            'label' => "HA Gabès",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tincidunt ligula enim, ut aliquet sapien viverra nec." ,
            'ville_id' => 1,
            'lat' => "33.8866",
            'lnt' => "10.1047",
            'address' => "" ,
            'type' => "BOUTIQUE",
        ]);
        DB::table('store')->insert([
            'label' => "Batam Gabes",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tincidunt ligula enim, ut aliquet sapien viverra nec." ,
            'ville_id' => 1,
            'lat' => "33.8785",
            'lnt' => "10.0933",
            'address' => "V3HV+98J, Boulevard Mohammed Ali, Gabes" ,
            'type' => "MAGASIN"
        ]);

        // SFAX
        DB::table('store')->insert([
            'label' => "HA Gabès",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tincidunt ligula enim, ut aliquet sapien viverra nec." ,
            'ville_id' => 1,
            'lat' => "33.8866",
            'lnt' => "10.1047",
            'address' => "" ,
            'type' => "BOUTIQUE"
        ]);
        DB::table('store')->insert([
            'label' => "Batam Gabes",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tincidunt ligula enim, ut aliquet sapien viverra nec." ,
            'ville_id' => 1,
            'lat' => "33.8785",
            'lnt' => "10.0933",
            'address' => "V3HV+98J, Boulevard Mohammed Ali, Gabes" ,
            'type' => "MAGASIN",
        ]);
    }
}
