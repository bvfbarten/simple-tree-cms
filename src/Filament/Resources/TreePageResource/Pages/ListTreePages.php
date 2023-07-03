<?php

namespace Bvfbarten\SimpleCms\Filament\Resources\TreePageResource\Pages;

use Bvfbarten\SimpleCms\Filament\Resources\TreePageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTreePages extends ListRecords
{
    protected static string $resource = TreePageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            //ACtions\Action::make("Tree List")->url('tree-pages/tree-list')
        ];
    }
}
