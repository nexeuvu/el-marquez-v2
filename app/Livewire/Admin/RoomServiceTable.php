<?php

namespace App\Livewire\Admin;

use App\Models\Room_service;
use Livewire\Component;
use Livewire\WithPagination;

class RoomServiceTable extends Component
{
    use WithPagination;

    public function render()
    {
        $room_services = Room_service::with(['employee', 'room'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.room-service-table', compact('room_services'));
    }
}
