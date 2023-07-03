<?php

namespace Bvfbarten\SimpleCms\Filament\Actions;

use Filament\Forms\ComponentContainer;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\DissociateAction;

class HasManyRemove extends DissociateAction
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-support::actions/dissociate.single.label'));

        $this->modalHeading(fn (): string => __('filament-support::actions/dissociate.single.modal.heading', ['label' => $this->getRecordTitle()]));

        $this->modalButton(__('filament-support::actions/dissociate.single.modal.actions.dissociate.label'));

        $this->successNotificationTitle(__('filament-support::actions/dissociate.single.messages.dissociated'));

        $this->color('danger');

        $this->icon('heroicon-s-x');

        $this->requiresConfirmation();

        $this->action(function (): void {
            $this->process(function (Model $record): void {

                $relationship = $this->getRelationship();
                

                $record->{$relationship->getForeignKeyName()} = null;
                $record->save();
            });

            $this->success();
        });
    }
}



