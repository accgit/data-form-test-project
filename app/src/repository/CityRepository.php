<?php

namespace Repository;

use App\Entity\CityEntity;
use Drago\Database\Connect;
use Drago\Database\Repository;


class CityRepository extends Connect
{
	use Repository;

	public string $table = CityEntity::TABLE;
	public string $columnId = CityEntity::ID_CITY;
}
