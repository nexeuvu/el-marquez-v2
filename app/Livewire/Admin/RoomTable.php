<?php

namespace App\Livewire\Admin;

use App\Models\Room;
use Livewire\Component;

class RoomTable extends Component
{
    public function render()
    {
        $rooms = Room::where('status', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.room-table', compact('rooms'));
    }
}
