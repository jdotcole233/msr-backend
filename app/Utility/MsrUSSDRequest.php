<?php

namespace App\Utility;

class MsrUSSDRequest
{

  public function __construct(
    private $actorID,
    private $transactionType,
    private $warehouseName,
    private $commodityName,
    private $unit_price,
    private $duration,
    private $quantity,
    private $package_size,
    private $grn_id,
    private $phoneNumber,
    private $harvestType
  ) {
   
  }

  public function getHarvestType(): string
  {
    return $this->harvestType;
  }

  public function getActorID (): string
  {
    return $this->actorID;
  }

  public function getTransactionType (): string
  {
    return $this->transactionType;
  }

  public function getWarehouseName (): string
  {
    return $this->warehouseName;
  }

  public function getCommodityName (): string
  {
    return $this->commodityName;
  }

  public function getUnitPrice (): string
  {
    return $this->unit_price;
  }

  public function getDuration (): string
  {
    return $this->duration;
  }

  public function getQuantity (): string
  {
    return $this->quantity;
  }

  public function getPackageSize (): string
  {
    return $this->package_size;
  }

  public function getGRNID (): string
  {
    return $this->grn_id;
  }

  public function getPhoneNumber (): string
  {
    return $this->phoneNumber;
  }

}