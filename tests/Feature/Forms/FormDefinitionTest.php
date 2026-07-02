<?php

declare(strict_types=1);

use YezzMedia\Content\Models\FormDefinition;
use YezzMedia\UserProjects\Models\Project;

it('creates a form definition', function () {
    $project = $this->createProject();

    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Contact Form',
        'fields' => [
            ['key' => 'name', 'type' => 'text', 'label' => 'Name', 'required' => true],
            ['key' => 'email', 'type' => 'email', 'label' => 'Email', 'required' => true],
        ],
        'options' => [
            'honeypot' => true,
            'rate_limit' => 5,
        ],
    ]);

    expect($form->name)->toBe('Contact Form')
        ->and($form->getFieldList())->toHaveCount(2)
        ->and($form->isHoneypotEnabled())->toBeTrue()
        ->and($form->getRateLimit())->toBe(5);
});

it('returns field list', function () {
    $project = $this->createProject();

    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Test Form',
        'fields' => [
            ['key' => 'name', 'type' => 'text', 'label' => 'Name', 'required' => true],
        ],
    ]);

    expect($form->getFieldList())->toBeArray()
        ->and($form->getFieldList()[0]['key'])->toBe('name');
});

it('returns default option values', function () {
    $project = $this->createProject();

    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Test',
        'fields' => [],
    ]);

    expect($form->getRateLimit())->toBe(5)
        ->and($form->getRateLimitPeriod())->toBe(60)
        ->and($form->getNotifyEmail())->toBeNull();
});
