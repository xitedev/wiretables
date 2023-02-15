<?php

namespace Xite\Wiretables;

use Livewire\Livewire;
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
            ->hasViews('wiretables');
    }

    public function packageBooted(): void
    {
        Livewire::component(DeleteModal::getName(), DeleteModal::class);
        Livewire::component(RestoreModal::getName(), RestoreModal::class);
    }
}
