<?php

namespace App\Livewire\Admin;

use App\Models\Guest;
use Livewire\Component;

class GuestTable extends Component
{
    public function render()
    {
        $guests = Guest::orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.guest-table', compact('guests'));
    }
}
