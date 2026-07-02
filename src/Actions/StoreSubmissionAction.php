<?php

declare(strict_types=1);

namespace YezzMedia\Content\Actions;

use YezzMedia\Content\Models\FormDefinition;
use YezzMedia\Content\Models\FormSubmission;

final class StoreSubmissionAction
{
    public function execute(FormDefinition $form, array $data, string $ip, ?string $userAgent, bool $isSpam = false): FormSubmission
    {
        return FormSubmission::create([
            'form_definition_id' => $form->id,
            'data' => $data,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'is_spam' => $isSpam,
        ]);
    }
}
