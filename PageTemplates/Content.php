<?php

namespace App\Filament\PageTemplates;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

final class Content
{
    public static function title()
    {
        return 'Content';
    }
    public static function retreivedEvent($data) {
      //dd($data);
    }
    public static function schema()
    {
        return [
          Repeater::make('sections')
            ->schema([
              TextInput::make('title'),
              FileUpload::make('image')
                ->storeFileNamesIn('image_name')
                ->image()
                ->directory('images/'),
            RichEditor::make('content')
            ])
        ];
    }
}
