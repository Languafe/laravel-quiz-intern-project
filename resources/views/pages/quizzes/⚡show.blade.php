<?php

use Livewire\Component;

new class extends Component
{
    public $message = 'Her skal kan man kunne ta en quiz';

};
?>

<div class="p-4 bg-gray-100 rounded">
    {{ $message }}
</div>
