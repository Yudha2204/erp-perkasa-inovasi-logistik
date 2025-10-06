<?php

namespace Modules\GeneralLedger\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinanceDataMaster\App\Models\MasterAccount;

class GeneralJournalDetail extends Model
{
    use HasFactory;
    
    protected $table = 'general_journal_details';
    protected $guarded = [];

    public function head()
    {
        return $this->belongsTo(GeneralJournalHead::class, 'head_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(MasterAccount::class, 'account_id', 'id');
    }
}
