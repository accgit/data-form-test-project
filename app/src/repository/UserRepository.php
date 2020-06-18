<?php

namespace Repository;

use Drago\Database\Connect;
use Drago\Database\Repository;


class UserRepository extends Connect
{
	use Repository;

	public $table = 'user';
	public $columnId = 'userId';
}
