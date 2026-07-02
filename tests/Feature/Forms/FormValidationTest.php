<?php

declare(strict_types=1);

use YezzMedia\Content\Models\FormDefinition;
use YezzMedia\Content\Support\FormService;
use YezzMedia\UserProjects\Models\Project;

it('validates required fields', function () {
    $project = Project::factory()->create();
    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Test',
        'fields' => [
            ['key' => 'name', 'type' => 'text', 'label' => 'Name', 'required' => true],
            ['key' => 'email', 'type' => 'email', 'label' => 'Email', 'required' => true],
        ],
    ]);

    app(FormService::class)->validate([
        'name' => 'John',
        'email' => 'john@example.com',
    ], $form);
})->throwsNoExceptions();

it('fails validation when required field missing', function () {
    $project = Project::factory()->create();
    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Test',
        'fields' => [
            ['key' => 'name', 'type' => 'text', 'label' => 'Name', 'required' => true],
        ],
    ]);

    app(FormService::class)->validate(['name' => ''], $form);
})->throws(\Illuminate\Validation\ValidationException::class);

it('validates email fields', function () {
    $project = Project::factory()->create();
    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Test',
        'fields' => [
            ['key' => 'email', 'type' => 'email', 'label' => 'Email', 'required' => true],
        ],
    ]);

    app(FormService::class)->validate(['email' => 'not-an-email'], $form);
})->throws(\Illuminate\Validation\ValidationException::class);

it('validates select fields against options', function () {
    $project = Project::factory()->create();
    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Test',
        'fields' => [
            ['key' => 'dept', 'type' => 'select', 'label' => 'Dept', 'required' => true, 'options' => ['Sales', 'Support']],
        ],
    ]);

    app(FormService::class)->validate(['dept' => 'Invalid'], $form);
})->throws(\Illuminate\Validation\ValidationException::class);
