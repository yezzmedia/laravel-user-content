<?php

declare(strict_types=1);

namespace YezzMedia\Content\Pipelines;

use Illuminate\Cache\RateLimiter;
use YezzMedia\Content\Models\FormDefinition;

class SpamProtectionPipeline
{
    public function __construct(private readonly RateLimiter $rateLimiter) {}

    public function check(array $input, FormDefinition $form, string $ip): SpamCheckResult
    {
        if ($this->honeypotFailed($input, $form)) {
            return SpamCheckResult::failed('honeypot');
        }

        if ($this->rateLimitExceeded($form, $ip)) {
            return SpamCheckResult::failed('rate_limit');
        }

        return SpamCheckResult::passed();
    }

    private function honeypotFailed(array $input, FormDefinition $form): bool
    {
        if (! $form->isHoneypotEnabled()) {
            return false;
        }

        $honeypotField = $this->getHoneypotFieldName($form);

        return ! empty($input[$honeypotField]);
    }

    private function rateLimitExceeded(FormDefinition $form, string $ip): bool
    {
        $maxAttempts = $form->getRateLimit();
        $decaySeconds = $form->getRateLimitPeriod();

        $key = 'content-form:'.$form->id.'|'.$ip;

        if ($this->rateLimiter->tooManyAttempts($key, $maxAttempts)) {
            return true;
        }

        $this->rateLimiter->hit($key, $decaySeconds);

        return false;
    }

    private function getHoneypotFieldName(FormDefinition $form): string
    {
        return 'hp_'.md5($form->id.'_'.($form->name ?? ''));
    }
}
