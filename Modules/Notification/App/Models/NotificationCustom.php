<?php

namespace Modules\Notification\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Notification\Database\factories\NotificationCustomFactory;
use Spatie\Permission\Traits\HasRoles;

class NotificationCustom extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
    protected $table = 'notification_to_role';
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): NotificationCustomFactory
    {
        //return NotificationCustomFactory::new();
    }
}
