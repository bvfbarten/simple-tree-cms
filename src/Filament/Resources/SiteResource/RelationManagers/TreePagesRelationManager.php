<?php

namespace Bvfbarten\SimpleCms\Filament\Resources\SiteResource\RelationManagers;

use Bvfbarten\SimpleCms\Filament\Resources\TreePageResource;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TreePagesRelationManager extends RelationManager
{
    protected static string $relationship = 'TreePages';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              TextInput::make('title')
                ->afterStateUpdated(function (Closure $get, Closure $set, ?string $state) {
                  if (! $get('is_slug_changed_manually') && filled($state)) {
                    $set('slug', "/" . Str::slug($state));
                  }
                })
                ->reactive()
                ->required(),
              TextInput::make('slug')
                ->extraInputAttributes(['readonly'=> true])
                ->afterStateUpdated(function (Closure $set) {
                  $set('is_slug_changed_manually', true);
                }) ,
                Checkbox::make('is_home')
                  ->reactive(), 
              Hidden::make('is_slug_changed_manually')
                ->default(false)
                ->dehydrated(false),
              Select::make('parent_id', 'Parent Node')
                ->default('Is Root', '')
                ->relationship('ParentNode', 'title')
                ->visible(fn ($get) => $get('is_home') === false)
                ->required(),
              Forms\Components\Select::make('template')
                ->reactive()
                ->options(TreePageResource::getTemplates()),

              ...TreePageResource::getTemplateSchemas($form),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('fullPath')->sortable(),
                TextColumn::make('updated_at')->dateTime('d.m.Y, H:i')->sortable(),
                TextColumn::make('order'),
                TextColumn::make('parent_id'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
