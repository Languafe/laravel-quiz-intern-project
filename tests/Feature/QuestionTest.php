<?php

it('has answers', function () {
    $question = \App\Models\Question::factory()->create();

    expect($question->answers)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
});
