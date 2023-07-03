<?php

namespace Bvfbarten\SimpleCms\Filament\Resources\TreePageResource\RelationManagers;

use Bvfbarten\SimpleCms\Filament\Actions\HasManyRemove;
use Bvfbarten\SimpleCms\Filament\Actions\HasManySave;
use Bvfbarten\SimpleCms\Filament\Resources\TreePageResource;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ChildrenNodesRelationManager extends RelationManager
{
    protected static string $relationship = 'ChildrenNodes';

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
              Hidden::make('is_slug_changed_manually')
                ->default(false)
                ->dehydrated(false),
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
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
              HasManySave::make(),
              
                Tables\Actions\CreateAction::make(),
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                HasManyRemove::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
