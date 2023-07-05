<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class ThemeNestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $template = Template::updateOrCreate(
            [
                'name' => 'Nest',
            ],
            [   
                'src_path' => 'frontend/nest',
                'asset_path' => 'frontend/nest',
            ]
        );
    }
}
