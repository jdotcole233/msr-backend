<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class tblOperator extends Model
{
    use HasFactory;
    public $fillable = [
        'user_id', 'fkWarehouseIDNo', 'operatorName', 'contactPhone',
        'ageGroup', 'isMale', 'isOwner', 'status', 'lastUpdatedByName'
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'operator_id');
    }

    public function warehouse () : BelongsTo
    {
        return $this->belongsTo(tblWarehouse::class, 'fkWarehouseIDNo', 'warehouseIDNo');
    }
}
