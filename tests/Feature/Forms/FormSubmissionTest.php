<?php

declare(strict_types=1);

use YezzMedia\Content\Models\FormDefinition;
use YezzMedia\Content\Models\FormSubmission;
use YezzMedia\UserProjects\Models\Project;

it('stores a submission', function () {
    $project = $this->createProject();
    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Contact',
        'fields' => [],
    ]);

    $submission = FormSubmission::create([
        'form_definition_id' => $form->id,
        'data' => ['name' => 'John', 'email' => 'john@example.com'],
        'ip' => '127.0.0.1',
        'is_spam' => false,
    ]);

    expect($submission->data['name'])->toBe('John')
        ->and($submission->data['email'])->toBe('john@example.com')
        ->and($submission->ip)->toBe('127.0.0.1')
        ->and($submission->isSpam())->toBeFalse();
});

it('marks submission as spam', function () {
    $project = $this->createProject();
    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Contact',
        'fields' => [],
    ]);

    $submission = FormSubmission::create([
        'form_definition_id' => $form->id,
        'data' => [],
        'is_spam' => true,
    ]);

    expect($submission->isSpam())->toBeTrue();
});

it('belongs to form definition', function () {
    $project = $this->createProject();
    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Contact',
        'fields' => [],
    ]);

    $submission = FormSubmission::create([
        'form_definition_id' => $form->id,
        'data' => [],
    ]);

    expect($submission->formDefinition->id)->toBe($form->id);
});
