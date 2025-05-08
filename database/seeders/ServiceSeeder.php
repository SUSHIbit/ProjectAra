<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'name' => 'Basic Consultation',
                'description' => 'A basic consultation service for new customers.',
                'price' => 50.00,
            ],
            [
                'name' => 'Premium Service',
                'description' => 'Full service package including all premium features.',
                'price' => 150.00,
            ],
            [
                'name' => 'Express Service',
                'description' => 'Quick service with expedited processing.',
                'price' => 75.00,
            ],
            [
                'name' => 'Annual Membership',
                'description' => 'Annual membership fee with benefits.',
                'price' => 200.00,
            ],
        ];
        
        foreach ($services as $service) {
            Service::create($service);
        }
    }
}