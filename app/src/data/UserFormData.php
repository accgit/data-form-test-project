<?php

declare(strict_types=1);

namespace App\Data;

class UserFormData extends \Drago\Utils\ExtraArrayHash
{
	public const USER_ID = 'userId';
	public const USER_ID_LENGTH = 11;
	public const ADDRESS_ID = 'addressId';
	public const ADDRESS_ID_LENGTH = 11;
	public const NAME = 'name';
	public const NAME_LENGTH = 255;

	/** Column length = 11 */
	public int $userId;

	/** Reference to table. */
	public AddressFormData $address;

	/** Column length = 11 */
	public int $addressId;

	/** Column length = 255 */
	public string $name;
}
