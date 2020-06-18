<?php

declare(strict_types=1);

namespace App\Entity;

class CountryEntity extends \Drago\Database\Entity
{
	public const TABLE = 'country';

	/**
	 * Column autoIncrement = 1
	 * Column length = 11
	 * Column type = int
	 */
	public int $countryId;

	/**
	 * Column length = 255
	 * Column type = varchar
	 */
	public string $name;


	public function getCountryId(): ?int
	{
		return $this->countryId;
	}


	public function setCountryId(int $countryId)
	{
		$this['countryId'] = $countryId;
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function setName(string $name)
	{
		$this['name'] = $name;
	}
}
