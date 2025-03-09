<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $default_user_value = [
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];

        $super_admin = User::create(
            array_merge([
                'email' => 'admin@gmail.com',
                'name' => 'Super Admin',
                'username' => 'admin',
                'instansi' => 'Universitas Brawijaya'
            ], $default_user_value)
        );

        $customer = User::create(
            array_merge([
                'email' => 'yamal@gmail.com',
                'name' => 'Lamine Yamal',
                'username' => 'yamal',
                'instansi' => 'Barcelona'
            ], $default_user_value)
        );

        $roleSuperAdmin = Role::create(['name' => 'Super Admin']);
        $roleOperator = Role::create(['name' => 'Operator']);
        $roleCustomer = Role::create(['name' => 'Customer']);

        $menus = ['Dashboard', 'Configuration', 'Gedung'];

        $menus1 = ['User-Configuration', 'Role-Configuration'];

        $menus2 = ['User', 'Gedung'];

        $menus3 = ['Role'];

        foreach ($menus as $key => $menu) {
            Permission::create(['name' => $menu, 'order' => $key + 1]);
            $roleSuperAdmin->givePermissionTo($menu);
        }

        foreach ($menus1 as $menu1) {
            Permission::create(['name' => $menu1]);
            $roleSuperAdmin->givePermissionTo($menu1);
        }

        foreach ($menus2 as $m) {
            Permission::create(['name' => $m . ' Read']);
            Permission::create(['name' => $m . ' Create']);
            Permission::create(['name' => $m . ' Edit']);
            Permission::create(['name' => $m . ' Delete']);

            $roleSuperAdmin->givePermissionTo($m . ' Read');
            $roleSuperAdmin->givePermissionTo($m . ' Create');
            $roleSuperAdmin->givePermissionTo($m . ' Edit');
            $roleSuperAdmin->givePermissionTo($m . ' Delete');
        }

        foreach ($menus3 as $m3) {
            Permission::create(['name' => $m3 . ' Create']);
            Permission::create(['name' => $m3 . ' Edit']);
            Permission::create(['name' => $m3 . ' Delete']);

            $roleSuperAdmin->givePermissionTo($m3 . ' Create');
            $roleSuperAdmin->givePermissionTo($m3 . ' Edit');
            $roleSuperAdmin->givePermissionTo($m3 . ' Delete');
        }

        Permission::create(['name' => 'Role Setting']);
        $roleSuperAdmin->givePermissionTo('Role Setting');

        $super_admin->assignRole($roleSuperAdmin);
    }
}
