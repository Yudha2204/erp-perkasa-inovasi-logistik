<?php

namespace Modules\FinanceDataMaster\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FinanceDataMaster\Database\factories\ClassificationAccountTypeFactory;
use Spatie\Permission\Traits\HasRoles;

class ClassificationAccountType extends Model
{
    use HasFactory, HasRoles;
    
    protected $table = 'classification_account_type';
    protected $guarded = [];

    public function account_types()
    {
        return $this->hasMany(AccountType::class, 'classification_id', 'id');
    }
}
