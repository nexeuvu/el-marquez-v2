<?php

namespace App\Livewire\Admin;

use App\Models\Guest;
use App\Models\Room;
use Livewire\Component;

class BookingIndex extends Component
{
    public $guests;
    public $rooms;

    public function mount()
    {
        $this->guests = Guest::all();
        $this->rooms = Room::with('type')->get();
    }

    public function render()
    {
        return view('livewire.admin.booking-index');
    }
}
