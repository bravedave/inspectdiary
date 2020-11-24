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

class inspect extends _dao {
	protected $_db_name = 'inspect';
	protected $template = '\dao\dto\inspect';
	protected $tmpDataSetName = '_tmp';
	protected $dataSource = 'inspect i
		LEFT JOIN (
			SELECT
				`date`, `property_id`, `time`, max( id) inspect_diary_id
			FROM
				inspect_diary
			WHERE
				`type` != "Inspect"
			GROUP BY
				`date` , `property_id` , `time`) d
		ON
			d.property_id = i.property_id
			AND CAST( i.`date` AS DATE) = CAST( d.`date` AS DATE)

		LEFT JOIN (
			SELECT
				MAX(id) id, inspect_id
			FROM
				property_diary
			WHERE
				inspect_id > 0 && follow_up = 0
			GROUP BY
				inspect_id) pd
		ON
			inspect_id = i.id';

	protected $dataFields = 'i.*, d.`time`, d.inspect_diary_id, pd.id pd_id';
	protected $dataOrder = 'ORDER BY `date` DESC, `time` ASC, `property_id` ASC, `id` ASC';
	protected $dataOrderReverse = 'ORDER BY `date` ASC, `time` DESC, `property_id` DESC, `id` DESC';

	public $debug = FALSE;
	//~ public $debug = TRUE;

	public $filterArchived = TRUE;	// by default hide archived records
	public $filterFuture = TRUE;	// by default hide future records
	public $filterToday = TRUE;	// by default hide todays records
	public $filterUser = 0;
	public $filterProperty = 0;
	public $filterDate = '';
	public $filterStart = '';
	public $filterEnd = '';
	public $filterSeed = FALSE;
	public $filterScope = '';

	public function exportCSV( $start, $end) {
		$sql = sprintf( 'SELECT * FROM inspect WHERE `date` BETWEEN "%s" AND "%s"', $start, $end);
		if ( $res = $this->Result( $sql)) {

			$csv = new \ParseCsv\Csv;
			if ( $dtoSet = $res->dtoSet()) {

				$data = [];
				foreach ( $dtoSet as $dto) {
					//~ unset( $dto->inspection);
					//~ unset( $dto->maintenance);
					//~ unset( $dto->sections);
					//~ unset( $dto->labels);

					//~ unset( $dto->sent_by);
					//~ unset( $dto->property_manager_id);

					//~ unset( $dto->textCount);
					//~ unset( $dto->imageCount);
					//~ unset( $dto->pdfDate);


					$data[] = (array)$dto;

				}

				$_keys = array_keys( (array)$dtoSet[0]);
				$keys = [];
				foreach( $_keys as $k => $v) {
					//~ if ( 'inspection' == $v) continue;
					//~ if ( 'maintenance' == $v) continue;
					//~ if ( 'sections' == $v) continue;
					//~ if ( 'labels' == $v) continue;

					//~ if ( 'sent_by' == $v) continue;
					//~ if ( 'property_manager_id' == $v) continue;

					//~ if ( 'textCount' == $v) continue;
					//~ if ( 'imageCount' == $v) continue;
					//~ if ( 'pdfDate' == $v) continue;

					$keys[] = $v;

				}

				//~ sys::dump( $_keys);

				$csv->titles = $_keys;
				$csv->data = $data;
				# Data to write:

			}

			$csv->output('open-home-inspections.csv');

		}

	}

	public function clearFilter() {
		$this->filterArchived = TRUE;	// by default hide archived records
		$this->filterFuture = TRUE;	// by default hide future records
		$this->filterToday = TRUE;	// by default hide todays records
		$this->filterUser = 0;
		$this->filterProperty = 0;
		$this->filterDate = '';
		$this->filterStart = '';
		$this->filterEnd = '';
		$this->filterSeed = FALSE;
		$this->filterScope = '';

	}

