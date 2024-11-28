<?php

use Database\Seeders\WarehouseSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $warehouses = [
            [
                'id' => 1,
                'name' => 'RawMaterial GoDown',
                // 'capacity' => 1000000,
                // 'used_capacity' => 0,
            ],
            [
                'id' => 2,
                'name' => 'Stiching Warehouse',
                // 'capacity' => 1000000,
                // 'used_capacity' => 0,
            ],
            [
                'id' => 3,
                'name' => 'Grade A',
                // 'capacity' => 1000000,
                // 'used_capacity' => 0,
            ],
            [
                'id' => 4,
                'name' => 'Grade B',
                // 'capacity' => 1000000,
                // 'used_capacity' => 0,
            ],
        ];

        $units = [
            [
                'unit_name' => 'Kilogram',
                'symbol' => 'kg'
            ],
            [
                'unit_name' => 'Gram',
                'symbol' => 'g'
            ],
            [
                'unit_name' => 'Pound',
                'symbol' => 'p'
            ],
            [
                'unit_name' => 'Tonne',
                'symbol' => 't'
            ],
            [
                'unit_name' => 'Liter',
                'symbol' => 'l'
            ],
            [
                'unit_name' => 'KiloLiter',
                'symbol' => 'kl'
            ],
            [
                'unit_name' => 'Gallon',
                'symbol' => 'gal'
            ],
            [
                'unit_name' => 'MiliLiter',
                'symbol' => 'ml'
            ],
            [
                'unit_name' => 'Meter',
                'symbol' => 'm'
            ],
            [
                'unit_name' => 'CentiMeter',
                'symbol' => 'cm'
            ],
            [
                'unit_name' => 'KiloMeter',
                'symbol' => 'km'
            ],
            [
                'unit_name' => 'Milimeter',
                'symbol' => 'mm'
            ],
            [
                'unit_name' => 'Foot',
                'symbol' => 'ft'
            ],
            [
                'unit_name' => 'Inch',
                'symbol' => 'in'
            ],
        ];

        foreach ($units as $unit) {
            $unit['unit_system_default'] = 'yes';
            $unit['unit_time_default'] = 'yes';
            DB::table('units')->insert($unit);
        }

        DB::table('warehouses')->insert($warehouses);
    }
}
