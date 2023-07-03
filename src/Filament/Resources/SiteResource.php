<?php

namespace Bvfbarten\SimpleCms\Filament\Resources;

use Bvfbarten\SimpleCms\Filament\Resources\SiteResource\Pages;
use Bvfbarten\SimpleCms\Filament\Resources\SiteResource\RelationManagers;
use Bvfbarten\SimpleCms\Filament\Resources\SiteResource\RelationManagers\TreePagesRelationManager;
use Bvfbarten\SimpleCms\Models\Site;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title'),                //
                TextInput::make('domain'),                //
                Repeater::make('alternative_domains')
            ->schema([
                TextInput::make('domain')
                    ->placeholder('Enter domain')
                    ->required()
            ])
            ->label('Alternative domain')
            ->helperText('Add alternative domain for the resource.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('title'),
              TextColumn::make('domain')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            TreePagesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }    
}
