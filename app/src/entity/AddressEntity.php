<?php

declare(strict_types=1);

namespace App\Entity;

final class AddressEntity extends \Drago\Database\Entity
{
	use \Nette\SmartObject;

	const TABLE = 'address';
	const ADDRESS_ID = 'addressId';
	const COUNTRY_ID = 'countryId';
	const STREET = 'street';
	const CITY = 'city';
	const ZIP = 'zip';

	/**
	 * Column autoIncrement 1
	 * Column length 11
	 */
	public int $addressId;

	/**
	 * Column length 11
	 */
	public int $countryId;

	/**
	 * Column length 255
	 */
	public string $street;

	/**
	 * Column length 255
	 */
	public string $city;

	/**
	 * Column length 11
	 */
	public int $zip;
}
