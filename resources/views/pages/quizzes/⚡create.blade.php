<?php

use Livewire\Component;

new class extends Component
{
    public $message = 'Her kan du lage en quiz';

    public $layout = 'app';
};
?>

<div class="p-4 bg-gray-100 rounded">
    {{ $message }}
</div>
