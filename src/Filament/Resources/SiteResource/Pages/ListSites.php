<?php

namespace Bvfbarten\SimpleCms\Filament\Resources\SiteResource\Pages;

use Bvfbarten\SimpleCms\Filament\Resources\SiteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSites extends ListRecords
{
    protected static string $resource = SiteResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
