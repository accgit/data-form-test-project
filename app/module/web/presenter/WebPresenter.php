<?php

declare(strict_types = 1);

namespace Module\Web;

use App\Data\AddressData;
use App\Data\CountryData;
use App\Data\UserData;
use App\Entity\AddressEntity;
use App\Entity\CityEntity;
use App\Entity\CountryEntity;
use App\Entity\UserEntity;
use Drago\Localization\TranslatorAdapter;
use Exception;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\TextInput;
use Nette\Security\AuthenticationException;
use Repository\AddressRepository;
use Repository\CityRepository;
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

	/** @inject */
	public CityEntity $cityEntity;

	/** @inject */
	public CityRepository $cityRepository;

	/** form country items */
	private array $countryItems;


	protected function createComponentForm()
	{
		$form = new Form;
		if (!$this->user->isLoggedIn()) {
			$form->addText(UserData::NAME, 'name')
				->addRule(Form::MAX_LENGTH, null, UserData::NAME_LENGTH)
				->setRequired();
		}

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

		/* city ----------------------------------------------------------------------------------------------------- */

		$cityItems = [];

		/** @var CityEntity $item */
		foreach ($this->cityRepository->all()->fetchAll() as $item)
		{
			$cityItems[$item->idCity] = $item->city_name;
		}

		$form->addSelect('city_select', null, $cityItems);
		$form->addText('city_name', 'city name')
			->setDisabled();

		/* country items -------------------------------------------------------------------------------------------- */

		/** @var CountryEntity $item */
		foreach ($this->countryRepository->all()->fetchAll() as $item)
		{
			$items[$item->countryId] = $item->name;
			$this->countryItems = $items;
		}

		/* country container ---------------------------------------------------------------------------------------- */

		$country = $address->addContainer('country');
		$country->addSelect(CountryData::COUNTRY_ID, null, $this->countryItems)
			->setPrompt('---')
			->setDisabled();

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

			if ($this->user->isLoggedIn()) {
				$data->name = $this->user->id;
			}

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


	protected function createComponentLogin()
	{
		$form = new Form;
		$form->addText('username');
		$form->addPassword('password');
		$form->addSubmit('login');
		$form->onSuccess[] = function (Form $form, $values) {
			try {
				$this->user->login($values['username'], $values['password']);
				$this->redirect('this');

			} catch (AuthenticationException $e) {
				$this->flashMessage('User not found.');
			}
		};
		return $form;
	}


	public function handleModal(): void
	{
		if ($this->isAjax()) {
			$this->payload->modal = 'run';
			$this->redrawControl('modal');
		}
	}


	public function handleModalChange(?string $val)
	{
		/** @var Form $form */
		$form = $this['form-address'];

		/** @var CountryEntity $item */
		foreach ($this->countryRepository->all()->fetchAll() as $item)
		{
			$items[$item->countryId] = $item->name;
			$this->countryItems = $items;
		}

		/** @var SelectBox $country */
		$country = $form['country-countryId'];
		$country->setItems($this->countryItems);

		if ($val === 'Czech') {
			$country->setDisabled(false);
			$country->setRequired();

		}

		if ($this->isAjax()) {
			$this->payload->data = $val;
			$this->redrawControl('city_name');
		}
	}


	public function renderDefault()
	{
		$this->template->getLatte()->addProvider('formsStack', [$this['form-address-country']]);
	}
}
