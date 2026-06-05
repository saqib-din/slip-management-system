<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [

        // Invoice Info
        'invoice_no',
        'invoice_date',
        'invoice_time',

        // Customer
        'customer_name',
        'vat_no',

        // References
        'po_no',
        'del_no',
        'received_by',

        // Items JSON
        'items',

        // Totals
        'subtotal',
        'discount',
        'vat_total',
        'gross_total',

        // Extra
        'amount_words',

        // System
        'created_by',
    ];

    protected $casts = [
        'items' => 'array',
        'invoice_date' => 'date',
        'invoice_time' => 'datetime:H:i',
    ];

    /*
    |--------------------------------------------------------------------------
    | Helper: Auto invoice number suggestion (optional)
    |--------------------------------------------------------------------------
    */
    public static function generateInvoiceNo()
    {
        $last = self::latest('id')->first();

        $next = $last ? ((int) str_replace('INV-', '', $last->invoice_no) + 1) : 1;

        return 'INV-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }
}
