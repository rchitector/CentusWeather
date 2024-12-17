<?php


use App\Models\User;
use App\Models\UserSettings;

it('returns UserSetting\'s parent User relation', function () {
    $user = User::factory()->create();
    $userSettings = UserSettings::factory()->for($user)->create();

    expect($userSettings->user)->toBeInstanceOf(User::class)
        ->and($userSettings->user->id)->toBe($user->id);
});
