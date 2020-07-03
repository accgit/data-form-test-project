<?php

declare(strict_types=1);

namespace App\Data;

final class UserData extends \Drago\Utils\ExtraArrayHash
{
	use \Nette\SmartObject;

	const USER_ID = 'userId';
	const USER_ID_LENGTH = 11;
	const ADDRESS_ID = 'addressId';
	const ADDRESS_ID_LENGTH = 11;
	const NAME = 'name';
	const NAME_LENGTH = 255;

	public int $userId;
	public AddressData $address;
	public int $addressId;
	public string $name;
}
