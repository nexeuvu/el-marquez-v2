<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Livewire\Component;

class BookingTable extends Component
{
    public function render()
    {
        $bookings = Booking::orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.booking-table', compact('bookings'));
    }
}
