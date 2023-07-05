<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserTableSeeder;
use Database\Seeders\CategoryTableSeeder;
use Database\Seeders\PermissionBlogSeeder;
use Database\Seeders\PermissionBlogCategorySeeder;
use Database\Seeders\ThemeNestTableSeeder;
use Database\Seeders\FPSyncTableSeeder;
use Database\Seeders\FPSyncTableSeeder2;
use Database\Seeders\ElementImageContentSeeder;
use Database\Seeders\FooterTextSeeder;
use Database\Seeders\SplitTermPostTableSeeder;
use Database\Seeders\SplitCustomerAffiliateUsersTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
             UserTableSeeder::class,
             CategoryTableSeeder::class,
            PermissionBlogSeeder::class,
            PermissionBlogCategorySeeder::class,
            ThemeNestTableSeeder::class,
            FPSyncTableSeeder::class,
            FPSyncTableSeeder2::class,
            ElementImageContentSeeder::class,
            FooterTextSeeder::class,
            SplitTermPostTableSeeder::class,
            SplitCustomerAffiliateUsersTableSeeder::class,
            FPSyncTableSeeder4::class,
        ]);
      
    }
}
