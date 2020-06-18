<?php

namespace Repository;

use Drago\Database\Connect;
use Drago\Database\Repository;


class AddressRepository extends Connect
{
	use Repository;

	public $table = 'address';
	public $columnId = 'addressId';
}
