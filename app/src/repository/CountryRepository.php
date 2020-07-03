<?php

namespace Repository;

use App\Entity\CountryEntity;
use Drago\Database\Connect;
use Drago\Database\Repository;


class CountryRepository extends Connect
{
	use Repository;

	public string $table = CountryEntity::TABLE;
	public string $columnId = CountryEntity::COUNTRY_ID;
}
