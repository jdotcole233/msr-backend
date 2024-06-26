<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class tblActor extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id', 'name', 'contactPhone', 'ageGroup',
        'isMale', 'region', 'townCity', 'district', 'age', 'ghana_card',
        'status', 'lastUpdatedByName'
    ];
}
