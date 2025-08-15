<?php

namespace Modules\FinanceKas\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class NoTransactionsKasIn extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    protected $table = 'no_transaction_kas_in';
    protected $guarded = [];

    public function kas_in()
    {
        return $this->hasMany(KasInHead::class, 'id', 'transaction_id');
    }

    public function getNumberAttribute()
    {
        $kasIn = KasInHead::where('transaction_id', $this->id)->latest()->first();
        if($kasIn) {
            return $kasIn->number + 1;
        } else {
            return $this->start;
        }
    }

    protected $appends = ['number'];
}
