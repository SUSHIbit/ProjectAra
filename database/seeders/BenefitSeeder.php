<?php

namespace Database\Seeders;

use App\Models\Benefit;
use Illuminate\Database\Seeder;

class BenefitSeeder extends Seeder
{
    public function run()
    {
        $benefits = [
            [
                'name' => 'Discount 10%',
                'description' => 'Get 10% discount on all services.',
                'is_active' => true,
            ],
            [
                'name' => 'Free Consultation',
                'description' => 'Receive a free consultation session.',
                'is_active' => true,
            ],
            [
                'name' => 'Priority Service',
                'description' => 'Get priority service with reduced waiting time.',
                'is_active' => true,
            ],
            [
                'name' => 'Extended Support',
                'description' => 'Extended customer support hours.',
                'is_active' => true,
            ],
        ];
        
        foreach ($benefits as $benefit) {
            Benefit::create($benefit);
        }
    }
}