<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace inspectdiary;

use strings;
use sys;

abstract class QuickPerson {
	public static function get( $id) {
		$dao = new dao\people;
		return ( $dao->getByID( $id));

	}

	public static function find( $a) {
		$debug = false;
		// $debug = true;
		/**
		 * require name, tel, email
		 * $a is an array - and if comming from legacy is probably $_POST
		 */


		$ret = [ 'id' => 0 ];
		$requirePhone = false;

		if ( isset( $a['id'])) {
			if ( $a['id']) {
				$dao = new dao\people;
				if ( $dto = $dao->getByID( $a['id'])) {

					$harvest = [];
					if ( !$dto->email && strings::CheckEmailAddress( (string)$a['email'] )) {
						$harvest['email'] = $dto->email = (string)$a['email'];

					}

					$phone = false;
					if ( isset( $a['phone']) && trim( $a['phone'])) {
						$phone = $a['phone'];

					}
					elseif ( isset( $a['home']) && trim( $a['home'])) {
						$phone = $a['home'];

					}
					elseif ( isset( $a['mobile']) && trim( $a['mobile'])) {
						$phone = $a['mobile'];

					}
					elseif ( isset( $a['business']) && trim( $a['business'])) {
						$phone = $a['business'];

					}

					if ( strings::isPhone( $phone = strings::cleanPhoneString( $phone))) {
						if ( strings::isMobilePhone( $phone) && !$dto->mobile) {
							$harvest['mobile'] = $dto->mobile = $phone;

						}
						elseif ( !$dto->telephone) {

							if ( $phone != $dto->telephone && $phone != $dto->telephone_business) {
								$harvest['telephone'] = $dto->telephone = $phone;

							}

						}

					}

					if ( $harvest) $dao->UpdateByID( $harvest, $dto->id);

				}

			}

		}

		if ( !isset( $a['name'] )) {
			$ret['errorText'] = 'missing name';
			if ( $debug) sys::logger( 'exit:: missing name');
			return ( (object)$ret );

		}

		$ret['name'] = $a['name'];
		$ret['email'] = '';
		$ret['phone'] = '';
		$ret['isNew'] = false;

		$dao = new dao\people;
		if ( isset( $a['email'])) {
			if ( strings::CheckEmailAddress( (string)$a['email'] )) {
				if ( $dto = $dao->getByEmail( (string)$a['email'])) {
					if ( $debug) sys::logger( sprintf( 'QuickPerson::find : found :: email : %s', $a['email']));

					/*
					* We may be able to harvest a phone
					*/
					if ( isset( $a['phone'] )) {
						$theirPhones = [];
						foreach ( [ $dto->telephone,  $dto->telephone2, $dto->telephone_business, $dto->mobile, $dto->mobile2] as $_p) {
							if ( $_p && strings::isPhone( $_p)) {
								$theirPhones[] = strings::cleanPhoneString( $_p);

							}

						}

						if ( $_phone = strings::isMobilePhone( $a['phone'])) {
							if ( !$dto->mobile && !in_array( $_phone, $theirPhones)) {
								if ( $debug) sys::logger( sprintf( '<%s : harvested mobile> : %s', $_phone, __METHOD__));
								$dao->UpdateByID(['mobile' => $_phone], $dto->id);

							}

						}
						elseif ( $_phone = strings::isPhone( $a['phone'])) {
							if ( !$dto->telephone && !in_array( $_phone, $theirPhones)) {
								if ( $debug) sys::logger( sprintf( '<%s : harvested phone> : %s', $_phone, __METHOD__));
								$dao->UpdateByID(['telephone' => $_phone], $dto->id);

							}

						}

					}


					return ( $dto);

				}

				$ret['email'] = (string)$a['email'];

			}
			else {
				$ret['errorText'] = 'invalid email : ' . (string)$a['email'];
				$requirePhone = true;

			}

		}
		else {
			$ret['errorText'] = 'missing email';
			$requirePhone = true;

		}

		foreach( ['phone', 'home', 'business', 'mobile'] as $fld) {
			if ( isset( $a[$fld] ) && trim( $a[$fld])) {
				$__tel = $a[$fld];
				if ( $debug) sys::logger( sprintf( '<search %s> : %s', $__tel, __METHOD__));

				$dto = $dao->getByPHONE( (string)$__tel);
				if ( !$dto) {
					$dto = $dao->getByPHONE( (string)$__tel, $mobile2 = true);

				}

				if ( $dto) {
					if ( !$dto->email && strings::CheckEmailAddress( (string)$a['email'] )) {
						$dto->email = (string)$a['email'];
						$dao->UpdateByID( ['email' => $dto->email], $dto->id);

					}

					return ( $dto);

				}

			}

		}

		if ( $debug) sys::logger( sprintf( '<not found ? add> : %s', __METHOD__));

		$phone = false;
		if ( isset( $a['phone']) && trim( $a['phone'])) {
			$phone = $a['phone'];

		}
		elseif ( isset( $a['home']) && trim( $a['home'])) {
			$phone = $a['home'];

		}
		elseif ( isset( $a['mobile']) && trim( $a['mobile'])) {
			$phone = $a['mobile'];

		}
		elseif ( isset( $a['business']) && trim( $a['business'])) {
			$phone = $a['business'];

		}

		if ( !$phone) {
			$ret['errorText'] = 'missing phone';
			if ( $requirePhone ) return ( (object)$ret );

		}

		if ( !( isset( $a['name'] ) && trim( $a['name']))) {
			$ret['errorText'] = 'not found and no name specified';
			return ( (object)$ret );

		}

		if ( $debug) sys::logger( 'still in game :: ' . ( isset( $ret['errorText']) ? $ret['errorText'] : 'no error' ));

		$aI = [
			'name' => $ret['name'],
			'email' => $ret['email']

		];

		$aI[ strings::isMobilePhone( $phone) ? 'mobile' : 'telephone'] =
			strings::cleanPhoneString( $phone);

		if ( isset( $a['mobile']) && trim( $a['mobile']) && $a['mobile'] != $phone) {
			$aI['mobile'] = strings::cleanPhoneString( (string)$a['mobile']);

		}

		if ( isset( $a['business']) && strings::isPhone( $a['business']) && $a['business'] != $phone) {
			$aI['telephone_business'] = strings::cleanPhoneString( (string)$a['business']);

		}

		if ( isset( $a['address_street'] ) && trim( $a['address_street'])) $aI['address_street'] = (string)$a['address_street'];
		if ( isset( $a['address_suburb'] ) && trim( $a['address_suburb'])) $aI['address_suburb'] = (string)$a['address_suburb'];
		if ( isset( $a['address_postcode'] ) && trim( $a['address_postcode'])) $aI['address_postcode'] = (string)$a['address_postcode'];
		if ( isset( $a['address_state'] ) && trim( $a['address_state'])) $aI['address_state'] = (string)$a['address_state'];

		$id = $dao->Insert( $aI);
		//~ if ( $debug)
		sys::logger( 'insert :: ' . (int)$id);

		$dto = $dao->getByID( $id);
		$dto->isNew = 1;

		return ( $dto);

	}

}
