<?php

declare(strict_types=1);

namespace App\Entity;

final class CountryEntity extends \Drago\Utils\ExtraArrayHash
{
	use \Nette\SmartObject;

	const TABLE = 'country';
	const COUNTRY_ID = 'countryId';
	const NAME = 'name';

	/**
	 * Column autoIncrement 1
	 * Column length 11
	 */
	public int $countryId;

	/**
	 * Column length 255
	 */
	public string $name;
}
