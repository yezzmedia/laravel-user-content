<?php

declare(strict_types=1);

namespace YezzMedia\Content\Actions;

use Illuminate\Support\Facades\Mail;
use YezzMedia\Content\Models\FormSubmission;

final class DeliverSubmissionAction
{
    public function execute(FormSubmission $submission): void
    {
        $form = $submission->formDefinition;

        if ($form === null) {
            return;
        }

        $email = $form->getNotifyEmail();

        if ($email === null) {
            return;
        }

        Mail::raw($this->buildBody($submission), function ($message) use ($email, $submission) {
            $message->to($email)
                ->subject('Form Submission: '.($submission->formDefinition->name ?? 'Unknown Form'))
                ->replyTo($submission->data['email'] ?? $email);
        });
    }

    private function buildBody(FormSubmission $submission): string
    {
        $lines = ['New form submission received:', ''];

        foreach (($submission->data ?? []) as $key => $value) {
            $label = ucfirst(str_replace('_', ' ', $key));
            $lines[] = "{$label}: {$value}";
        }

        $lines[] = '';
        $lines[] = 'IP: '.($submission->ip ?? 'Unknown');

        return implode("\n", $lines);
    }
}
