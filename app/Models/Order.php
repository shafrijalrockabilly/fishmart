<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'user_id',
        'voucher_id',
        'voucher_code',
        'status',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'shipping_city_id',
        'postal_code',
        'courier',
        'shipping_service',
        'shipping_etd',
        'shipping_cost',
        'total_weight_gram',
        'subtotal',
        'discount_amount',
        'grand_total',
        'tracking_no',
        'note',
        'paid_at',
        'shipped_at',
        'completed_at',
        'cancelled_at',
        'cancel_reason',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'shipping_cost' => 'integer',
            'total_weight_gram' => 'integer',
            'subtotal' => 'integer',
            'discount_amount' => 'integer',
            'grand_total' => 'integer',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
