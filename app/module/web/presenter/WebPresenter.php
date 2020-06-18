<?php

declare(strict_types = 1);

namespace Module\Web;

use App\Data\AddressFormData;
use App\Data\CountryFormData;
use App\Data\UserFormData;
use App\Entity\AddressEntity;
use App\Entity\CountryEntity;
use App\Entity\UserEntity;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Repository\AddressRepository;
use Repository\CountryRepository;
use Repository\UserRepository;
use Tracy\Debugger;


final class WebPresenter extends Presenter
{
	use TranslatorAdapter;

	/** @var UserRepository */
	private $userRepository;

	/** @var AddressRepository */
	private $addressRepository;

	/** @var CountryRepository */
	private $countryRepository;

	/** @var UserEntity */
	private $userEntity;

	/** @var AddressEntity */
	private $addressEntity;

	/** @var CountryEntity */
	private $countryEntity;


	public function __construct(
		UserRepository $userRepository,
		AddressRepository $addressRepository,
		CountryRepository $countryRepository,

		UserEntity $userEntity,
		AddressEntity $addressEntity,
		CountryEntity $countryEntity
	)
	{
		parent::__construct();
		$this->userRepository = $userRepository;
		$this->addressRepository = $addressRepository;
		$this->countryRepository = $countryRepository;

		$this->userEntity = $userEntity;
		$this->addressEntity = $addressEntity;
		$this->countryEntity = $countryEntity;
	}

	protected function createComponentForm()
	{
		$form = new Form;

		$form->addText(UserFormData::NAME, 'name');

		$address = $form->addContainer('address');
		$address->addText(AddressFormData::STREET, 'street')
			->addRule(Form::MAX_LENGTH, null, AddressFormData::STREET_LENGTH);

		$address->addText(AddressFormData::CITY, 'city');
		$address->addText(AddressFormData::ZIP, 'zip');

		$countryItems = [];
		foreach ($this->countryRepository->all()->fetchAll() as $countryItem)
		{
			$countryItems[$countryItem['countryId']] = $countryItem['name'];
		}

		$country = $address->addContainer('country');
		$country->addSelect(CountryFormData::COUNTRY_ID, null, $countryItems);

		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = function ($form, UserFormData $data) {

			// Instance AddressFormData
			$address = $data->address;
			Debugger::barDump($address);

			// Instance CountryFormData
			$country = $address->country;
			Debugger::barDump($country);

			/* save data by array ----------------------------------------------------------------------------------- */

			// Save address data.
			$addressData = [
				AddressFormData::STREET => $address->street,
				AddressFormData::CITY => $address->city,
				AddressFormData::ZIP => $address->zip,
				AddressFormData::COUNTRY_ID => $country->countryId
			];

			unset($address->country);
			$addressRepository = $this->addressRepository;
			$addressRepository->put($addressData);

			// Save user data.
			$userData = [
				UserFormData::NAME => $data->name,
				UserFormData::ADDRESS_ID => $addressRepository->getInsertedId(),
			];
			$this->userRepository->put($userData);

			/* save data by entity ---------------------------------------------------------------------------------- */

			// Save address data.
			$addressEntity = $this->addressEntity;
			$addressEntity->setStreet($address->street);
			$addressEntity->setCity($address->city);
			$addressEntity->setZip($address->zip);
			$addressEntity->setCountryId($country->countryId);

			//$addressRepository->put($addressEntity->getModify());

			// Save user data.
			$userEntity = $this->userEntity;
			$userEntity->setName($data->name);
			$userEntity->setAddressId($addressRepository->getInsertedId());

			//$this->userRepository->put($userEntity->getModify());

			$this->redirect('this');
		};
		return $form;
	}
}
