<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $permissions = ['create user','read user','edit user','delete user','create permission','read permission','delete permission','create role','read role','edit role','delete role'];
        foreach($permissions as $item){
            Permission::create([
                'name'=>$item
            ]);
        }

        $role = Role::create([
            'name'=>'admin'
        ]);
        $role->givePermissionTo($permissions);

        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'type'=>'admin'
        ]);
        $user->assignRole($role);
    }
}