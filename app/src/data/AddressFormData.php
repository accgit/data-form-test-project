<?php

declare(strict_types=1);

namespace App\Data;

class AddressFormData extends \Drago\Utils\ExtraArrayHash
{
	public const ADDRESS_ID = 'addressId';
	public const ADDRESS_ID_LENGTH = 11;
	public const COUNTRY_ID = 'countryId';
	public const COUNTRY_ID_LENGTH = 11;
	public const STREET = 'street';
	public const STREET_LENGTH = 255;
	public const CITY = 'city';
	public const CITY_LENGTH = 255;
	public const ZIP = 'zip';
	public const ZIP_LENGTH = 255;

	/** Column length = 11 */
	public int $addressId;

	/** Reference to table. */
	public CountryFormData $country;

	/** Column length = 11 */
	public int $countryId;

	/** Column length = 255 */
	public string $street;

	/** Column length = 255 */
	public string $city;

	/** Column length = 255 */
	public string $zip;
}
