<?php

declare(strict_types=1);

namespace App\Entity;

final class UserEntity extends \Drago\Database\Entity
{
	use \Nette\SmartObject;

	const TABLE = 'user';
	const USER_ID = 'userId';
	const ADDRESS_ID = 'addressId';
	const NAME = 'name';

	/**
	 * Column autoIncrement 1
	 * Column length 11
	 */
	public int $userId;

	/**
	 * Column length 11
	 */
	public int $addressId;

	/**
	 * Column length 255
	 */
	public string $name;
}
