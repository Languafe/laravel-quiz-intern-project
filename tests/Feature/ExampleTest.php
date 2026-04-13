<?php

test('the application returns a successful response', function () {
    $response = $this->get('/quizzes');

    $response->assertStatus(200);
});
