<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dao;

class inspect_diary extends _dao {
	protected static $populateFromInspect_hasRun = false;
	static $populateFromInspect_DoNotRun = false;

	public function getAll( $fields = self::fields, $order = 'ORDER BY `date` DESC, `time` ASC') {
		$debug = $this->debug;
		//~ $debug = TRUE;

		$sql = sprintf( 'SELECT
				%s
			FROM
				inspect_diary id
			LEFT JOIN
				properties p ON p.id = id.property_id
			LEFT JOIN
				inspect i ON i.id = id.inspect_id
			LEFT JOIN
				property_diary pd ON pd.inspect_diary_id = id.id
				%s', $fields, $order);

		if ( $debug) \sys::logSQL( $sql);
		if ( $res = $this->Result( $sql))
			return ( $res->dtoSet());

		return ( FALSE);

	}

	public function getForDate( $date) {
		$debug = $this->debug;
		//~ $debug = true;

		$fields = self::fields;

		$sql = sprintf( 'SELECT
				%s,
				CASE
					WHEN id.type = "OH Inspect" then inspects.i
				ELSE ""
				END count
			FROM
				inspect_diary id
			LEFT JOIN
				properties p ON p.id = id.property_id
			LEFT JOIN
				inspect i ON i.id = id.inspect_id
			LEFT JOIN
				property_diary pd ON pd.inspect_diary_id = id.id
			LEFT JOIN
				(SELECT
					property_id, COUNT(*) i
				FROM
					inspect
				WHERE
					date = "%s"
						AND type = "OH Inspect"
				GROUP BY property_id)
					inspects ON inspects.property_id = id.property_id
			WHERE id.`date` = "%s"
			ORDER BY `date` desc, `time` desc', $fields, $date, $date);

		if ( $this->debug) \sys::logger('dao\inspect_diary->getForDate');
		if ( $this->debug) \sys::logSQL( $sql);

		if ( $res = $this->Result( $sql))
			return ( $res->dtoSet());


		return ( FALSE);

	}

	public function getByID( $id) {
		//~ id.id, id.date, id.time, id.property_id, p.address_street
		if ( $res = $this->Result( sprintf( '
			SELECT
				%s FROM inspect_diary id
			LEFT JOIN
				properties p ON p.id = id.property_id
			LEFT JOIN
				inspect i ON i.id = id.inspect_id
			LEFT JOIN
				property_diary pd ON pd.inspect_diary_id = id.id
			WHERE id.id = %d', self::fields, $id))) {

			if ( $dto = $res->dto())
				return ( $dto);

		}

		return ( FALSE);

	}

	public function populateFromInspect() {
		$debug = false;
		//~ $debug = true;

		if ( self::$populateFromInspect_hasRun)
			return;	// don't piss me round - dickhead, once per load max !

		if ( self::$populateFromInspect_DoNotRun) {
			\sys::logger( 'inspect_diary->populateFromInspect() : got DoNotRun');
			return;	// probably from inspect App

		}

		self::$populateFromInspect_hasRun = TRUE;
		if ( $debug) \sys::logger( 'inspect_diary->populateFromInspect()');

		$sql = 'SELECT
				d.id
			FROM
				inspect_diary d
				LEFT JOIN
					inspect i ON i.id = d.inspect_id
			WHERE
				d.inspect_id > 0 AND d.type = "Inspect"
				AND d.auto = 1
				AND (i.id IS NULL OR d.type != i.type)';

		$stale = [];
		if ( $res = $this->Result( $sql)) {
			while ( $dto = $res->dto())
				$stale[] = $dto->id;

			if ( count( $stale)) {
				$this->Q( sprintf( 'DELETE FROM inspect_diary WHERE id in ( %s)', implode( ',', $stale)));
				\sys::logger( 'stale cleanup');

			}

		}


		$this->Q( <<<QUERY
INSERT INTO inspect_diary(`created`,`updated`,`date`,`time`,`property_id`,`type`,`auto`)
	SELECT
		CURRENT_TIMESTAMP created,
		CURRENT_TIMESTAMP updated,
		pd.date,
		'09:00',
		pd.property_id,
		'OH Inspect',
		1 auto
	FROM
		(SELECT DISTINCT
			date, property_id
		FROM
			inspect
		WHERE
			type = 'OH Inspect'
		) pd
		LEFT JOIN
			inspect_diary id ON pd.date = id.date
				AND pd.property_id = id.property_id
	WHERE
		id.id IS NULL

QUERY
		);

		$this->Q( <<<QUERY
INSERT INTO inspect_diary(`created`,`updated`,`date`,`time`,`property_id`,`inspect_id`,`type`,`auto`)
	SELECT
		CURRENT_TIMESTAMP `created`,
		CURRENT_TIMESTAMP `updated`,
		pd.date `date`,
		CONCAT(HOUR(pd.`created`), ':', MINUTE(pd.`created`)) `time`,
		pd.property_id,
		pd.id `inspect_id`,
		'Inspect',
		1 `auto`
	FROM
		(SELECT
			date, property_id, id, created
		FROM
			inspect
		WHERE
			type = 'Inspect' AND archived = 0
		) pd
	        LEFT JOIN
			inspect_diary id ON pd.id = id.inspect_id
	WHERE
		id.id IS NULL

QUERY
		);

	}

	public function getAllDates() {
		if ( $res = $this->Result( 'SELECT date, COUNT(*) "count" FROM inspect_diary GROUP BY date ORDER BY date DESC'))
			return ( $res->dtoSet());

		return ( FALSE);

	}

}

