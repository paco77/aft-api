<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingSetting;

class LandingSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = LandingSetting::defaults();

        foreach ($defaults as $key => $meta) {
            LandingSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $meta['value'],
                    'type' => $meta['type'],
                    'group' => $meta['group'],
                    'label' => $meta['label'],
                ]
            );
        }

        LandingSetting::clearCache();
    }
}
