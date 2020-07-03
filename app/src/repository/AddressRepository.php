<?php

namespace Repository;

use App\Entity\AddressEntity;
use Drago\Database\Connect;
use Drago\Database\Repository;


class AddressRepository extends Connect
{
	use Repository;

	public string $table = AddressEntity::TABLE;
	public string $columnId = AddressEntity::ADDRESS_ID;
}
