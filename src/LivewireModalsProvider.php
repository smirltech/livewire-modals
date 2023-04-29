<?php

namespace SmirlTech\LivewireModals;

use Livewire\Livewire;
use SmirlTech\LaravelMedia\Livewire\AvatarComponent;
use SmirlTech\LaravelMedia\Livewire\GalleryComponent;
use SmirlTech\LivewireModals\Components\Modals;
use SmirlTech\LivewireModals\Livewire\DeleteModel;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LivewireModalsProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('modals')
            ->hasViews();
    }

    public function packageBooted(): void
    {
        Livewire::component('modals', Modals::class);
        Livewire::component('modals::delete-model', DeleteModel::class);
    }
}
