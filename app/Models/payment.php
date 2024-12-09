<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    protected $fillable = [
        'order_id', 'amount', 'status', 'method', 'payment_date'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
enum PaymentStatus: string
{
    case Pending = 'pending';
    case Successful = 'successful';
    case Failed = 'failed';
}
enum PaymentMethod: string
{
    case CreditCard = 'credit_card';
    case Paypal = 'paypal';
    case BankTransfer = 'bank_transfer';
}