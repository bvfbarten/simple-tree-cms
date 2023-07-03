<?php

namespace Bvfbarten\SimpleCms\Filament\Resources\SiteResource\Pages;

use Bvfbarten\SimpleCms\Filament\Resources\SiteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSite extends EditRecord
{
    protected static string $resource = SiteResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
