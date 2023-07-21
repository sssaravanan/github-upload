<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('products')->insert([
            [
                'name' => 'Apple iPhone',
                'price' => 19990.00,
                'description' => 'Features 3.5″ display, 2 MP primary camera, 1400 mAh battery, 16 GB storage, Corning Gorilla Glass',
                'status' => 'active'
            ],
            [
                'name' => 'Samsung Tablet',
                'price' => 8999.00,
                'description' => 'Samsung Galaxy Tab A is powered by a 1.2GHz quad-core processor. It comes with 2GB of RAM. The Samsung Galaxy Tab A runs Android 5.0 and is powered by a 4200mAh',
                'status' => 'active'
            ],
            [
                'name' => 'Sandisk Memory Card',
                'price' => 349.00,
                'description' => 'SANDISK SDSQXCG-032G-GN6MA | Memory card; Android,Extreme Pro,A1',
                'status' => 'active'
            ],
            [
                'name' => 'boAt Smart Watch',
                'price' => 499.00,
                'description' => 'boAt Xtend Smartwatch with Alexa Built-in, 1.69 HD Display, Multiple Watch Faces, Stress Monitor, Heart & SpO2 Monitoring, 14 Sports Modes, Sleep Monitor, 5 ATM & 7 Days Battery',
                'status' => 'active'
            ],
            [
                'name' => 'HP Laptop',
                'price' => 21199.00,
                'description' => 'HP Notebook - 15-ac650tu ; Microprocessor. Intel® Core™ i5-4210U with Intel HD Graphics 4400 (1.7 GHz, up to 2.7 GHz, 3 MB cache, 2 cores)',
                'status' => 'active'
            ]
            // Add more sample users here
        ]);
    }
}
