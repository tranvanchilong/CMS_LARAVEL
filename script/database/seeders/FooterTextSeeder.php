<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Menu;
use App\Useroption;

class FooterTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $footer_text = Menu::where('position','footer_text')->select('position as key','user_id','data as value')->get();
        if ($footer_text) {
            foreach ($footer_text as $key => $value) {
                $data_other = Useroption::where('key', $value->key)->where('user_id', $value->user_id)->firstOrNew();
                $data_other->key = $value->key;
                $data_other->value = $value->value;
                $data_other->user_id = $value->user_id;
                $data_other->save();
            }
        }
    }
}
