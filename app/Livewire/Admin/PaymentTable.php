<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentTable extends Component
{
    use WithPagination;

    public function render()
    {
        $payments = Payment::with(['guest', 'room', 'service'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.payment-table', compact('payments'));
    }
}
