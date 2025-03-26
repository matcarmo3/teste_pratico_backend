<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gateway;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Estou utilizando o metodo updateOrCreate para caso execute 2x os seeders, assim evitando erros.
        Gateway::updateOrCreate(
            ['name' => 'Gateway 1'],
            [
                'name' => 'Gateway 1',
                'class_name' => 'Gateway1',
                'active' => true,
                'priority' => 1
            ]
        );

        Gateway::updateOrCreate(
            ['name' => 'Gateway 2'],
            [
                'name' => 'Gateway 2',
                'class_name' => 'Gateway2',
                'active' => true,
                'priority' => 0
            ]
        );
    }
}
