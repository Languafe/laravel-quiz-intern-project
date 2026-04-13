<?php

use Livewire\Component;

new class extends Component
{
    public $message = 'Liste over alle quizzes skal vises her';
};
?>

<div class="p-4 bg-gray-100 rounded">
    {{ $message }}
</div>
