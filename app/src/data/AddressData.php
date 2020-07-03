<?php

declare(strict_types=1);

namespace App\Data;

final class AddressData extends \Drago\Utils\ExtraArrayHash
{
	use \Nette\SmartObject;

	const ADDRESS_ID = 'addressId';
	const ADDRESS_ID_LENGTH = 11;
	const COUNTRY_ID = 'countryId';
	const COUNTRY_ID_LENGTH = 11;
	const STREET = 'street';
	const STREET_LENGTH = 255;
	const CITY = 'city';
	const CITY_LENGTH = 255;
	const ZIP = 'zip';
	const ZIP_LENGTH = 255;

	public int $addressId;
	public CountryData $country;
	public int $countryId;
	public string $street;
	public string $city;
	public string $zip;
}
