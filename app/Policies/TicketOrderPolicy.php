<?php

namespace App\Policies;

use App\Models\TicketOrder;
use App\Models\User;

class TicketOrderPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketOrder $order): bool
    {
        return $user->email === $order->customer_email;
    }

    /**
     * Determine whether the user can pay for the order.
     */
    public function pay(User $user, TicketOrder $order): bool
    {
        return $user->email === $order->customer_email;
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, TicketOrder $order): bool
    {
        return $user->email === $order->customer_email;
    }
}
