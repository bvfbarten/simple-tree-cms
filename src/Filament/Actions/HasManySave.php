<?php

namespace Bvfbarten\SimpleCms\Filament\Actions;

use Filament\Tables\Actions\AssociateAction;
use Filament\Forms\ComponentContainer;

class HasManySave extends AssociateAction
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-support::actions/associate.single.label'));

        $this->modalHeading(fn (): string => __('filament-support::actions/associate.single.modal.heading', ['label' => $this->getModelLabel()]));

        $this->modalButton(__('filament-support::actions/associate.single.modal.actions.associate.label'));

        $this->modalWidth('lg');

        $this->extraModalActions(function (): array {
            return $this->isAssociateAnotherDisabled ? [] : [
                $this->makeExtraModalAction('associateAnother', ['another' => true])
                    ->label(__('filament-support::actions/associate.single.modal.actions.associate_another.label')),
            ];
        });

        $this->successNotificationTitle(__('filament-support::actions/associate.single.messages.associated'));

        $this->color('secondary');

        $this->button();

        $this->form(fn (): array => [$this->getRecordSelect()]);

        $this->action(function (array $arguments, ComponentContainer $form): void {
            $this->process(function (array $data) {
              //dd($data, $this->getModel());
              //$related = "{$this->getModel()}"::find($data['recordId']);
                /** @var HasMany | MorphMany $relationship */
                $relationship = $this->getRelationship();

                $record = $relationship->getRelated()->query()->find($data['recordId']);

                /** @var BelongsTo $inverseRelationship */
                $inverseRelationship = $this->getInverseRelationshipFor($record);
                $relationship->getParent()->childrenNodes()->save($record);
            });

            if ($arguments['another'] ?? false) {
                $this->callAfter();
                $this->sendSuccessNotification();

                $form->fill();

                $this->halt();

                return;
            }

            $this->success();
        });
    }
}


