<?php

declare(strict_types=1);

namespace App\Data;

final class CountryData extends \Drago\Utils\ExtraArrayHash
{
	use \Nette\SmartObject;

	const COUNTRY_ID = 'countryId';
	const COUNTRY_ID_LENGTH = 11;
	const NAME = 'name';
	const NAME_LENGTH = 255;

	public int $countryId;
	public string $name;
}
