<?php

namespace Bvfbarten\SimpleCms\Filament\PageTemplates;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

final class Contact
{
    public static function title()
    {
        return 'Contact';
    }
    public static function schema()
    {
        return [
            TextInput::make('name'),
            TextInput::make('email'),
            TextInput::make('phone'),
            TextInput::make('description'),
        ];
    }
    public static function savingEvent($model) {
      if (!$model->title) {
        $model->title = $model->get('name');
      }
    }
}
