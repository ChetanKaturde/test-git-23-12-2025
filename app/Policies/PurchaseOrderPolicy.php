<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PurchaseOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can approve the purchase order.
     */
    public function approve(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasRole(['admin', 'purchase_team']) &&
               $purchaseOrder->status === 'pending' ;
              // $user->id !== $purchaseOrder->created_by;
    }

    /**
     * Determine whether the user can view the purchase order.
     */
    public function view(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasRole(['admin', 'purchase_team', 'inventory_manager']) ||
               $user->id === $purchaseOrder->created_by;
    }

    /**
     * Determine whether the user can update the purchase order.
     */
    public function update(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasRole(['admin', 'purchase_team']) ||
               (
                   $user->id === $purchaseOrder->created_by &&
                   in_array($purchaseOrder->status, ['pending', 'rejected'])
               );
    }

    /**
     * Determine whether the user can reject the purchase order (optional).
     */
    public function reject(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasRole(['admin']) &&
               $purchaseOrder->status === 'pending';
    }
}
