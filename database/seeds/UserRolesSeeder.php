<?php

use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\HttpOz\Roles\Models\Role::create([
    	    'name' => 'Admin',
    	    'slug' => 'admin',
    	    'description' => 'Administrador Del Sistema.', // optional
    	    'group' => 'default' // optional, set as 'default' by default
    	]);
    	\HttpOz\Roles\Models\Role::create([
    	    'name' => 'Empleado',
    	    'slug' => 'employee',
    	    'description' => 'Empleado.', // optional
    	    'group' => 'default' // optional, set as 'default' by default
    	]);
    }
}
