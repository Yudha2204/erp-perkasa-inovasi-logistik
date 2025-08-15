<?php

namespace Modules\Setting\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogoAddress extends Model
{
    use HasFactory;
    protected $table = 'logo_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'address',
        'image_path',
    ];
}