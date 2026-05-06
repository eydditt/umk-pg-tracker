<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('local')) {
            $this->app->resolving('mailer', function () {
                $transport = new EsmtpTransport(
                    host: config('mail.mailers.smtp.host'),
                    port: (int) config('mail.mailers.smtp.port'),
                    tls: true
                );

                $transport->setUsername(config('mail.mailers.smtp.username'));
                $transport->setPassword(config('mail.mailers.smtp.password'));

                $transport->getStream()->disableTls();
                $transport->getStream()->setStreamOptions([
                    'ssl' => [
                        'verify_peer'       => false,
                        'verify_peer_name'  => false,
                        'allow_self_signed' => true,
                    ],
                ]);
            });
        }
    }
}