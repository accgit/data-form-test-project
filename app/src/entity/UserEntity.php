<?php

declare(strict_types=1);

namespace App\Entity;

class UserEntity extends \Drago\Database\Entity
{
	public const TABLE = 'user';

	/**
	 * Column length = 11
	 * Column type = int
	 */
	public int $userId;

	/**
	 * Column length = 11
	 * Column type = int
	 */
	public int $addressId;

	/**
	 * Column length = 255
	 * Column type = varchar
	 */
	public string $name;


	public function getUserId(): int
	{
		return $this->userId;
	}


	public function setUserId(int $userId)
	{
		$this['userId'] = $userId;
	}


	public function getAddressId(): int
	{
		return $this->addressId;
	}


	public function setAddressId(int $addressId)
	{
		$this['addressId'] = $addressId;
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
