<?php

namespace Modules\FinanceKas\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceKas\Database\factories\KasInDetailFactory;
use Spatie\Permission\Traits\HasRoles;

class KasInDetail extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    protected $table = 'kas_in_detail';
    protected $guarded = [];

    public function head()
    {
        return $this->belongsTo(KasInHead::class, 'head_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(MasterAccount::class, 'account_id', 'id');
    }
}
