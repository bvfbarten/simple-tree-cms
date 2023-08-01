<?php

namespace Bvfbarten\SimpleCms\Filament\Resources;

use Bvfbarten\SimpleCms\Filament\Resources\TreePageResource\Pages;
use Bvfbarten\SimpleCms\Filament\Resources\TreePageResource\RelationManagers;
use Bvfbarten\SimpleCms\Filament\Resources\TreePageResource\RelationManagers\ChildrenNodesRelationManager;
use Bvfbarten\SimpleCms\Models\TreePage;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Support\Components\Component;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use SplFileInfo;

class TreePageResource extends Resource
{
  protected static ?string $model = TreePage::class;

  protected static ?string $navigationIcon = 'heroicon-o-document-text';

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
          ->required() ,
        TextInput::make('slug')
          ->extraInputAttributes(['readonly'=> true]),
        Hidden::make('is_slug_changed_manually')
          ->default(false)
          ->dehydrated(false)
          ->afterStateUpdated(function (Closure $set) {
            $set('is_slug_changed_manually', true);
          }) ,
          Hidden::make('is_slug_changed_manually')
            ->default(false)
            ->dehydrated(false),
          Checkbox::make('is_home')
            ->reactive(), 
          Select::make('parent_id', 'Parent Node')
            ->default('Is Root', '')
            ->relationship('ParentNode', 'title')
            ->required()
            ->visible(fn ($get) => $get('is_home') === false)
          ,
          Forms\Components\Select::make('template')
            ->reactive()
            ->options(static::getTemplates()),

          ...static::getTemplateSchemas($form),
      ]);
  }

  public static function getTemplates(): Collection
  {
    return static::getTemplateClasses()->mapWithKeys(fn ($class) => [$class => $class::title()]);
  }

  public static function getTemplateClasses(): Collection
  {
    $filesystem = app(Filesystem::class);
    if (!is_dir(app_path('Filament/PageTemplates'))) {
      mkdir(app_path('Filament/PageTemplates'), 0775, 1);
      file_put_contents(
        app_path('Filament/PageTemplates') . '/Home.php',
        file_get_contents(__DIR__ . '/../PageTemplates/Home.php')
      );
    }
    return collect($filesystem->allFiles(app_path('Filament/PageTemplates')))
      ->map(function (SplFileInfo $file): string {
        return (string) Str::of('App\\Filament\\PageTemplates')
          ->append('\\', $file->getRelativePathname())
          ->replace(['/', '.php'], ['\\', '']);
      });
  }

  public static function getTemplateSchemas($form): array
  {
    return static::getTemplateClasses()
      ->map(fn ($class) =>
      Forms\Components\Group::make($class::schema())
        ->columnSpan(2)
        ->afterStateHydrated(fn ($component, $state) => $component->getChildComponentContainer()->fill($state))
        ->statePath('content')
        ->visible(fn ($get) => $get('template') === $class)
      )
      ->toArray();
  }

  public static function getTemplateName($class)
  {
    return Str::of($class)->afterLast('\\')->snake()->toString();
  }

  public static function table(Table $table): Table
  {

    return $table
      ->columns([
        TextColumn::make('title'),
        TextColumn::make('fullPath')->sortable(),
        TextColumn::make('template')->sortable(),
        TextColumn::make('ParentNode.fullPath'),
        TextColumn::make('order'),
      ])
      ->filters([
        SelectFilter::make('template')
          ->options(function(){
            $treePages = TreePage
              ::groupBy('template')
              ->get()
              ->keyBy('id', 'template');
            $rtn = [];
            foreach($treePages as $counter => $treePage) {
              $rtn[$counter] = $treePage->title;
            }
            return $rtn;
          })
          ->query(function (Builder $query, array $data): Builder {
            return $query;
          }),
        Filter::make('parent_id')
          ->form([
            Forms\Components\Select::make('ParentNode')
              ->options(function(){
                $treePages = TreePage::where('parent_id', '!=', -1)
                  ->get();
                $rtn = [];
                foreach($treePages as $treePage) {
                  $rtn[$treePage->parent_id] = $treePage->ParentNode->title;
                }
                return $rtn;
              }),
          ])
          ->query(function (Builder $query, array $data): Builder {
            return $query
              ->when(
                $data['ParentNode'],
                fn (Builder $query, $parent_id): Builder => $query->where('parent_id', '=', $parent_id)->orWhere(
                  function($query) use ($parent_id) {
                    $query->where('id', $parent_id);
                  }),
              );
          })
          //
      ])
      ->actions([
        Tables\Actions\EditAction::make()
      ])
      ->bulkActions([
        Tables\Actions\DeleteBulkAction::make(),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      ChildrenNodesRelationManager::class,
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListTreePages::route('/'),
      'create' => Pages\CreateTreePage::route('/create'),
      'edit' => Pages\EditTreePage::route('/{record}/edit'),
      //'tree-list' => Pages\TreePage::route('/tree-list'),
    ];
  }    
}