	public function setFilter( $param = NULL) {
		if ( !$this->filterSeed)
			$this->filterSeed = strtotime( 'this sunday');

		if ( (string)$param == 'lastweek') {
			$sunday = strtotime( 'last sunday', $this->filterSeed - 86400);
			$this->filterStart = date( 'Y-m-d', strtotime( 'last monday', $sunday));
			$this->filterEnd = date( 'Y-m-d', $sunday);

			//~ \sys::logger( sprintf( 'lastweek : %s :: %s : %s - %s', $this->filterSeed, date( 'Y-m-d', $sunday), $this->filterStart, $this->filterEnd));

		}
		elseif ( (string)$param == 'thisweek') {
			//~ if ( date('D', $this->filterSeed) == 'Mon') {
				//~ $this->filterStart = date( 'Y-m-d', $this->filterSeed);
				//~ $this->filterEnd = date( 'Y-m-d', strtotime( 'next sunday', $this->filterSeed));

			//~ }
			//~ else {
				$this->filterStart = date( 'Y-m-d', strtotime( 'last monday', $this->filterSeed));
				$this->filterEnd = date( 'Y-m-d', strtotime( 'this sunday', $this->filterSeed));

			//~ }

		}
		elseif ( (string)$param == 'nextweek') {
			$sunday = strtotime( 'next sunday', $this->filterSeed);
			$this->filterStart = date( 'Y-m-d', strtotime( 'last monday', $sunday));
			$this->filterEnd = date( 'Y-m-d', $sunday);

			//~ \sys::logger( sprintf( 'lastweek : %s :: %s : %s - %s', date( 'Y-m-d', $this->filterSeed), date( 'Y-m-d', $sunday), $this->filterStart, $this->filterEnd));

		}
		else {
			// today
			$this->filterStart = date( 'Y-m-d', strtotime( '-3 days', $this->filterSeed));
			$this->filterEnd = date( 'Y-m-d', strtotime( '+3 days', $this->filterSeed));

		}

		$this->filterScope = sprintf( '%s - %s',
			date( \config::$SHORTDATE_FORMAT, strtotime( $this->filterStart)),
			date( \config::$SHORTDATE_FORMAT, strtotime( $this->filterEnd)));

	}

	protected function filterBasic() {
		$debug = $this->debug;
		//~ $debug = TRUE;

		$where = array();
		if ( $this->filterArchived)
			$where[] = 'i.`archived` <> 1';

		if ( $this->filterUser)
			$where[] = sprintf( 'i.`user_id` = %d', $this->filterUser);

		if ( $this->filterProperty)
			$where[] = sprintf( 'i.`property_id` = %d', $this->filterProperty);

		if ( ( $ts = strtotime( $this->filterStart)) && ( $tf = strtotime( $this->filterEnd))) {
			//~ \sys::logger( sprintf( 'start, end => %s, %s', date( 'Y-m-d', strtotime( $this->filterStart)), $this->filterEnd));
			//~ \sys::logger( sprintf( 'start, end => %s, %s', date( 'Y-m-d', strtotime( $ts)), $this->filterEnd));
			$where[] = sprintf( 'i.`date` BETWEEN "%s" AND "%s"', date( 'Y-m-d', $ts), date( 'Y-m-d', $tf));

		}
		elseif ( $t = strtotime( $this->filterDate)) {
			$where[] = sprintf( 'i.`date` = "%s"', date( 'Y-m-d', $t));

		}
		elseif ( $this->filterFuture) {	// future is being filtered out
			if ( $this->filterToday) {	// today is being filtered out
				$where[] = sprintf( 'CAST(i.`date` AS DATE) < "%s"', date( 'Y-m-d'));

			}
			else {				// future is being filtered out, but not today - show today
				$where[] = sprintf( 'CAST(i.`date` AS DATE) <= "%s"', date( 'Y-m-d'));

			}

		}
		//~ elseif ( !$this->filterToday) {	// future is NOT being filtered out, but today is selected - just show today
			//~ $where[] = sprintf( 'CAST(`date` AS DATE) = "%s"', date( 'Y-m-d', strtotime('2017-07-08')));

		//~ }

		//~ if ( $debug) \sys::logger( sprintf( 'dao\inspect->filterBasic :: %s', implode( ' AND ', $where)));
		return ( $where);

	}

