<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class ReportManager extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $status = '';

    public function exportCSV()
    {
        $query = Booking::with(['user', 'vehicle']);

        if ($this->dateFrom)
            $query->where('start_date', '>=', $this->dateFrom);
        if ($this->dateTo)
            $query->where('end_date', '<=', $this->dateTo);
        if ($this->status)
            $query->where('status', $this->status);

        $bookings = $query->get();
        $fileName = 'bookings_report_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Customer', 'Vehicle', 'Start Date', 'End Date', 'Status', 'Total Price'];

        $callback = function () use ($bookings, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($bookings as $booking) {
                $days = \Carbon\Carbon::parse($booking->start_date)->diffInDays(\Carbon\Carbon::parse($booking->end_date));
                if ($days == 0)
                    $days = 1;
                $total = $days * $booking->vehicle->price;

                fputcsv($file, [
                    $booking->id,
                    $booking->user->name,
                    $booking->vehicle->make . ' ' . $booking->vehicle->model,
                    $booking->start_date,
                    $booking->end_date,
                    $booking->status,
                    $total
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $query = Booking::with(['user', 'vehicle']);

        if ($this->dateFrom)
            $query->where('start_date', '>=', $this->dateFrom);
        if ($this->dateTo)
            $query->where('end_date', '<=', $this->dateTo);
        if ($this->status)
            $query->where('status', $this->status);

        return view('livewire.admin.report-manager', [
            'bookings' => $query->latest()->paginate(20)
        ]);
    }
}
