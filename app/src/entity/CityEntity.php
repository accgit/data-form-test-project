<?php

declare(strict_types=1);

namespace App\Entity;

final class CityEntity extends \Drago\Database\Entity
{
	use \Nette\SmartObject;

	const TABLE = 'city';
	const ID_CITY = 'idCity';
	const CITY_NAME = 'city_name';

	/**
	 * Column autoIncrement 1
	 * Column length 11
	 */
	public int $idCity;

	/**
	 * Column length 50
	 */
	public string $city_name;
}
