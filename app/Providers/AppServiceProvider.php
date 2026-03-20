<?php

namespace App\Providers;

use Livewire\Livewire;
use App\Core\KTBootstrap;
use App\Models\Category;
use App\Models\ServingCity;
use App\Models\State;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        // Update defaultStringLength
        Builder::defaultStringLength(191);

        Paginator::useBootstrapFive();

        KTBootstrap::init();

        if (app()->environment('production')) {
            Livewire::setUpdateRoute(function ($handle) {
                return Route::post('/starterkit/metronic/laravel/livewire/update', $handle);
            });
        }

        // Share site content with frontend views
        View::composer('frontend.*', function ($view) {
            $heroContent = \App\Models\SiteContent::getSection('hero');
            $infoContent = \App\Models\SiteContent::getSection('info');
            $navbarContent = \App\Models\SiteContent::getSection('navbar');
            $privacyPolicy = \App\Models\SiteContent::getSection('privacy_policy');
            $termsConditions = \App\Models\SiteContent::getSection('terms_conditions');
            
            // Get footer content (merged section)
            $footerAbout = \App\Models\SiteContent::getSection('footer');
            $footerContact = \App\Models\SiteContent::getSection('footer');
            
            $view->with(compact('heroContent', 'infoContent', 'navbarContent', 'privacyPolicy', 'termsConditions', 'footerAbout', 'footerContact'));
        });
    }
}
