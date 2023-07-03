<?php

namespace App\Filament\PageTemplates;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

final class Home 
{
    public static function title()
    {
        return 'Home';
    }

    public static function schema()
    {
        return [
          RichEditor::make('content')
        ];
    }
}
