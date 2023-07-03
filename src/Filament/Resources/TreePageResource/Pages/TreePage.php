<?php

namespace Bvfbarten\SimpleCms\Filament\Resources\TreePageResource\Pages;

use Bvfbarten\SimpleCms\Filament\Resources\TreePageResource;
use Filament\Pages\Actions as FilamentActions;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use SolutionForest\FilamentTree\Actions;
use SolutionForest\FilamentTree\Concern;
use SolutionForest\FilamentTree\Resources\Pages\TreePage as BasePage;
use SolutionForest\FilamentTree\Support\Utils;


class TreePage extends BasePage
{
  protected static string $resource = TreePageResource::class;

  protected static int $maxDepth = 2;

  protected function getActions(): array
  {
    return [

      CreateAction::make(),
      //$this->getCreateAction(),
      FilamentActions\Action::make("List Mode")->url('../tree-pages'),
      // SAMPLE CODE, CAN DELETE
      //\Filament\Pages\Actions\Action::make('sampleAction'),
    ];
  }
  protected function getTreeActions(): array
  {
    return [
      Actions\DeleteAction::make(),
      Actions\EditAction::make(),
    ];
  }   


  protected function getHeaderWidgets(): array
  {
    return [];
  }

  protected function getFooterWidgets(): array
  {
    return [];
  }

  // CUSTOMIZE ICON OF EACH RECORD, CAN DELETE
  // public function getTreeRecordIcon(?\Illuminate\Database\Eloquent\Model $record = null): ?string
  // {
  //     return null;
  // }
}
