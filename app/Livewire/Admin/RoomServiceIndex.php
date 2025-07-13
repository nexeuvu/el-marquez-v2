<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use App\Models\Room;
use Livewire\Component;

class RoomServiceIndex extends Component
{
    public $employees;
    public $rooms;

    public function mount()
    {
        $this->employees = Employee::all();
        $this->rooms = Room::with('type')->get(); // Asegúrate de tener la relación 'type' si aplica
    }

    public function render()
    {
        return view('livewire.admin.room-service-index');
    }
}
