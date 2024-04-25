<?php

namespace App\Utility;

class MsrActor
{
  public function __construct(
    private string $region,
    private string $gender,
    private string $name,
    private string $phoneNumber
  ) {
  //
  }

  public function getRegion (): string
  {
    return $this->region;
  }

  public function getGender (): string
  {
    return $this->gender;
  }

  public function getName (): string
  {
    return $this->name;
  }


  public function getPhoneNumber (): string
  {
    return $this->phoneNumber;
  }
}