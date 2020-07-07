<?php

declare(strict_types=1);

namespace App\Data;

final class CityData extends \Drago\Utils\ExtraArrayHash
{
	use \Nette\SmartObject;

	const ID_CITY = 'idCity';
	const ID_CITY_LENGTH = 11;
	const CITY_NAME = 'city_name';
	const CITY_NAME_LENGTH = 50;

	public int $idCity;
	public string $city_name;
}
