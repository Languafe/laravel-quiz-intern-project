<?php

it('can create a quiz', function () {
    $quiz = \App\Models\Quiz::factory()->create();

    expect($quiz)->toBeInstanceOf(\App\Models\Quiz::class);
});

it('has questions', function () {
    $quiz = \App\Models\Quiz::factory()->create();

    expect($quiz->questions)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
});
