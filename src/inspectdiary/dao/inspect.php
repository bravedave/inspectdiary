<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace inspectdiary\dao;

use dao\_dao;

class inspect extends _dao {
	protected $_db_name = 'inspect';
	protected $template = __NAMESPACE__ . '\dto\inspect';

	public function getDetail( $dto) {
		// \sys::logger( sprintf('<%s> %s', $dto->inspect_diary_id, __METHOD__));

		if ( $dto->inspect_diary_id) {
			$dao = new inspect_diary;
			if ( $_dto = $dao->getByID($dto->inspect_diary_id)) {
				$dto->date = $_dto->date;

				if ( $_dto->property_id) {
					$dao = new properties;
					if ( $_dto = $dao->getByID($_dto->property_id)) {
						$dto->address_street = $_dto->address_street;

					}

				}

			}

			if ( $dto->person_id) {
				$dao = new people;
				if ( $_dto = $dao->getByID($dto->person_id)) {
					$dto->property2sell = $_dto->property2sell;

				}

			}

		}

		return $dto;

	}

	public function Insert( $a) {
		$a[ 'created'] = $a['updated'] = self::dbTimeStamp();
		return parent::Insert( $a);

	}

	public function prendiIlDiario( int $inspectdiaryID) : array {
		$sql = sprintf(
			'SELECT * FROM inspect WHERE inspect_diary_id = %d',
			$inspectdiaryID

		);

		if ( $res = $this->Result( $sql)) {
			return $this->dtoSet( $res);

		}

		return [];

	}

	public function UpdateByID( $a, $id) {
		$a['updated'] = self::dbTimeStamp();
		return parent::UpdateByID( $a, $id);

	}

}