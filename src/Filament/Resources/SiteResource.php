<?php

namespace Bvfbarten\SimpleCms\Filament\Resources;

use Bvfbarten\SimpleCms\Filament\Resources\SiteResource\Pages;
use Bvfbarten\SimpleCms\Filament\Resources\SiteResource\RelationManagers;
use Bvfbarten\SimpleCms\Filament\Resources\SiteResource\RelationManagers\TreePagesRelationManager;
use Bvfbarten\SimpleCms\Models\Site;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
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
              TextInput::make('title')
                ->columnSpanFull(),                //
              Repeater::make('Domain')
                ->columnSpanFull()
                ->schema([
                  TextInput::make('value'),
                  Hidden::make('is_primary')
                  ->default(true)
                ])
                ->minItems(1)
                ->maxItems(1)
                ->relationship(),                //
              Repeater::make('AlternativeDomains')
                ->columnSpanFull()
                ->schema([
                  TextInput::make('value')
                    ->placeholder('Enter domain'),
                  Hidden::make('is_primary')
                  ->default(false)
                ])
                ->relationship()
                ->label('Alternative domain')
                ->helperText('Add alternative domain for the resource.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('title'),
              TextColumn::make('Domain.value')
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
