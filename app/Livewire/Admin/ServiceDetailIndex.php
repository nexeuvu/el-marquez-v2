<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Employee;
use App\Models\Service;
use Livewire\Component;

class ServiceDetailIndex extends Component
{
    public function render()
    {
        // Asegúrate que todas las variables estén bien definidas y cargadas
        $services = Service::all(); // Usa '->where('status', true)' si tienes esa columna
        $employees = Employee::where('status', true)->get();
        $bookings = Booking::with(['guest'])->get(); // Relacionado para mostrar datos del huésped y habitación

        // Devuelve la vista con todas las variables necesarias
        return view('livewire.admin.service-detail-index', compact('services', 'employees', 'bookings'));
    }
}
