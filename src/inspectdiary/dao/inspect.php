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
		$timer = false;
		$timer = new \timer;

		$sql = sprintf(
			'SELECT
				i.*,
				p.name people_name,
				p.telephone people_telephone,
				p.mobile people_mobile,
				p.email people_email,
				u.name user_name
			FROM
				inspect i
				LEFT JOIN users u on u.id = i.user_id
				LEFT JOIN people p on p.id = i.person_id
			WHERE
				i.inspect_diary_id = %d',
			$inspectdiaryID

		);

		$this->Q( sprintf( 'CREATE TEMPORARY TABLE _t AS %s', $sql));
		$this->Q( 'ALTER TABLE _t ADD COLUMN `offer_to_buy` DATE NULL DEFAULT "0000-00-00"');

		if ( $timer) \sys::logger( sprintf('<%s - extract> %s', $timer->elapsed(), __METHOD__));


		if ( $this->db->table_exists('email_log')) {
			$this->Q( 'UPDATE _t
				LEFT JOIN
					email_log e ON e.person_id = _t.person_id
						AND e.property_id = _t.property_id
						AND e.offer_to_buy = 1
				SET
					_t.offer_to_buy = e.created
				WHERE
					e.person_id = _t.person_id
						AND e.property_id = _t.property_id
						AND e.offer_to_buy = 1'

			);

			if ( $timer) \sys::logger( sprintf('<%s - extract email> %s', $timer->elapsed(), __METHOD__));

		}

		if ( $res = $this->Result( 'SELECT * FROM _t ORDER BY id')) {
			return $this->dtoSet( $res);

		}

		return [];

	}

	public function UpdateByID( $a, $id) {
		$a['updated'] = self::dbTimeStamp();
		return parent::UpdateByID( $a, $id);

	}

}