<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\Service;
use Livewire\Component;

class PaymentIndex extends Component
{
    public $guests;
    public $rooms;
    public $bookings;
    public $services;

    public function mount()
    {
        $this->guests = Guest::all();
        $this->rooms = Room::with('type')->get();
        $this->bookings = Booking::with('guest')->get();
        $this->services = Service::all(); // O Room_service::all();
    }

    public function render()
    {
        return view('livewire.admin.payment-index');
    }
}
