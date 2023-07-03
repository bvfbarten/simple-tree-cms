<?php

namespace Bvfbarten\SimpleCms\Filament\PageTemplates;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

final class Faq
{
    public static function title()
    {
        return 'FAQ';
    }

    public static function schema()
    {
        return [
            TextInput::make('faq_title'),
            Repeater::make('faq')->label('FAQ')->schema([
                    TextInput::make( 'title'),
                    RichEditor::make('content')
                ])
        ];
    }
}
