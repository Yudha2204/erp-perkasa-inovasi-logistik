<?php

namespace Modules\FinanceDataMaster\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinanceDataMaster\Database\factories\MasterContactFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinancePiutang\App\Models\SalesOrderHead;
use Modules\Marketing\App\Models\MarketingExport;
use Modules\Marketing\App\Models\MarketingImport;
use Modules\ReportFinance\App\Models\Sao;
use Spatie\Permission\Traits\HasRoles;

class MasterContact extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    
    protected $table = 'master_contact';
    protected $guarded = [];

    public function marketing_exports()
    {
        return $this->hasMany(MarketingExport::class, 'contact_id', 'id');
    }

    public function marketing_imports()
    {
        return $this->hasMany(MarketingImport::class, 'contact_id', 'id');
    }

    public function termPaymentContacts()
    {
        return $this->hasMany(TermPaymentContact::class, 'contact_id', 'id');
    }

    public function sales_orders()
    {
        return $this->hasMany(SalesOrderHead::class, 'contact_id', 'id');
    }

    public function sao()
    {
        return $this->hasOne(Sao::class, 'contact_id', 'id');
    }
    public function saoVendor()
    {
        return $this->hasOne(Sao::class, 'vendor_id', 'id');
    }

    public function ppn()
    {
        return $this->belongsTo(MasterTax::class, 'ppn_id', 'id');
    }
}