	public function getByID( $id) {
		/*
		* property2sell is read from the person record
		*/

		$debug = FALSE;
		//~ $debug = TRUE;

		if ( $dto = parent::getByID( $id)) {

			if ( $debug) \sys::logger( sprintf( 'dao\inspect->getByID( %d).', $id));

			if ( (int)$dto->person_id) {
				$peopleDAO = new people;
				if ( $peopleDTO = $peopleDAO->getByID( $dto->person_id)) {
					$dto->property2sell = $peopleDTO->property2sell;	// property2sell is read from the person record
				}

			}

			if ( $dto->type == 'OH Inspect') {
				if ( $debug) \sys::logger( sprintf( 'dao\inspect->getByID( %d) :: OH Inspect', $id));

				if ( $dto->property_id && strtotime( $dto->date) > 0) {
					//~ $inspect_diary = new inspect_diary;	// dao namespace
					$sql = sprintf( 'SELECT `id`, `date`, `property_id`, `time`, `inspect_id` FROM inspect_diary WHERE property_id = %d AND `date` = "%s"',
						$dto->property_id,
						$dto->date );

					$dto->sql = $sql;
					if ( $_res = $this->Result( $sql)) {
						if ( $_dto = $_res->dto()) {
							$dto->inspect_time = $_dto->time;
							$dto->inspect_diary_id = $_dto->id;

							if ( $_dto->inspect_id) {
								$dao = new inspect_diary;
								$dao->UpdateByID( ['inspect_id' => 0], $_dto->id);

							}

						}
						else {
							$a = [
								'created' => self::dbTimeStamp(),
								'updated' =>self::dbTimeStamp(),
								'date' => $dto->date,
								'time' => $dto->inspect_time,
								'property_id' => $dto->property_id,
								'type' => 'OH Inspect',
								'auto' => 1 ];

							$id = $this->db->Insert( 'inspect_diary', $a);
							$dto->inspect_diary_id = $id;

							//~ \sys::logger( 'we should add to inspect diary here !!!!');

						}

					}

				}

				$this->Q( sprintf( 'DELETE FROM inspect_diary WHERE inspect_id = %d', $dto->id));
				//~ \sys::logger( sprintf( 'i do this :: DELETE FROM inspect_diary WHERE inspect_id = %d', $dto->id));

			}
			else {
				if ( $debug) \sys::logger( sprintf( 'dao\inspect->getByID( %d) :: Inspect', $id));

				if ( $_res = $this->Result( sprintf( 'SELECT * FROM inspect_diary WHERE inspect_id = %d', $dto->id))) {
					if ( $_dto = $_res->dto()) {
						if ( $debug) \sys::logger( sprintf( 'dao\inspect->getByID( %d) :: Inspect :: found in diary ', $id));

						$dto->inspect_time = $_dto->time;
						$dto->inspect_diary_id = $_dto->id;
						//~ \sys::logger( 'found added to inspect diary here !');

					}
					else {
						$a = [
							'created' => self::dbTimeStamp(),
							'updated' =>self::dbTimeStamp(),
							'date' => $dto->date,
							'time' => $dto->inspect_time,
							'property_id' => $dto->property_id,
							'inspect_id' => $dto->id,
							'type' => 'Inspect',
							'auto' => 1 ];

						$id = $this->db->Insert( 'inspect_diary', $a);
						$dto->inspect_diary_id = $id;

						//~ \sys::logger( 'added to inspect diary here !');

					}

				}
				else { \sys::logger( 'could add to inspect diary here (2)'); }

			}

			return ( $dto);

		}

		return ( FALSE);

	}

