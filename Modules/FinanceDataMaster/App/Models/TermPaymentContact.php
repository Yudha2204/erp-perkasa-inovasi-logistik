<?php

namespace Modules\FinanceDataMaster\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinanceDataMaster\Database\factories\TermPaymentContactFactory;
use Spatie\Permission\Traits\HasRoles;

class TermPaymentContact extends Model
{
    use HasFactory, HasRoles;
    
    protected $table = 'term_payment_master_contact';
    protected $guarded = [];

    public function term_payment()
    {
        return $this->belongsTo(MasterTermOfPayment::class, 'term_payment_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(MasterContact::class, 'contact_id', 'id');
    }
}
