<?php

namespace App\Livewire\Admin;

use App\Models\Service_detail;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceDetailTable extends Component
{
    use WithPagination;

    public function render()
    {
        $serviceDetails = Service_detail::with(['service', 'employee', 'booking.guest', 'booking.room'])
                                ->latest()
                                ->paginate(10);

        return view('livewire.admin.service-detail-table', compact('serviceDetails'));
    }
}
