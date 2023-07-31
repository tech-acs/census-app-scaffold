<?php

namespace Uneca\Scaffold;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Laravel\Fortify\Fortify;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Livewire\Livewire;

use Uneca\Scaffold\Services\ConnectionLoader;

class ScaffoldServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $migrations = [
            'install_postgis_extension',
            'install_ltree_extension',
            'create_area_restrictions_table',
            'create_invitations_table',
            'create_usage_stats_table',
            'create_areas_table',
            'create_sources_table',
            'add_is_suspended_column_to_users_table',
            'create_notifications_table',
            'create_announcements_table',
            'create_area_hierarchies_table',
            'create_analytics_table',
            'add_case_stats_component_column_to_questionnaires_table',
        ];
        $package
            ->name('scaffold')
            ->hasViews()
            ->hasConfigFile(['scaffold', 'languages', 'filesystems'])
            ->hasTranslations()
            ->hasRoute('web')
            ->hasMigrations($migrations)
            ->hasCommands([
                \Uneca\Scaffold\Commands\Install::class,
                \Uneca\Scaffold\Commands\DataExport::class,
                \Uneca\Scaffold\Commands\DataImport::class,
                \Uneca\Scaffold\Commands\Dockerize::class,
                \Uneca\Scaffold\Commands\Adminify::class,
                \Uneca\Scaffold\Commands\Update::class,
                \Uneca\Scaffold\Commands\Production::class,
            ]);
    }

    public function packageRegistered()
    {
        Livewire::component('area-filter', \Uneca\Scaffold\Http\Livewire\AreaFilter::class);
        Livewire::component('area-restriction-manager', \Uneca\Scaffold\Http\Livewire\AreaRestrictionManager::class);
        Livewire::component('area-spreadsheet-importer', \Uneca\Scaffold\Http\Livewire\AreaSpreadsheetImporter::class);
        Livewire::component('bulk-inviter', \Uneca\Scaffold\Http\Livewire\BulkInviter::class);
        //Livewire::component('command-palette', \Uneca\Scaffold\Http\Livewire\CommandPalette::class);
        Livewire::component('invitation-manager', \Uneca\Scaffold\Http\Livewire\InvitationManager::class);
        Livewire::component('language-switcher', \Uneca\Scaffold\Http\Livewire\LanguageSwitcher::class);
        Livewire::component('notification-bell', \Uneca\Scaffold\Http\Livewire\NotificationBell::class);
        Livewire::component('notification-dropdown', \Uneca\Scaffold\Http\Livewire\NotificationDropdown::class);
        Livewire::component('notification-inbox', \Uneca\Scaffold\Http\Livewire\NotificationInbox::class);
        Livewire::component('role-manager', \Uneca\Scaffold\Http\Livewire\RoleManager::class);
    }

    public function boot()
    {
        parent::boot();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        (new ConnectionLoader())();

        Blade::if('connectible', function ($value) {
            try {
                DB::connection($value)->getPdo();
                return true;
            } catch (\Exception $exception) {
                return false;
            }
        });

        Collection::macro('joinWithExternalColumn', function (array $keyValue, string $using, string $newColumnName) {
            return empty($keyValue) ?
                $this :
                $this->map(function ($item) use ($keyValue, $using, $newColumnName) {
                    if (property_exists($item, $using)) {
                        $item->$newColumnName = $keyValue[$item->$using] ?? null;
                    }
                    return $item;
                });
        });

        Fortify::registerView(function (Request $request) {
            if (! $request->hasValidSignature()) {
                throw new InvalidSignatureException();
            }
            return view('auth.register')
                ->with(['encryptedEmail' => Crypt::encryptString($request->email)]);
        });

        $router = $this->app->make(Router::class);
        $router->pushMiddlewareToGroup('web', \Uneca\Scaffold\Http\Middleware\CheckAccountSuspension::class);
        $router->pushMiddlewareToGroup('web', \Uneca\Scaffold\Http\Middleware\Language::class);
        $router->aliasMiddleware('enforce_2fa', \Uneca\Scaffold\Http\Middleware\RedirectIf2FAEnforced::class);
        $router->aliasMiddleware('log_page_views', \Uneca\Scaffold\Http\Middleware\LogPageView::class);

    }

    /*public function register()
    {
        parent::register();
    }*/
}
