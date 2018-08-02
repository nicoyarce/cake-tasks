<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = Role::where('nombre', 'user')->get();
        $role_admin = Role::where('nombre', 'admin')->get();
        $role_cr = Role::where('nombre', 'cr')->get();

        $user = new User();
        $user->nombre = 'User';
        $user->email = 'user@example.com';
        $user->password = bcrypt('secret');
        $user->role_id = $role_user->first()->id;
        $user->save();
        
        $user = new User();
        $user->nombre = 'Admin';
        $user->email = 'admin@example.com';
        $user->password = bcrypt('secret');
        $user->role_id = $role_admin->first()->id;
        $user->save();

        $user = new User();
        $user->nombre = 'CR';
        $user->email = 'cr@example.com';
        $user->password = bcrypt('secret');
        $user->role_id = $role_cr->first()->id;
        $user->save();
    }
}
