<?php

declare(strict_types=1);

namespace YezzMedia\Content\Http\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use YezzMedia\Content\Actions\DeliverSubmissionAction;
use YezzMedia\Content\Actions\StoreSubmissionAction;
use YezzMedia\Content\Models\FormDefinition;
use YezzMedia\Content\Pipelines\SpamProtectionPipeline;

class SubmitForm extends Component
{
    public FormDefinition $form;
    public array $fieldValues = [];
    public bool $success = false;
    public ?string $error = null;
    public array $fieldErrors = [];

    public function mount(FormDefinition $form): void
    {
        $this->form = $form;
        $this->fieldValues = $this->defaultValues();
    }

    public function submit(
        StoreSubmissionAction $store,
        DeliverSubmissionAction $deliver,
        SpamProtectionPipeline $spam,
    ): void {
        $this->resetErrorBag();
        $this->fieldErrors = [];

        try {
            $validated = app(\YezzMedia\Content\Support\FormService::class)
                ->validate($this->fieldValues, $this->form);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->fieldErrors = $e->errors();

            return;
        }

        $ip = request()->ip() ?? '0.0.0.0';
        $userAgent = request()->userAgent();

        $spamResult = $spam->check($validated, $this->form, $ip);

        $submission = $store->execute(
            form: $this->form,
            data: $validated,
            ip: $ip,
            userAgent: $userAgent,
            isSpam: $spamResult->isSpam,
        );

        if (! $spamResult->isSpam && $submission->formDefinition->getNotifyEmail()) {
            $deliver->execute($submission);
        }

        $this->success = true;
        $this->fieldValues = $this->defaultValues();
    }

    public function render(): View
    {
        return view('user-content::frontend.submit-form');
    }

    private function defaultValues(): array
    {
        $defaults = [];

        foreach ($this->form->getFieldList() as $field) {
            $defaults[$field['key']] = match ($field['type'] ?? 'text') {
                'checkbox' => false,
                default => '',
            };
        }

        return $defaults;
    }
}
