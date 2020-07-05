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
use Exception;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Repository\AddressRepository;
use Repository\CountryRepository;
use Repository\UserRepository;
use Tracy\Debugger;
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

	/** select */
	public string $change = '';


	protected function createComponentForm()
	{
		$form = new Form;
		$form->addText(UserData::NAME, 'name')
			->addRule(Form::MAX_LENGTH, null, UserData::NAME_LENGTH)
			->setRequired();

		/* Address container ---------------------------------------------------------------------------------------- */

		$address = $form->addContainer('address');
		$address->addText(AddressData::STREET, 'street')
			->addRule(Form::MAX_LENGTH, null, AddressData::STREET_LENGTH)
			->setRequired();

		$address->addText(AddressData::CITY, 'city')
			->addRule(Form::MAX_LENGTH, null, AddressData::CITY_LENGTH)
			->setRequired();

		$address->addInteger(AddressData::ZIP, 'zip')
			->addRule(Form::MAX_LENGTH, null, AddressData::ZIP_LENGTH)
			->setRequired();

		/* country items -------------------------------------------------------------------------------------------- */

		/** @var CountryEntity $item */
		foreach ($this->countryRepository->all()->fetchAll() as $item)
		{
			$items[$item->countryId] = $item->name;
			$this->countryItems = $items;
		}

		/* country container ---------------------------------------------------------------------------------------- */

		$country = $address->addContainer('country');
		$country->addSelect(CountryData::COUNTRY_ID, null, $this->countryItems);

		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = function (Form $form, UserData $data) {

			//Dumper::$theme = 'dark';
			//Dumper::dump($data);

			// Instance AddressFormData
			$address = $data->address;
			//Dumper::dump($address);

			// Instance CountryFormData
			$country = $address->country;
			//Dumper::dump($country);

			try {

				/** @var UserEntity $userName */
				$userName = $this->userRepository
					->discover('name', $data->name)
					->fetch();

				if ($userName) {
					throw new Exception('This name exists.', 1);
				}

				/* save address data -------------------------------------------------------------------------------- */

				$address->offsetUnset('country');
				$address->offsetSet($address::COUNTRY_ID, $country->countryId);

				$addressRepository = $this->addressRepository;
				$addressRepository->put($address->toArray());

				/* save user data ----------------------------------------------------------------------------------- */

				$data->offsetUnset('address');
				$data->addressId = $addressRepository->getInsertedId();
				$this->userRepository->put($data->toArray());

				/* save address data by entity ---------------------------------------------------------------------- */

				$addressEntity = $this->addressEntity;
				$addressEntity->street = $address->street;
				$addressEntity->city = $address->city;
				$addressEntity->zip = $address->zip;
				$addressEntity->countryId = $country->countryId;

				//$addressRepository->put($addressEntity->toArray());

				/* save user data by entity ------------------------------------------------------------------------- */

				$userEntity = $this->userEntity;
				$userEntity->name = $data->name;
				$userEntity->addressId = $addressRepository->getInsertedId();

				//$this->userRepository->put($userEntity->toArray());
				$form->reset();
				if ($this->isAjax()) {
					$this->flashMessage('The form was sent.');
					$this->redrawControl('factory');
					$this->redrawControl('message');
				}

			} catch (Exception $e) {
				if ($e->getCode() === 1) {
					if ($this->isAjax()) {
						$this->flashMessage('This name exists.');
						$this->redrawControl('message');
					}
				}
			}

			//$this->redirect('this');
		};
		return $form;
	}


	public function handleChange()
	{
		if ($this->isAjax()) {
			$this->change = 'Chosen';
			$this->redrawControl('change');
		}
	}


	public function renderDefault()
	{
		$this->template->change = $this->change;
	}
}
