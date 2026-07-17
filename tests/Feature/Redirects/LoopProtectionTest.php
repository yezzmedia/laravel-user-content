<?php

declare(strict_types=1);

use YezzMedia\Content\Actions\ProtectAgainstRedirectLoopsAction;
use YezzMedia\Content\Models\Redirect;

it('rejects self-referential redirect', function () {
    $project = $this->createProject();

    $result = app(ProtectAgainstRedirectLoopsAction::class)->execute('/same', '/same', $project->id);

    expect($result)->toBeFalse();
});

it('passes when no chain exists', function () {
    $project = $this->createProject();

    $result = app(ProtectAgainstRedirectLoopsAction::class)->execute('/a', '/b', $project->id);

    expect($result)->toBeTrue();
});

it('detects direct loop', function () {
    $project = $this->createProject();
    Redirect::create(['project_id' => $project->id, 'source' => '/b', 'target' => '/a']);

    $result = app(ProtectAgainstRedirectLoopsAction::class)->execute('/a', '/b', $project->id);

    expect($result)->toBeFalse();
});

it('detects indirect loop', function () {
    $project = $this->createProject();
    Redirect::create(['project_id' => $project->id, 'source' => '/b', 'target' => '/c']);
    Redirect::create(['project_id' => $project->id, 'source' => '/c', 'target' => '/d']);
    Redirect::create(['project_id' => $project->id, 'source' => '/d', 'target' => '/a']);

    $result = app(ProtectAgainstRedirectLoopsAction::class)->execute('/a', '/b', $project->id);

    expect($result)->toBeFalse();
});
