<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            [
                'name' => 'Classic Black & White',
                'slug' => 'classic-black-white',
                'description' => 'Timeless elegance with high contrast',
                'colors' => [
                    'primary' => '#000000',
                    'secondary' => '#FFFFFF',
                    'accent' => '#808080',
                    'background' => '#FFFFFF',
                    'text' => '#000000',
                    'heading' => '#000000',
                ],
            ],
            [
                'name' => 'Ocean Blue',
                'slug' => 'ocean-blue',
                'description' => 'Calming ocean-inspired palette',
                'colors' => [
                    'primary' => '#0077BE',
                    'secondary' => '#00A8E8',
                    'accent' => '#007EA7',
                    'background' => '#F0F8FF',
                    'text' => '#2C3E50',
                    'heading' => '#003459',
                ],
            ],
            [
                'name' => 'Forest Green',
                'slug' => 'forest-green',
                'description' => 'Natural and refreshing green tones',
                'colors' => [
                    'primary' => '#2D6A4F',
                    'secondary' => '#52B788',
                    'accent' => '#40916C',
                    'background' => '#F1F8F4',
                    'text' => '#1B4332',
                    'heading' => '#081C15',
                ],
            ],
            [
                'name' => 'Sunset Orange',
                'slug' => 'sunset-orange',
                'description' => 'Warm and energetic sunset colors',
                'colors' => [
                    'primary' => '#FF6B35',
                    'secondary' => '#F7931E',
                    'accent' => '#FBB13C',
                    'background' => '#FFF5EB',
                    'text' => '#4A4A4A',
                    'heading' => '#8B4513',
                ],
            ],
            [
                'name' => 'Royal Purple',
                'slug' => 'royal-purple',
                'description' => 'Luxurious and sophisticated purple scheme',
                'colors' => [
                    'primary' => '#6B2C91',
                    'secondary' => '#9B4DCA',
                    'accent' => '#D4A5E5',
                    'background' => '#F8F5FA',
                    'text' => '#2D1B3D',
                    'heading' => '#4A1B66',
                ],
            ],
            [
                'name' => 'Modern Brown',
                'slug' => 'modern-brown',
                'description' => 'Earthy brown tones inspired by wowlogbook.com',
                'colors' => [
                    'primary' => '#8B4513',
                    'secondary' => '#A0522D',
                    'accent' => '#D2691E',
                    'background' => '#FFFFFF',
                    'text' => '#000000',
                    'heading' => '#654321',
                ],
            ],
        ];

        foreach ($themes as $theme) {
            Theme::create($theme);
        }
    }
}
