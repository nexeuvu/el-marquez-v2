<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use Livewire\Component;

class EmployeeTable extends Component
{
    public function render()
    {
        $employees = Employee::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('livewire.admin.employee-table', compact('employees'));
    }
}