	public function getAll( $fields = NULL, $order = NULL ) {
		if ( is_null( $this->_db_name))
			throw new \Exception( 'db_name is NULL' );

		if ( !$fields)
			$fields = $this->dataFields;
		if ( is_null( $order))
			$order = $this->dataOrder;

		$debug = $this->debug;
		//~ $debug = true;

		$this->db->log = $this->log;

		$where = $this->filterBasic();

		//~ $src = 'inspect i LEFT JOIN inspect_diary d ON d.property_id = i.property_id AND CAST( i.`date` AS DATE) = CAST( d.`date` AS DATE)';
		if ( count( $where))
			$sql = sprintf( 'SELECT %s FROM %s WHERE %s', $fields, $this->dataSource, implode( ' AND ', $where));
		else
			$sql = sprintf( 'SELECT %s FROM %s', $fields, $this->dataSource);

		$this->CreateTmpDataset( $sql);

		$sql = sprintf( 'SELECT * FROM %s %s', $this->tmpDataSetName, $order);
		if ( $debug) \sys::logSQL( $sql);

		return ( $this->Result( $sql));

	}

	public function getByPropertyDate( int $property, $date) {
		/*
		 * property2sell is read from the person record
		 *
		 * we are only interested in the OH Inspects,
		 * because we would ask for Inspects by ID
		 */

		$sql = sprintf( 'SELECT
				i.*,
				p.name people_name,
				p.mobile people_mobile,
				p.mobile2 people_mobile2,
				p.email people_email,
				p.email2 people_email2,
				p.property2sell _property2sell,
				u.name user_name
			FROM
				`inspect` i
				LEFT JOIN `users` u ON u.`id` = i.`user_id`
				LEFT JOIN `people` p ON i.person_id = p.id
			WHERE i.`type` = "OH Inspect"
				AND i.`property_id` = %d
				AND i.`date` = "%s"', $property, date( 'Y-m-d', strtotime( $date)));

		$this->Q( 'DROP TABLE IF EXISTS _tmp_inspect');
		$this->Q( sprintf( 'CREATE TEMPORARY TABLE _tmp_inspect AS ( %s)', $sql));

		$this->Q( 'UPDATE _tmp_inspect set property2sell = _property2sell');		// property2sell is read from the person record

		$this->Q( 'ALTER TABLE _tmp_inspect ADD COLUMN `offer_to_buy` DATE NULL DEFAULT "0000-00-00"');
		$this->Q( 'UPDATE _tmp_inspect p
			LEFT JOIN
				email_log e ON e.person_id = p.person_id
					AND e.property_id = p.property_id
					AND e.offer_to_buy = 1
			SET
				p.offer_to_buy = e.created
			WHERE
				e.person_id = p.person_id
					AND e.property_id = p.property_id
					AND e.offer_to_buy = 1;');

		//~ if ( $res = $this->Result( $sql))

		$this->Q( 'DROP TABLE IF EXISTS _db_tmp_inspect');
		//~ $this->Q( 'CREATE TABLE _db_tmp_inspect AS (SELECT * FROM _tmp_inspect)');

		if ( $res = $this->result( 'SELECT * FROM _tmp_inspect'))
			return ( $res->dtoSet());


		return ( FALSE);

	}

	public function getByInspectDiaryID( $id) {

		/*
		* property2sell is read from the person record
		*/

		if ( $resD = $this->Result( sprintf( 'SELECT inspect_id FROM inspect_diary WHERE id = %d', $id))) {
			if ( $dtoD = $resD->dto()) {
				// and we are only interested in the OH Inspects, because we would ask for Inspects by ID
				$sql = sprintf( 'SELECT
						i.*,
						p.name people_name,
						p.mobile people_mobile,
						p.mobile2 people_mobile2,
						p.email people_email,
						p.email2 people_email2,
						p.property2sell _property2sell ,
						u.name user_name
					FROM
						`inspect` i
						LEFT JOIN `users` u ON u.`id` = i.`user_id`
						LEFT JOIN `people` p ON i.person_id = p.id
					WHERE
						i.`id` = %d', $dtoD->inspect_id);

				$this->Q( 'DROP TABLE IF EXISTS _tmp_inspect');
				$this->Q( sprintf( 'CREATE TEMPORARY TABLE _tmp_inspect AS ( %s)', $sql));

				$this->Q( 'UPDATE _tmp_inspect set property2sell = _property2sell');		// property2sell is read from the person record

				$this->Q( 'ALTER TABLE _tmp_inspect ADD COLUMN `offer_to_buy` DATE NULL DEFAULT "0000-00-00"');
				$this->Q( 'UPDATE _tmp_inspect p
					LEFT JOIN
						email_log e ON e.person_id = p.person_id
							AND e.property_id = p.property_id
							AND e.offer_to_buy = 1
					SET
						p.offer_to_buy = e.created
					WHERE
						e.person_id = p.person_id
							AND e.property_id = p.property_id
							AND e.offer_to_buy = 1;');

				if ( $res = $this->result( 'SELECT * FROM _tmp_inspect'))
					return ( $res->dto());

			}

		}

		return ( FALSE);

	}

	public function getByIDofReportDataSet( $id) {
		$this->CreateTmpDataset( sprintf( 'SELECT %s FROM %s WHERE i.id = %d', $this->dataFields, $this->dataSource, $id));

		if ( $res = $this->Result( sprintf( 'SELECT * FROM %s LIMIT 1', $this->tmpDataSetName)))
			return ( $res->dto());

		return ( FALSE);

	}

	protected function CreateTmpDataset( $sql) {
		/*
		* property2sell is read from the person record
		*/

		$debug = $this->debug;
		//~ $debug = true;

		if ( $debug) \sys::logSQL( $sql);

		$this->Q( sprintf( 'DROP TABLE IF EXISTS %s', $this->tmpDataSetName));	//
		$this->Q( sprintf( 'CREATE TEMPORARY TABLE %s AS (%s)', $this->tmpDataSetName, $sql));
		$this->Q( sprintf( 'UPDATE %s t
		        LEFT JOIN
				inspect_diary d ON t.id = d.inspect_id
			SET
				t.inspect_diary_id = d.id,
				t.`time` = d.time, t.`inspect_time` = d.time WHERE t.`type` = "Inspect"', $this->tmpDataSetName));

		$this->db->Q( sprintf( 'ALTER TABLE %s
			ADD COLUMN `people_name` VARCHAR(100) NULL DEFAULT "",
			ADD COLUMN `people_mobile` VARCHAR(30) NULL DEFAULT "",
			ADD COLUMN `people_mobile2` VARCHAR(30) NULL DEFAULT "",
			ADD COLUMN `people_email` VARCHAR(100) NULL DEFAULT "",
			ADD COLUMN `people_email2` VARCHAR(100) NULL DEFAULT "",
			ADD COLUMN `user_name` VARCHAR(100) NULL DEFAULT ""', $this->tmpDataSetName));

		$this->Q( sprintf( 'UPDATE %s t
			LEFT JOIN
				people p ON t.person_id = p.id
					SET
						t.`people_name` = p.name,
						t.`people_mobile` = p.mobile,
						t.`people_mobile2` = p.mobile2,
						t.`people_email` = p.email,
						t.`people_email2` = p.email2,
						t.`property2sell` = p.property2sell', $this->tmpDataSetName));	// property2sell is read from the person record

		//~ sys::logger('yo');
		$this->Q( sprintf( 'UPDATE %s t
			LEFT JOIN
				users u ON t.user_id = u.id
					SET
						t.`user_name` = u.name', $this->tmpDataSetName));

		//~ $this->Q( 'DROP TABLE IF EXISTS __x');
		//~ $this->Q( sprintf( 'CREATE TABLE __x AS (SELECT * FROM %s)', $this->tmpDataSetName));

	}

	public function getFirst( $date, $id ) {
		if ( is_null( $this->_db_name))
			throw new \Exception( 'db_name is NULL' );

		$debug = $this->debug;
		//~ $debug = TRUE;
		if ( $debug) \sys::logger( 'dao\inspect->getFirst');

		$this->db->log = $this->log;

		$where = [
			sprintf( 'CAST( i.`date` AS DATE) = "%s"', $date),
			sprintf( 'i.`property_id` = %d', $id)];

		//~ $src = 'inspect i LEFT JOIN inspect_diary d ON d.property_id = i.property_id AND CAST( i.`date` AS DATE) = CAST( d.`date` AS DATE)';
		if ( count( $where))
			$sql = sprintf( 'SELECT %s FROM %s WHERE %s', $this->dataFields, $this->dataSource, implode( ' AND ', $where));
		else
			$sql = sprintf( 'SELECT %s FROM %s', $fields, $this->dataSource );


		$this->CreateTmpDataset( $sql);

		$sql = sprintf( 'SELECT * FROM %s %s LIMIT 1', $this->tmpDataSetName, $this->dataOrder);
		if ( $debug) \sys::logSQL( $sql);
		if ( $res = $this->Result( $sql)) {
			if ( $dto = $res->dto()) {
				return ( $dto);

			}
			else { if ( $debug) \sys::logger( ' row not found'); }

		}
		else { if ( $debug) \sys::logger( ' result not found'); }

		return ( FALSE);

	}

	public function getDates() {
		$debug = $this->debug;
		//~ $debug = TRUE;

		$sql = 'SELECT DISTINCT date FROM inspect WHERE archived = 0 ORDER by `date` DESC';
		if ( $debug) \sys::logSQL( $sql);
		if ( $res= $this->Result( $sql))
			return ( $res->dtoSet());

		return ( FALSE);

	}

	public function getNext( $id = 0) {
		$debug = $this->debug;
		//~ $debug = TRUE;

		if ( $id = (int)$id) {
			if ( $debug) \sys::logger( sprintf( 'dao\inspect->getNext :: looking for next > %d (filterUser = %d, filterProperty = %d)', $id, $this->filterUser, $this->filterProperty));

			$where = $this->filterBasic();
			if ( $res = $this->getAll()) {
				$first = FALSE;
				$next = FALSE;
				while ( $dto = $res->dto()) {
					if ( !$first)
						$first = $dto;

					if ( $next)
						return ( $dto);

					if ( $dto->id == $id)
						$next = TRUE;

				}

				if ( $next && $first)
					return ( $first);

			}

		}
		else { if ( $debug) \sys::logger( 'dao\inspect->getNext :: no id sent ?'); }

		/* so next must be the first */
		if ( $debug) \sys::logger( sprintf( 'dao\inspect->getNext :: looking for next > first (filterUser = %d, filterProperty = %d)', $id, $this->filterUser, $this->filterProperty));

		$where = $this->filterBasic();
		$where[] = 'i.id > 0';
		$sql = sprintf( 'SELECT %s FROM %s WHERE %s', $this->dataFields, $this->dataSource, implode( ' AND ', $where));
		if ( $debug) \sys::logger( sprintf( 'dao\inspect->getNext :: SQL :: %s', $sql));

		$this->CreateTmpDataset( $sql);

		$sql = sprintf( 'SELECT * FROM %s %s LIMIT 1', $this->tmpDataSetName, $this->dataOrder);

		if ( $res = $this->Result( $sql)) {
			if ( $dto = $res->dto())
				return ( $dto);

		}

		if ( $debug) \sys::logger( 'dao\inspect->getNext :: no next - go to empty dto');
		return ( dto\inspect::newinstance());

	}

	public function getPrevious( $id = 0) {
		$debug = $this->debug;
		//~ $debug = TRUE;

		if ( $id = (int)$id) {
			if ( $debug) \sys::logger( sprintf( 'dao\inspect->getPrevious :: looking for previous < %d (filterUser = %d, filterProperty = %d)', $id, $this->filterUser, $this->filterProperty));

			$where = $this->filterBasic();
			if ( $res = $this->getAll( NULL, $this->dataOrderReverse)) {
				$first = FALSE;
				$next = FALSE;
				while ( $dto = $res->dto()) {
					if ( !$first)
						$first = $dto;

					if ( $next)
						return ( $dto);

					if ( $dto->id == $id)
						$next = TRUE;

				}

				if ( $next && $first)
					return ( $first);

			}


		}

		/* so next must be the last */
		if ( $debug) \sys::logger( sprintf( 'dao\inspect->getPrevious :: looking for previous as last (filterUser = %d, filterProperty = %d)', $id, $this->filterUser, $this->filterProperty));

		$where = $this->filterBasic();
		if ( count( $where))
			$sql = sprintf( 'SELECT %s FROM %s WHERE %s', $this->dataFields, $this->dataSource, implode( ' AND ', $where));
		else
			$sql = sprintf( 'SELECT %s FROM %s', $this->dataFields, $this->dataSource);

		$this->CreateTmpDataset( $sql);

		$sql = sprintf( 'SELECT * FROM %s %s LIMIT 1', $this->tmpDataSetName, $this->dataOrderReverse);
		if ( $debug) \sys::logger( sprintf( 'dao\inspect->getPrevious :: SQL :: %s', $sql));
		if ( $res = $this->Result( $sql)) {
			if ( $dto = $res->dto())
				return ( $dto);

		}

		if ( $debug) \sys::logger( 'dao\inspect->getPrevious :: no previous - go to empty dto');
		return ( dto\inspect::newinstance());

	}

	public function getPositionOf( $dto) {
		$debug = $this->debug;
		//~ $debug = TRUE;

		/*
		 * Given the current filter, what number is this record of total
		 */

		$tot = 0;
		$pos = 0;
		$groupID = -1;
		$group = 0;
		$Group_pos = 0;
		$OHtot = 0;
		$OHpos = 0;
		$Itot = 0;
		$Ipos = 0;

		if ( $res = $this->getAll()) {
			$tot = $res->num_rows();
			$i = 0;
			while ( $_dto = $res->dto()) {
				$i ++;
				if ( $dto->id == $_dto->id) {
					if ( !$pos)
						$pos = $i;

				}

				if ( $_dto->property_id  && $groupID != $_dto->property_id) {
					$groupID = $_dto->property_id;
					$group++;

					if ( $debug) \sys::logger( sprintf( 'inspect->getPositionOf :: %d - %s (%d)', $group, $_dto->property_address, $groupID));

				}

				if ( $dto->id == $_dto->id) {
					if ( !$Group_pos)
						$Group_pos = $group;

				}


				if ( $dto->type == 'OH Inspect' && $dto->property_id  && $dto->property_id == $_dto->property_id ) {
					$OHtot ++;
					if ( $dto->id == $_dto->id) {
						if ( !$OHpos)
							$OHpos = $OHtot;

					}

					//~ \sys::logger( 'baby - your mine');

				}
				elseif ( $dto->type == 'Inspect'  && $_dto->type == 'Inspect' ) {
					$Itot ++;
					if ( $dto->id == $_dto->id) {
						if ( !$Ipos)
							$Ipos = $Itot;

					}


				}

				//~ if ( $pos && $OHpos)
					//~ break;

			}

		}

		$dto->position = ( $dto->type == 'OH Inspect' ? $OHpos : $Ipos);
		$dto->rowcount = ( $dto->type == 'OH Inspect' ? $OHtot : $Itot);
		$dto->group = $Group_pos;
		$dto->Gposition = $pos;
		$dto->Growcount = $tot;

	}

	public function getNotesForPerson( $id) {

		$sql = sprintf( 'SELECT
				id, date, type, notes
			FROM
				inspect
			WHERE
				person_id = %d AND notes != ""
			ORDER BY
				id DESC', $id);

		if ( $res = $this->Result( $sql)) {
			return ( $res->dtoSet());

		}

		return ( FALSE);

	}

}

