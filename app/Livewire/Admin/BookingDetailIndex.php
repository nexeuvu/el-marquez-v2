<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Room;
use Livewire\Component;

class BookingDetailIndex extends Component
{
    public function render()
    {
        return view('livewire.admin.booking-detail-index', [
            'bookings' => Booking::with('guest')->get(),
            'rooms' => Room::all(),
        ]);
    }
}
