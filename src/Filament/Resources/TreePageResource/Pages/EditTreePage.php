<?php

namespace Bvfbarten\SimpleCms\Filament\Resources\TreePageResource\Pages;

use Bvfbarten\SimpleCms\Filament\Resources\TreePageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Str;

class EditTreePage extends EditRecord
{
    protected static string $resource = TreePageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public static function getTemplateName($class): string
    {
        return Str::of($class)->afterLast('\\')->snake()->toString();
    }
}
