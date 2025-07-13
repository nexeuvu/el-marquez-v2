<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Booking_detail;
use App\Models\Room;
use Livewire\Component;
use Livewire\WithPagination;

class BookingDetailTable extends Component
{
    use WithPagination;

    public function render()
    {
        $bookingDetails = Booking_detail::with(['booking.guest', 'room'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $bookings = Booking::with('guest')->get();
        $rooms = Room::all();

        return view('livewire.admin.booking-detail-table', compact('bookingDetails', 'bookings', 'rooms'));
    }
}
