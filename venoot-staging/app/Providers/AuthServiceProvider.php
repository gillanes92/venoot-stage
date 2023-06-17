<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        Gate::define('only-super-admin', function($user) {
          return false;
        });

        Gate::define('update-user', function ($user, $userToUpdate) {
            if (
                $user->id == $userToUpdate->id || ($user->company_id == $userToUpdate->company_id && $user->hasPermissionTo('update user'))
            ) {
                return true;
            }

            return false;
        });

        Gate::define('show-user', function ($user, $userToShow) {
            if (
                $user->id == $userToShow->id || ($user->company_id == $userToShow->company_id && $user->hasPermissionTo('show user'))
            ) {
                return true;
            }

            return false;
        });

        Gate::define('index-user', function ($user) {
            if ($user->hasPermissionTo('show user')) {
                return true;
            }

            return false;
        });

        Gate::define('accept-user', function ($user, $userToAccept) {
            if ($user->company->companyRequests->pluck('user')->contains($userToAccept) && $user->hasPermissionTo('accept user')) {
                return true;
            }

            return false;
        });

        Gate::define('store-company', function ($user) {
            if ($user->company_id == null) {
                return true;
            }

            return false;
        });

        Gate::define('update-company', function ($user, $company) {
            if ($user->company_id == $company->id) {
                return true;
            }

            return false;
        });

        Gate::define('show-company', function ($user, $company) {
            if ($user->company_id == $company->id) {
                return true;
            }

            return false;
        });

        Gate::define('index-company', function ($user, $company) {
            if ($user->company_id == $company->id) {
                return true;
            }

            return false;
        });

        Gate::define('index-actor', function ($user) {
            if ($user->hasPermissionTo('show actor')) {
                return true;
            }

            return false;
        });

        Gate::define('show-actor', function ($user, $actor) {
            if ($user->company_id == $actor->company_id && $user->hasPermissionTo('show actor')) {
                return true;
            }

            return false;
        });

        Gate::define('edit-actor', function ($user, $actor) {
            if (($user->company_id == $actor->company_id || !isset($actor->id)) && $user->hasPermissionTo('edit actor')) {
                return true;
            }

            return false;
        });

        Gate::define('show-event', function ($user, $event) {
            if ($user->company_id == $event->company_id && $user->hasPermissionTo('show event')) {
                return true;
            }

            return false;
        });

        Gate::define('edit-event', function ($user, $event) {
            if (($user->company_id == $event->company_id || !isset($event->id)) && $user->hasPermissionTo('edit event')) {
                return true;
            }

            return false;
        });

        Gate::define('show-database', function ($user, $database) {
            if ($user->company_id == $database->company_id && $user->hasPermissionTo('show database')) {
                return true;
            }

            return false;
        });

        Gate::define('edit-database', function ($user, $database) {
            if (($user->company_id == $database->company_id || !isset($database->id)) && $user->hasPermissionTo('edit database')) {
                return true;
            }

            return false;
        });

        Gate::define('show-participant', function ($user, $participant) {
            if ($user->company_id == $participant->database->company_id && $user->hasPermissionTo('show participant')) {
                return true;
            }

            return false;
        });

        Gate::define('edit-participant', function ($user, $participant) {
            if ((!isset($participant->id) || $user->company_id == $participant->database->company_id) && $user->hasPermissionTo('edit participant')) {
                return true;
            }

            return false;
        });

        Gate::define('show-sponsor', function ($user, $sponsor) {
            if ($user->company_id == $sponsor->company_id && $user->hasPermissionTo('show sponsor')) {
                return true;
            }

            return false;
        });

        Gate::define('edit-sponsor', function ($user, $sponsor) {
            if (($user->company_id == $sponsor->company_id || !isset($sponsor->id)) && $user->hasPermissionTo('edit sponsor')) {
                return true;
            }

            return false;
        });

        Gate::define('show-poll', function ($user, $poll) {
            if ($user->company_id == $poll->company_id && $user->hasPermissionTo('show poll')) {
                return true;
            }

            return false;
        });

        Gate::define('edit-poll', function ($user, $poll) {
            if (($user->company_id == $poll->company_id || !isset($poll->id)) && $user->hasPermissionTo('edit poll')) {
                return true;
            }

            return false;
        });

        Gate::define('show-product', function ($user, $product) {
            if ($user->company_id == $product->company_id && $user->hasPermissionTo('show product')) {
                return true;
            }

            return false;
        });

        Gate::define('edit-product', function ($user, $product) {
            if (($user->company_id == $product->company_id || !isset($product->id)) && $user->hasPermissionTo('edit product')) {
                return true;
            }

            return false;
        });

        Gate::define('edit-permissions', function ($user, $target) {
            if ($user->company_id == $target->company_id && $user->hasPermissionTo('edit permissions')) {
                return true;
            }

            return false;
        });

        Gate::define('delete-jobs', function ($user, $job) {
            if ($user->company_id == $job->event->company->id) {
                return true;
            }

            return false;
        });
    }
}
