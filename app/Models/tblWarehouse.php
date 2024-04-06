<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class tblWarehouse extends Model
{
    use HasFactory;
    public $fillable = [
        'user_id', 'warehouseIDNo', 'registeredName', 'region',
        'townCity', 'district', 'ghPostAddress', 'GPSLat', 'GPSLong',
        'yearEstablished', 'businessType', 'storageCapacity', 'offersCleaning',
        'offersRebagging', 'mainContactName', 'mainContactIsMale', 'mainContactPosition',
        'mainContactTel', 'mainContactEmail', 'declarationAccepted', 'status', 'lastUpdatedByName',
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function commodities (): HasMany
    {
        return $this->hasMany(tblCommodity::class, 'fkWarehouseIDNo', 'warehouseIDNo');
    }
}
