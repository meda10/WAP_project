<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'customer', 'guard_name' => 'api']);
        Permission::create(['name' => 'employee', 'guard_name' => 'api']);
        Permission::create(['name' => 'manager', 'guard_name' => 'api']);
        Permission::create(['name' => 'director', 'guard_name' => 'api']);
        Permission::create(['name' => 'Upload images', 'guard_name' => 'api']);
        Permission::create(['name' => 'Basic permissions', 'guard_name' => 'api']);
        Permission::create(['name' => 'Assign role', 'guard_name' => 'api']);
        Permission::create(['name' => 'Remove role', 'guard_name' => 'api']);
        Permission::create(['name' => 'View all permissions', 'guard_name' => 'api']);
        Permission::create(['name' => 'Edit all permissions', 'guard_name' => 'api']);
        Permission::create(['name' => 'View_all_users', 'guard_name' => 'api']);
        Permission::create(['name' => 'Edit all users', 'guard_name' => 'api']);
        Permission::create(['name' => 'Add all users', 'guard_name' => 'api']);
        Permission::create(['name' => 'Confirm users', 'guard_name' => 'api']);
        Permission::create(['name' => 'View all titles', 'guard_name' => 'api']);
        Permission::create(['name' => 'Edit all titles', 'guard_name' => 'api']);
        Permission::create(['name' => 'View all participants', 'guard_name' => 'api']);
        Permission::create(['name' => 'Edit all participants', 'guard_name' => 'api']);
        Permission::create(['name' => 'View all reservations', 'guard_name' => 'api']);
        Permission::create(['name' => 'Edit all reservations', 'guard_name' => 'api']);
        Permission::create(['name' => 'View all stores', 'guard_name' => 'api']);
        Permission::create(['name' => 'Edit all stores', 'guard_name' => 'api']);
        Permission::create(['name' => 'View all discounts', 'guard_name' => 'api']);
        Permission::create(['name' => 'Edit all discounts', 'guard_name' => 'api']);
        Permission::create(['name' => 'Administration', 'guard_name' => 'api']);
//        Permission::create(['name' => '']);

        $customer = Role::create(['name' => 'customer', 'guard_name' => 'api']);
        $customer->givePermissionTo('customer');
        $customer->givePermissionTo('View all titles');
        $customer->givePermissionTo('Basic permissions');

        $employee = Role::create(['name' => 'employee', 'guard_name' => 'api']);
        $employee->givePermissionTo('employee');
        $employee->givePermissionTo('Basic permissions');
        $employee->givePermissionTo('Administration');
        $employee->givePermissionTo('View all titles');
        $employee->givePermissionTo('View_all_users');
        $employee->givePermissionTo('Add all users');
        $employee->givePermissionTo('Confirm users');
        $employee->givePermissionTo('View all participants');
        $employee->givePermissionTo('View all reservations');
        $employee->givePermissionTo('Edit all reservations');
        $employee->givePermissionTo('View all stores');

        $manager = Role::create(['name' => 'manager', 'guard_name' => 'api']);
        $manager->givePermissionTo('manager');
        $manager->givePermissionTo('Basic permissions');
        $manager->givePermissionTo('Administration');
        $manager->givePermissionTo('Upload images');
        $manager->givePermissionTo('Basic permissions');
        $manager->givePermissionTo('Edit all titles');
        $manager->givePermissionTo('View all titles');
        $manager->givePermissionTo('View_all_users');
        $manager->givePermissionTo('Add all users');
        $manager->givePermissionTo('Edit all users');
        $manager->givePermissionTo('Confirm users');
        $manager->givePermissionTo('View all participants');
        $manager->givePermissionTo('View all reservations');
        $manager->givePermissionTo('Edit all reservations');
        $manager->givePermissionTo('View all stores');

        $director = Role::create(['name' => 'director', 'guard_name' => 'api']);
        $director->givePermissionTo('director');
        $director->givePermissionTo('Upload images');
        $director->givePermissionTo('Basic permissions');
        $director->givePermissionTo('Assign role');
        $director->givePermissionTo('Remove role');
        $director->givePermissionTo('View all permissions');
        $director->givePermissionTo('Edit all permissions');
        $director->givePermissionTo('View_all_users');
        $director->givePermissionTo('Add all users');
        $director->givePermissionTo('Edit all users');
        $director->givePermissionTo('Confirm users');
        $director->givePermissionTo('View all titles');
        $director->givePermissionTo('Edit all titles');
        $director->givePermissionTo('View all participants');
        $director->givePermissionTo('Edit all participants');
        $director->givePermissionTo('View all reservations');
        $director->givePermissionTo('Edit all reservations');
        $director->givePermissionTo('View all stores');
        $director->givePermissionTo('Edit all stores');
        $director->givePermissionTo('View all discounts');
        $director->givePermissionTo('Edit all discounts');
        $director->givePermissionTo('Administration');


        $user_director = User::findOrFail(1);
        $user_manager = User::findOrFail(2);
        $user_employee = User::findOrFail(3);
        $user_customer = User::findOrFail(4);

        $user_director->assignRole($director);
        $user_manager->assignRole($manager);
        $user_employee->assignRole($employee);
        $user_customer->assignRole($customer);

    }
}
