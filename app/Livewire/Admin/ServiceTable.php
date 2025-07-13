<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Livewire\Component;

class ServiceTable extends Component
{
    public function render()
    {
        $services = Service::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.service-table', compact('services'));
    }
}
