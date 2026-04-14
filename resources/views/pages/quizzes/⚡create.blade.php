<?php

use Livewire\Component;

new class extends Component
{
    public $message = 'Her kan du lage en quiz!';

    public $layout = 'app';
};
?>

<div class="p-4 bg-gray-100 rounded">
    {{ $message }}

    <input class="bg-gray-200 border border-gray-300 rounded py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500" 
    type="text" wire:model.live="Quiz Title" placeholder="Quiz Title">

    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" wire:click="createQuiz">Create Quiz</button>
</div>
