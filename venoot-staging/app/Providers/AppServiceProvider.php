<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;

use App\ScheduledDelivery;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    if ($this->app->environment('staging') || $this->app->environment('production')) {
      URL::forceScheme('https');
    }

    Mail::macro('selectConfig', function (string $mail) {

      if (config('app.env') != 'local') {
        if (DB::table('verified_mails')->where('email', strtolower($mail))->exists()) {
          $domain = config('multimail.domain_verified');
          $key = config('multimail.secret_verified');
        } else {
          $domain = config('multimail.domain_unverified');
          $key = config('multimail.secret_unverified');
        }

        $transport = $this->getSwiftMailer()->getTransport();
        $transport->setDomain($domain);
        $transport->setKey($key);
      }

      return $this->to(strtolower($mail));
    });


    Queue::after(function (JobProcessed $event) {
      $id = $event->job->getJobId();
      $s = ScheduledDelivery::whereJsonContains('jobs', $id)->first();
      if ($s) {
        if (($index = array_search($id, $s->jobs)) !== false) {
          $jobs = $s->jobs;
          array_splice($jobs, $index, 1);
          $s->jobs = $jobs;
          $s->save();
        }
      }
    });
  }
}
