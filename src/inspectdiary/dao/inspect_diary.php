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

use inspectdiary\config;
use dao\_dao;
use DateInterval;
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

				$end = new DateTime(
					sprintf(
						'%s %s',
						$dto->date,
						strings::AMPM( $dto->time)

					)

				);

				// $end->add(new DateInterval('PT30M'));

				// $end = new DateTime( sprintf( '%s 08:00:00', $dto->settlement_deposit_due));
				$start->setTimezone( new DateTimeZone('UTC'));
				$end->setTimezone( new DateTimeZone('UTC'));
				$type = $dto->type;
				$_type = strtolower( preg_replace( '@[^a-zA-Z0-9\.]@', '_', $dto->type));
				if ( config::inspectdiary_openhome == $dto->type) {
					$type = 'OH';

				}
				elseif ( config::inspectdiary_inspection == $dto->type) {
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
				$dto->property_contact_id = $_dto->people_id;

				// \sys::logger( sprintf('<%s %s> %s', $_dto->people_id, $_dto->address_street, __METHOD__));

			}

		}

		if ( $dto->property_contact_id) {

			// \sys::logger( sprintf('<%s> %s', $dto->property_contact_id, __METHOD__));

			$dao = new people;
			if ( $_dto = $dao->getByID($dto->property_contact_id)) {
				// \sys::logger( sprintf('<%s> %s', $_dto->name, __METHOD__));
				$dto->property_contact_name = $_dto->name;
				$dto->property_contact_mobile = $_dto->mobile;
				$dto->property_contact_email = $_dto->email;

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

		// if ( $debug) \sys::trace('inspect_diary->getFiltered :: logging SQL');
		if ( $debug) {
			\sys::logger( sprintf('<%s> %s', $filter, date( 'c', $seed), __METHOD__));

			\sys::logSQL( $sql);


		}

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

	public function getInspectionCount( dto\inspect_diary $dto) : int {
		$sql = sprintf(
			'SELECT count(*) tot FROM inspect WHERE `inspect_diary_id` = %d',
			$dto->id

		);

		if ( $_res = $this->Result( $sql)) {
			if ( $_dto = $_res->dto()) {
				return (int)$_dto->tot;

			}

		}

		return 0;

	}

	public function getInspectsForDate( string $date) : array {
		$sql = sprintf(
			'SELECT
				i.`id`,
				i.`date`,
				i.`time`,
				i.`type`,
				i.`property_id`,
				p.`address_street`
			FROM
				`inspect_diary` i
				LEFT JOIN `properties` p on p.`id` = i.`property_id`
			WHERE
				`date` = "%s" AND `type` = "%s"',
			$date,
			config::inspectdiary_openhome

		);

		if ( $res = $this->Result( $sql)) {
			return $res->dtoSet();

		}

		return [];

	}

	public function getInspectsOfFuture( string $date, int $property_id = 0) : array {
		$where = [
			sprintf( 'DATE( `date`) >= "%s"', $date),
			sprintf( '`type` = "%s"', config::inspectdiary_openhome)

		];

		if ( $property_id) {
			array_unshift( $where, sprintf( 'i.`property_id` = %s', $property_id));

		}

		$sql = sprintf(
			'SELECT
				i.`id`,
				i.`date`,
				i.`time`,
				i.`type`,
				i.`property_id`,
				p.`address_street`
			FROM
				`inspect_diary` i
				LEFT JOIN `properties` p on p.`id` = i.`property_id`
			WHERE %s',
			implode( ' AND ', $where)

		);

		// \sys::logSQL( sprintf('<%s> %s', $sql, __METHOD__));

		if ( $res = $this->Result( $sql)) {
			return $res->dtoSet();

		}

		return [];

	}

	public function getRange( $from, $to) : array {
		$sql = sprintf(
			'SELECT
				i.id,
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

	public function statistics( $dto) : object {
		$stats = (object)[
			'visitors' => 0,
			'visitors_requesting_documents' => 0,
			'documents' => 0,
			'ip' => 0,
			'text' => ''

		];

		$_dao = new inspect;
		$_dtoSet = $_dao->prendiIlDiario($dto->id);
		$stats->visitors = count( $_dtoSet);
		foreach ($_dtoSet as $_dto) {
			if ( $_dto->attachment_count) {
				$stats->visitors_requesting_documents ++;

			}
			$stats->documents += $_dto->attachment_count;
			if ( 'yes' == $_dto->fu_interested_party) {
				$stats->ip ++;

			}

		}


		$stats->text = sprintf(
			'<table style="width: auto;"><tbody>' .
			'<tr><td style="padding: 4px 8px 4px 0;">Visitors</td><td style="padding: 4px 8px;">%s</td></tr>' .
			'<tr><td style="padding: 4px 8px 4px 0;">Visitors Requesting Documents</td><td style="padding: 4px 8px;">%s</td></tr>' .
			'<tr><td style="padding: 4px 8px 4px 0;">Documents Sent</td><td style="padding: 4px 8px;">%s</td></tr>' .
			'<tr><td style="padding: 4px 8px 4px 0;">Interested</td><td style="padding: 4px 8px;">%s</td></tr>' .
			'</tbody></table>',
			$stats->visitors,
			$stats->visitors_requesting_documents,
			$stats->documents,
			$stats->ip

		);

		return $stats;

	}

	public function UpdateByID( $a, $id) {
		$a['updated'] = self::dbTimeStamp();
		return parent::UpdateByID( $a, $id);

	}

}