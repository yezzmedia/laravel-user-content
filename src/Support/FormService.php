<?php

declare(strict_types=1);

namespace YezzMedia\Content\Support;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use YezzMedia\Content\Models\FormDefinition;

class FormService
{
    private const SUPPORTED_TYPES = ['text', 'textarea', 'email', 'tel', 'select', 'checkbox', 'radio'];

    public function getSupportedTypes(): array
    {
        return self::SUPPORTED_TYPES;
    }

    public function validate(array $input, FormDefinition $form): array
    {
        $rules = $this->buildValidationRules($form);

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    private function buildValidationRules(FormDefinition $form): array
    {
        $rules = [];

        foreach ($form->getFieldList() as $field) {
            $fieldRules = [];

            if ($field['required'] ?? false) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            $typeRules = match ($field['type'] ?? 'text') {
                'email' => ['email:filter'],
                'tel' => ['string', 'max:50'],
                'textarea' => ['string', 'max:5000'],
                'select', 'radio' => ['string', 'in:'.implode(',', $field['options'] ?? [])],
                'checkbox' => ['boolean'],
                default => ['string', 'max:1000'],
            };

            $rules[$field['key']] = array_merge($fieldRules, $typeRules);
        }

        return $rules;
    }
}
