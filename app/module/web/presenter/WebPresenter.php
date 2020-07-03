<?php

declare(strict_types = 1);

namespace Module\Web;

use App\Data\AddressData;
use App\Data\CountryData;
use App\Data\UserData;
use App\Entity\AddressEntity;
use App\Entity\CountryEntity;
use App\Entity\UserEntity;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Repository\AddressRepository;
use Repository\CountryRepository;
use Repository\UserRepository;
use Tracy\Dumper;


final class WebPresenter extends Presenter
{
	use TranslatorAdapter;

	/** @inject */
	public UserRepository $userRepository;

	/** @inject */
	public UserEntity $userEntity;

	/** @inject */
	public AddressRepository $addressRepository;

	/** @inject */
	public AddressEntity $addressEntity;

	/** @inject */
	public CountryRepository $countryRepository;

	/** form country items */
	private array $countryItems;


	protected function createComponentForm()
	{
		$form = new Form;
		$form->addText(UserData::NAME, 'name');

		$address = $form->addContainer('address');
		$address->addText(AddressData::STREET, 'street')
			->addRule(Form::MAX_LENGTH, null, AddressData::STREET_LENGTH);

		$address->addText(AddressData::CITY, 'city');
		$address->addInteger(AddressData::ZIP, 'zip');

		/** @var CountryEntity $item */
		foreach ($this->countryRepository->all()->fetchAll() as $item)
		{
			$items[$item->countryId] = $item->name;
			$this->countryItems = $items;
		}

		$country = $address->addContainer('country');
		$country->addSelect(CountryData::COUNTRY_ID, null, $this->countryItems);

		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = function ($form, UserData $data) {

			Dumper::$theme = 'dark';
			Dumper::dump($data);

			// Instance AddressFormData
			$address = $data->address;
			Dumper::dump($address);

			// Instance CountryFormData
			$country = $address->country;
			Dumper::dump($country);

			/* save address data ------------------------------------------------------------------------------------ */

			$address->offsetUnset('country');
			$address->offsetSet($address::COUNTRY_ID, $country->countryId);

			$addressRepository = $this->addressRepository;
			$addressRepository->put($address->toArray());

			/* save user data --------------------------------------------------------------------------------------- */

			$data->offsetUnset('address');
			$data->addressId = $addressRepository->getInsertedId();
			$this->userRepository->put($data->toArray());

			/* save address data by entity -------------------------------------------------------------------------- */

			$addressEntity = $this->addressEntity;
			$addressEntity->street = $address->street;
			$addressEntity->city = $address->city;
			$addressEntity->zip = $address->zip;
			$addressEntity->countryId = $country->countryId;

			$addressRepository->put($addressEntity->toArray());

			/* save user data by entity ----------------------------------------------------------------------------- */

			$userEntity = $this->userEntity;
			$userEntity->name = $data->name;
			$userEntity->addressId = $addressRepository->getInsertedId();

			$this->userRepository->put($userEntity->toArray());

			//$this->redirect('this');
		};
		return $form;
	}
}
