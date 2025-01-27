<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sourceFiles = [
            '/images/logo/logo-dark.png',
            '/images/favicon.png',
        ];

        foreach($sourceFiles as $file){
            Storage::disk('public')->put($file, file_get_contents(public_path($file)));
        }

        $settings = [
            [
                'key' => 'logo',
                'value' => '/images/logo/logo-dark.png',
            ],
            [
                'key' => 'favicon',
                'value' => '/images/favicon.png',
            ],
            [
                'key' => 'name',
                'value' => 'Template',
            ],

        ];

        foreach ($settings as $setting) {
            AppSetting::query()->create($setting);
        }
    }
}
