<?php

namespace App\Livewire\Admin;

use App\Models\Service_detail;
use Livewire\Component;

class ServiceDetailShow extends Component
{
    public $detailId;
    public $serviceDetail;

    public function mount($detailId)
    {
        $this->detailId = $detailId;
        $this->loadDetail();
    }

    public function loadDetail()
    {
        $this->serviceDetail = Service_detail::with(['service', 'employee', 'booking'])
            ->findOrFail($this->detailId);
    }

    public function render()
    {
        return view('livewire.admin.service-detail-show');
    }
}
