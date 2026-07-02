<?php

declare(strict_types=1);

use YezzMedia\Content\Models\FormDefinition;
use YezzMedia\Content\Pipelines\SpamProtectionPipeline;
use YezzMedia\UserProjects\Models\Project;

it('passes clean submissions', function () {
    $project = Project::factory()->create();
    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Test',
        'fields' => [],
        'options' => ['honeypot' => true, 'rate_limit' => 100],
    ]);

    $result = app(SpamProtectionPipeline::class)->check([], $form, '10.0.0.1');

    expect($result->isSpam)->toBeFalse();
});

it('detects honeypot filled', function () {
    $project = Project::factory()->create();
    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Test',
        'fields' => [],
        'options' => ['honeypot' => true],
    ]);

    $hpField = 'hp_'.md5($form->id.'_Test');

    $result = app(SpamProtectionPipeline::class)->check([$hpField => 'bot'], $form, '10.0.0.1');

    expect($result->isSpam)->toBeTrue()
        ->and($result->reason)->toBe('honeypot');
});

it('skips honeypot when disabled', function () {
    $project = Project::factory()->create();
    $form = FormDefinition::create([
        'project_id' => $project->id,
        'name' => 'Test',
        'fields' => [],
        'options' => ['honeypot' => false],
    ]);

    $result = app(SpamProtectionPipeline::class)->check([], $form, '10.0.0.1');

    expect($result->isSpam)->toBeFalse();
});
