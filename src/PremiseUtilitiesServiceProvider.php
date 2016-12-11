<?php

declare(strict_types=1);

namespace Premise\Utilities;

use App;
use Illuminate\Support\ServiceProvider;

/**
 * Class PremiseUtilitiesServiceProvider.
 */
class PremiseUtilitiesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        App::make(PremiseUtilities::class);
    }
}
