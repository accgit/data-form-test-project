<?php

declare(strict_types=1);

namespace App\Entity;

class AddressEntity extends \Drago\Database\Entity
{
	public const TABLE = 'address';

	/**
	 * Column autoIncrement = 1
	 * Column length = 11
	 * Column type = int
	 */
	public int $addressId;

	/**
	 * Column length = 11
	 * Column type = int
	 */
	public int $countryId;

	/**
	 * Column length = 255
	 * Column type = varchar
	 */
	public string $street;

	/**
	 * Column length = 255
	 * Column type = varchar
	 */
	public string $city;

	/**
	 * Column length = 255
	 * Column type = varchar
	 */
	public string $zip;


	public function getAddressId(): ?int
	{
		return $this->addressId;
	}


	public function setAddressId(int $addressId)
	{
		$this['addressId'] = $addressId;
	}


	public function getCountryId(): int
	{
		return $this->countryId;
	}


	public function setCountryId(int $countryId)
	{
		$this['countryId'] = $countryId;
	}


	public function getStreet(): string
	{
		return $this->street;
	}


	public function setStreet(string $street)
	{
		$this['street'] = $street;
	}


	public function getCity(): string
	{
		return $this->city;
	}


	public function setCity(string $city)
	{
		$this['city'] = $city;
	}


	public function getZip(): string
	{
		return $this->zip;
	}


	public function setZip(string $zip)
	{
		$this['zip'] = $zip;
	}
}
