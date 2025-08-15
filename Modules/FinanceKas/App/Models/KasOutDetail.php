<?php

namespace Modules\FinanceKas\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceKas\Database\factories\KasOutDetailFactory;
use Spatie\Permission\Traits\HasRoles;

class KasOutDetail extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    protected $table = 'kas_out_detail';
    protected $guarded = [];

    public function head()
    {
        return $this->belongsTo(KasOutHead::class, 'head_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(MasterAccount::class, 'account_id', 'id');
    }
}
