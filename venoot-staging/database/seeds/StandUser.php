<?php

use App\User;
use App\Company;

use Illuminate\Database\Seeder;

class StandUser extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $now = \Carbon\Carbon::now();
    $company = Company::find(1);

    $user = User::create([
      'name' => 'Roberto',
      'lastname' => 'Veliz',
      'email_verified_at' => $now,
      'email' => 'roberto@sib.cl',
      'password' => Hash::make('StandsPassword2021'),
      'created_at' => $now,
      'updated_at' => $now
    ]);

    $user->company()->associate($company);
    $user->save();
    $user->assignRole('base');
    $user->assignRole('company-admin');
  }
}
