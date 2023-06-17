<?php

use App\User;
use App\Company;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \Carbon\Carbon::now();

        // Reset cached roles and permissions
        Schema::disableForeignKeyConstraints();
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        Permission::create(['name' => 'update permission']);

        //Users
        Permission::create(['name' => 'show user']);
        Permission::create(['name' => 'accept user']);
        Permission::create(['name' => 'update user']);

        // Company
        Permission::create(['name' => 'show company']);
        Permission::create(['name' => 'edit company']);

        // Participant Databases
        Permission::create(['name' => 'show database']);
        Permission::create(['name' => 'create database']);
        Permission::create(['name' => 'edit database']);

        // Participants
        Permission::create(['name' => 'show participant']);
        Permission::create(['name' => 'create participant']);
        Permission::create(['name' => 'edit participant']);

        // Actor
        Permission::create(['name' => 'show actor']);
        Permission::create(['name' => 'create actor']);
        Permission::create(['name' => 'edit actor']);

        // Sponsor
        Permission::create(['name' => 'show sponsor']);
        Permission::create(['name' => 'create sponsor']);
        Permission::create(['name' => 'edit sponsor']);

        // Event
        Permission::create(['name' => 'show event']);
        Permission::create(['name' => 'create event']);
        Permission::create(['name' => 'edit event']);

        // Polls
        Permission::create(['name' => 'show poll']);
        Permission::create(['name' => 'create poll']);
        Permission::create(['name' => 'edit poll']);

        // Products
        Permission::create(['name' => 'show product']);
        Permission::create(['name' => 'create product']);
        Permission::create(['name' => 'edit product']);

        // Webpay
        Permission::create(['name' => 'pay']);

        // Permissions
        Permission::create(['name' => 'edit permissions']);

        // Roles
        // Base Role
        Role::create(['name' => 'base'])
            ->givePermissionTo(['show user', 'show company', 'show actor']);

        Role::create(['name' => 'actor-manager'])
            ->givePermissionTo(['show actor', 'create actor', 'edit actor']);

        Role::create(['name' => 'database-manager'])
            ->givePermissionTo(['show database', 'create database', 'edit database']);

        Role::create(['name' => 'participant-manager'])
            ->givePermissionTo(['show participant', 'create participant', 'edit participant']);

        Role::create(['name' => 'event-manager'])
            ->givePermissionTo(['show event', 'create event', 'edit event']);

        Role::create(['name' => 'sponsor-manager'])
            ->givePermissionTo(['show sponsor', 'create sponsor', 'edit sponsor']);

        Role::create(['name' => 'poll-manager'])
            ->givePermissionTo(['show poll', 'create poll', 'edit poll']);

        Role::create(['name' => 'product-manager'])
            ->givePermissionTo(['show product', 'create product', 'edit product']);

        Role::create(['name' => 'company-admin'])
            ->givePermissionTo([
                'update permission',
                'show user', 'accept user', 'update user',
                'show company', 'edit company',
                'show actor', 'create actor', 'edit actor',
                'show sponsor', 'create sponsor', 'edit sponsor',
                'show database', 'create database', 'edit database',
                'show participant', 'create participant', 'edit participant',
                'show event', 'create event', 'edit event',
                'show poll', 'create poll', 'edit poll',
                'show product', 'create product', 'edit product',
                'pay',
                'edit permissions'
            ]);

        Role::create(['name' => 'super-admin']);

        // Venoot
        $company = Company::create([
            'rut' => '1111111-1',
            'social_reason' => 'Venoot',
            'area' => 'Metadata Eventos',
            'address' => 'Alguna Calle 1234',
            'city' => 'Santiago',
            'phone' => '56 9 555 55 55',
            'logo' => 'ljfiWEyW1jXck2GHG3RJZQ0wDPtNFz6ZNxq8gS1g.jpeg',
            'payment_type' => '0',
            'commune_id' => 12,
            'region_id' => 7,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // Super admin
        $user = User::create([
            'name' => 'Cristóbal',
            'lastname' => 'Díaz',
            'email_verified_at' => $now,
            'email' => 'cristobal@venoot.com',
            'password' => Hash::make('TicketFactory2021'),
            'created_at' => $now,
            'updated_at' => $now
        ]);
        $user->company()->associate($company);
        $user->save();
        $user->assignRole('base');
        $user->assignRole('company-admin');
        $user->assignRole('super-admin');        

        $user = User::create([
          'name' => 'Franco',
          'lastname' => 'Accorsi',
          'email_verified_at' => $now,
          'email' => 'franco@venoot.com',
          'password' => Hash::make('TicketFactory2021'),
          'created_at' => $now,
          'updated_at' => $now
        ]);

        $user->company()->associate($company);
        $user->save();
        $user->assignRole('base');
        $user->assignRole('super-admin');

        $user = User::create([
          'name' => 'Luis',
          'lastname' => 'Arce',
          'email_verified_at' => $now,
          'email' => 'luis@venoot.com',
          'password' => Hash::make('TicketFactory2021'),
          'created_at' => $now,
          'updated_at' => $now
        ]);
        $user->company()->associate($company);
        $user->save();
        $user->assignRole('base');
        $user->assignRole('super-admin');

        // Test Company
        $company = Company::create([
            'rut' => '1111111-1',
            'social_reason' => 'Adistec',
            'area' => 'Metadata Eventos',
            'address' => 'Alguna Calle 1234',
            'city' => 'Santiago',
            'phone' => '56 9 555 55 55',
            'logo' => 'ljfiWEyW1jXck2GHG3RJZQ0wDPtNFz6ZNxq8gS1g.jpeg',
            'payment_type' => '0',
            'commune_id' => 12,
            'region_id' => 7,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // Test user
        $user = User::create([
            'name' => 'Luis',
            'lastname' => 'Arce',
            'email_verified_at' => $now,
            'email' => 'mailing@adistec.com',
            'password' => Hash::make('TicketFactory2021'),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $user->company()->associate($company);
        $user->save();
        $user->assignRole('base');
        $user->assignRole('company-admin');
    }
}
