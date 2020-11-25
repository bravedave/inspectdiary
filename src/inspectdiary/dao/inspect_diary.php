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
use DateTime;
use DateTimeZone;
use strings;

use Sabre\VObject\Component\VCalendar;

class inspect_diary extends _dao {
	protected $_db_name = 'inspect_diary';
	protected $template = __NAMESPACE__ . '\dto\inspect_diary';

	public $debug = false;

	const fields = 'id.id, id.date, id.time, id.type, id.property_id, id.inspect_id, p.address_street,
		i.person_id contact_id, i.name contact_name, i.mobile contact_mobile, i.email contact_email';

	public function getCalendary( $from, $to) : string {
		$vcal = new  VCalendar;
		$vcal->add('REFRESH-INTERVAL;VALUE=DURATION','PT5M');
		$vcal->add('X-PUBLISHED-TTL','PT5M');

		if ( $dtoSet = $this->getRange( $from, $to)) {
			foreach ($dtoSet as $dto) {
				$start = new DateTime(
					sprintf(
						'%s %s',
						$dto->date,
						strings::AMPM( $dto->time)

					)

				);

				// $end = new DateTime( sprintf( '%s 08:00:00', $dto->settlement_deposit_due));
				$end = $start;
				$start->setTimezone( new DateTimeZone('UTC'));
				$end->setTimezone( new DateTimeZone('UTC'));
				$type = $dto->type;
				$_type = strtolower( preg_replace( '@[^a-zA-Z0-9\.]@', '_', $dto->type));
				if ( 'OH Inspect' == $dto->type) {
					$type = 'OH';

				}
				elseif ( 'Inspect' == $dto->type) {
					$type = 'Insp';

				}

				$vevent = $vcal->add( 'VEVENT', [
					'SUMMARY' => sprintf( '%s - %s', $type, $dto->address_street),
					'UID' => sprintf( '%s-%d@cmss.darcy.com.au', $_type, $dto->id),
					'DTSTART' => $start,
					'DTEND'   => $end

				]);

			}

		}

		return $vcal->serialize();

	}

	public function getDetail( $dto) {
		if ( $dto->inspect_id) {
			$dao = new inspect;
			if ( $_dto = $dao->getByID($dto->inspect_id)) {
				$dto->contact_id = $_dto->person_id;
				$dto->contact_name = $_dto->name;
				$dto->contact_mobile = $_dto->mobile;
				$dto->contact_email = $_dto->email;

			}

		}

		if ( $dto->property_id) {
			$dao = new properties;
			if ( $_dto = $dao->getByID($dto->property_id)) {
				$dto->address_street = $_dto->address_street;

			}

		}

		return $dto;

	}

	public function getFiltered(
		$filter = '',
		$seed = '',
		$fields = self::fields,
		$order = 'ORDER BY `date` ASC, `time` ASC, property_id ASC'

		) {

		$debug = $this->debug;
		// $debug = true;

		if ( ( $seed = strtotime( $seed)) <= 0)
			$seed = time();

		$dEnd = strtotime( 'this Friday', $seed);
		if ( $filter == 'lastweek') {
			$dEnd = strtotime( 'last Friday', $dEnd);

		}
		elseif ( $filter == 'nextweek') {
			// \sys::logger( sprintf('<seed %s> %s', date( 'Y-m-d', $seed), __METHOD__));
			// \sys::logger( sprintf('<this Friday %s> %s', date( 'Y-m-d', $dEnd), __METHOD__));
			$dEnd = strtotime( 'next Friday', $dEnd);
			// \sys::logger( sprintf('<Next Friday %s> %s', date( 'Y-m-d', $dEnd), __METHOD__));

		}
		elseif ( $filter == 'weekafternext') {
			$dEnd = strtotime( 'next Friday', strtotime( 'next Friday', $dEnd));

		}
		else {
			$filter = 'thisweek';

		}

		$dStart = strtotime( 'last Saturday', $dEnd);
		$where = sprintf( 'id.date BETWEEN "%s" AND "%s"', date('Y-m-d', $dStart), date('Y-m-d', $dEnd));

		$sql = sprintf( 'SELECT
				%s
			FROM
				inspect_diary id
			LEFT JOIN
				properties p ON p.id = id.property_id
			LEFT JOIN
				inspect i ON i.id = id.inspect_id
			WHERE %s', $fields, $where);

		if ( $debug) \sys::trace('inspect_diary->getFiltered :: logging SQL');
		if ( $debug) \sys::logSQL( $sql);

		$this->Q( 'DROP TABLE IF EXISTS _tmp');
		$this->Q( 'DROP TABLE IF EXISTS _tmp2');
		$this->Q( 'DROP TABLE IF EXISTS _tmp3');
		$this->Q( $_sql = sprintf( 'CREATE TEMPORARY TABLE _tmp AS %s', $sql));
		//~ \sys::logSQL( $_sql);

		$this->Q( $_sql = 'CREATE TEMPORARY TABLE _tmp3 AS
			SELECT DISTINCT
				`date`, `property_id`
			FROM
				_tmp');

		$this->Q( $_sql = 'CREATE TEMPORARY TABLE _tmp2 AS SELECT
				d.*,
				COUNT(*) `count`
			FROM
				_tmp3 d
				LEFT JOIN
					inspect i ON i.date = d.date
						AND i.property_id = d.property_id
			WHERE i.NAME <> ""
			GROUP BY
				d.date, d.property_id');

		//~ \sys::logSQL( $_sql);

		$this->Q( 'ALTER TABLE _tmp ADD COLUMN inspections INT');
		// $this->Q( 'UPDATE
		// 	_tmp SET `inspections` =
		// 			(SELECT
		// 				count
		// 			FROM
		// 				_tmp2
		// 			WHERE
		// 				_tmp2.`date` = _tmp.date
		// 				AND _tmp2.property_id = _tmp.property_id)');
		$this->Q( 'UPDATE
			_tmp SET `inspections` =
					(SELECT
						count(*)
					FROM
						inspect
					WHERE
						inspect.`inspect_diary_id` = _tmp.id)');

		if ( $res = $this->Result( sprintf( 'SELECT * FROM _tmp %s', $order)))
			return ( (object)[
				'scopeS' => sprintf( '%s - %s', $dStart, $dEnd),
				'scope' => sprintf( '%s - %s', \strings::asShortDate( date( 'Y-m-d', $dStart)), \strings::asShortDate( date( 'Y-m-d', $dEnd))),
				'filter' => $filter,
				'seed' => date('Y-m-d', $dEnd),
				'data' => $res->dtoSet()
				]);

		return ( false);

	}

	public function getRange( $from, $to) : array {
		$sql = sprintf(
			'SELECT
				i.date,
				i.time,
				i.type,
				p.address_street
			FROM `%s` i
				LEFT JOIN
				properties p ON p.id = i.property_id
			WHERE i.`date` BETWEEN "%s" AND "%s"',
			$this->db_name(),
			$from, $to

		);

		// \sys::logger( sprintf('<%s> %s', $sql, __METHOD__));


		if ( $res = $this->Result( $sql)) {
			return $this->dtoSet( $res);

		}

		return [];

	}

	public function Insert( $a) {
		$a[ 'created'] = $a['updated'] = self::dbTimeStamp();
		return parent::Insert( $a);

	}

	public function UpdateByID( $a, $id) {
		$a['updated'] = self::dbTimeStamp();
		return parent::UpdateByID( $a, $id);

	}

}