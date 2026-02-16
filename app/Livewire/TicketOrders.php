<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TicketOrder;
use Illuminate\Support\Facades\Log;

class TicketOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $date = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'date' => ['except' => ''],
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'status', 'date'])) {
            $this->resetPage();
        }
    }





    public function render()
    {
        $query = TicketOrder::with(['ticket', 'user', 'ticket.place'])
            ->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('order_id', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->date) {
            $query->whereDate('created_at', $this->date);
        }

        // Calculate stats based on current filters (optional, or global stats)
        // For the dashboard cards, usually we want global stats or filtered stats?
        // Let's match the original controller: stats based on *all* orders or *filtered* orders to be useful?
        // Original controller calculated stats on the filtered collection: $paidOrders = $orders->whereIn('status', ['paid', 'used'])->count();
        // But doing it on paginated result is wrong.
        // Let's do it on the query *before* pagination for the current view's context, OR global stats.
        // Usually stats cards show global stats or at least respected filters.
        
        // Clone query for stats to respect filters
        $statsQuery = clone $query;
        // Optimization: We could cache these or calculate them separately if performance is an issue.
        // For now, let's calculate simplistic stats based on current filter context
        
        // Actually, typically stats cards at top are "All time" or "Today" independent of table search.
        // Let's stick to the original controller logic which seemed to use the $orders variable which was the *filtered* list?
        // Wait, controller: $orders = $query->paginate(15);
        // $paidOrders = $orders->whereIn... -> this would only count visible items on current page! That's a bug in original controller if so.
        // Let's fix it to be global stats or properly filtered stats.
        // Let's do global stats for the cards to be consistent.
        
        $stats = [
            'total_orders' => TicketOrder::count(),
            'paid_orders' => TicketOrder::whereIn('status', ['paid', 'used'])->count(),
            'pending_orders' => TicketOrder::where('status', 'pending')->count(),
            'revenue' => TicketOrder::whereIn('status', ['paid', 'used'])->sum('total_price'),
        ];
        
        // If filters are active, maybe render query-based stats? 
        // For now let's keep it simple: reliable global stats are better than page-limited stats.

        $orders = $query->paginate(10);

        return view('livewire.ticket-orders', [
            'orders' => $orders,
            'stats' => $stats
        ]);
    }
}
