<?php

namespace Modules\FinanceKas\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class NoTransactionsKasOut extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    protected $table = 'no_transaction_kas_out';
    protected $guarded = [];

    public function kas_out()
    {
        return $this->hasMany(KasOutHead::class, 'id', 'transaction_id');
    }

    public function getNumberAttribute()
    {
        $kasOut = KasOutHead::where('transaction_id', $this->id)->latest()->first();
        if($kasOut) {
            return $kasOut->number + 1;
        } else {
            return $this->start;
        }
    }

    protected $appends = ['number'];
}
