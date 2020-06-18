<?php

namespace Repository;

use Drago\Database\Connect;
use Drago\Database\Repository;


class CountryRepository extends Connect
{
	use Repository;

	public $table = 'country';
	public $columnId = 'countryId';
}
