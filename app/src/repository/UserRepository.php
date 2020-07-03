<?php

namespace Repository;

use App\Entity\UserEntity;
use Drago\Database\Connect;
use Drago\Database\Repository;


class UserRepository extends Connect
{
	use Repository;

	public string $table = UserEntity::TABLE;
	public string $columnId = UserEntity::USER_ID;
}
