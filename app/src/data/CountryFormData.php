<?php

declare(strict_types=1);

namespace App\Data;

class CountryFormData extends \Drago\Utils\ExtraArrayHash
{
	public const COUNTRY_ID = 'countryId';
	public const COUNTRY_ID_LENGTH = 11;
	public const NAME = 'name';
	public const NAME_LENGTH = 255;

	/** Column length = 11 */
	public int $countryId;

	/** Column length = 255 */
	public string $name;
}
