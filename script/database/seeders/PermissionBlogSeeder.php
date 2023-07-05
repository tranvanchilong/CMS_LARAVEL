<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$super = User::find(1);
    	
    	
    	$roleSuperAdmin = Role::find(1);
        //create permission
    	$permissions = [
    		[
    			'group_name' => 'Blog',
    			'permissions' => [
    				'blog.create',
    				'blog.edit',
    				'blog.delete',
    				'blog.list',
    				
    			]
    		],  		
    	];
        foreach ($permissions as $key => $row) {


    		foreach ($row['permissions'] as $per) {
    			$permission = Permission::create(['name' => $per, 'group_name' => $row['group_name']]);
    			$roleSuperAdmin->givePermissionTo($permission);
    			$permission->assignRole($roleSuperAdmin);
    			$super->assignRole($roleSuperAdmin);
    		}
    	}
    }
}
