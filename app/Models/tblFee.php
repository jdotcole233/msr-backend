<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class tblFee extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id', 'fkWarehouseIDNo', 'commodityType', 'storageFee',
        'loadingFee', 'unloadingFee', 'cleaningFee', 'rebaggingFee', 
        'status', 'lastUpdatedByName'
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function warehouse () : BelongsTo
    {
        return $this->belongsTo(tblWarehouse::class, 'fkWarehouseIDNo', 'warehouseIDNo');
    }
}
