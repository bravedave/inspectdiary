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
				$dto->property_id = $_dto->property_id;
				$dto->type = $_dto->type;

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
		// $timer = new \timer;

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
		// if ( $timer) \sys::logSQL( $sql);

		$this->Q( 'ALTER TABLE _t ADD COLUMN `offer_to_buy` DATE NULL DEFAULT "0000-00-00"');
		$this->Q( 'ALTER TABLE _t ADD COLUMN `attachments` TEXT');
		$this->Q( 'ALTER TABLE _t ADD COLUMN `attachment_count` INT');

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

			if ( $res = $this->Result( 'SELECT * FROM _t')) {
				$res->dtoSet( function( $dto) {
					$_sql = sprintf('SELECT
							created, attachments
						FROM
							email_log
						WHERE
							person_id = %d AND property_id = %d',
						$dto->person_id,
						$dto->property_id

					);

					if ( $_res = $this->Result( $_sql)) {
						$_attachments = [];
						while ( $_dto = $_res->dto()) {
							if ( $_dto->attachments) {
								$_attachments = array_merge( $_attachments, explode( ',', $_dto->attachments));

							}

						}

					}

					if ( $_attachments) {
						// \sys::logger( sprintf('<%s> %s', count( $_attachments), __METHOD__));

						$this->db->Update(
							'_t',
							[
								'attachments' => implode( ',', $_attachments),
								'attachment_count' => count( $_attachments)

							],
							sprintf(
								'WHERE person_id = %d AND property_id = %d',
								$dto->person_id,
								$dto->property_id

							),
							$flushCache = false

						);

					}

				});
				if ( $timer) \sys::logger( sprintf('<%s - extract attachments> %s', $timer->elapsed(), __METHOD__));

			}

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