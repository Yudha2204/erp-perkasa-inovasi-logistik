<?php

namespace Modules\FinanceDataMaster\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinanceDataMaster\Database\factories\MasterTermOfPaymentFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MasterTermOfPayment extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'master_term_of_payment';
    protected $guarded = [];

    public function term_payment_contacts()
    {
        return $this->hasMany(TermPaymentContact::class, 'term_payment_id', 'id');
    }
}
