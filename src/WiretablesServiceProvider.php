<?php

namespace Xite\Wiretables;

use Livewire\Livewire;
use Xite\Wiretables\Commands\TableMakeCommand;
use Xite\Wiretables\Modals\DeleteModal;
use Xite\Wiretables\Modals\RestoreModal;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WiretablesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('wiretables')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasCommand(TableMakeCommand::class)
            ->hasViews();
    }

    public function packageBooted(): void
    {
        Livewire::component(DeleteModal::class);
        Livewire::component(RestoreModal::class);
    }
}
