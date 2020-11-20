<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

class inspect_diary extends Controller {
	protected function posthandler() {
		$debug = TRUE;
		//~ $debug = FALSE;

		$format = 'redirect';
		$response = ['response' => 'ack',
			'description' => ''];

		$action = $this->getPost('action');
		//~ \sys::logger( sprintf( 'inspect_diary/posthandler :: %s', $action));
		if ( $action == 'list' || $action == 'list-next' || $action == 'list-previous' || $action == 'dates') {
			$format = 'json';
			$dao = new dao\inspect_diary;
				//~ $dao->debug = TRUE; sys::logger('inspect_diary/posthandler :: turned debug on');
				//~ $dao->populateFromInspect();

			if ( $action == 'list' || $action == 'list-next' || $action == 'list-previous' ) {
				$response['description'] = 'inspect diary list';
				$response['date'] = $this->getPost( 'date', date( 'Y-m-d'));
				if ( $action == 'list-next' || $action == 'list-previous' ) {
					if ( !$response['date'] )
						$response['date'] = date( 'Y-m-d');

					if ( $action == 'list-next')
						$response['date'] = date( 'Y-m-d', strtotime( 'tomorrow', strtotime($response['date'])));
					else
						$response['date'] = date( 'Y-m-d', strtotime( 'yesterday', strtotime($response['date'])));

				}

				if ( $debug) \sys::logger( sprintf( 'inspect_diary/postHandler :: %s :: %s', $action, $response['date']));

				if ( $response['date'] )
					$response['description'] = 'Inspect Diary ' . date( 'd M', strtotime( $response['date']));

				$response['data'] = [];
				if ( $res = $dao->getForDate( $response['date'])) {
					$inspectDAO = new dao\inspect;
					foreach ( $res as $r) {
						$t = strtotime( $r->date);
						if ( date('Y') == date( 'Y', $t))
							$r->date_formatted = date( 'jS', $t);
						else
							$r->date_formatted = date( \config::$DATE_FORMAT, $t);
						$r->time_formatted = strings::AMPM( $r->time, TRUE, FALSE);

						/*
							we want to know the first item for this date, for this property
							*/
						$r->firstID = 0;
						if ( $dtoFirst = $inspectDAO->getFirst( $r->date, $r->property_id))
							$r->firstID = $dtoFirst->id;
						//~ \sys::logger( sprintf( 'dao\inspect_diary->save :: date : %s, property : %s => %d', $r->date, $r->property_id, $r->firstID));


						$response['data'][] = $r;

					}

				}

			}
			elseif ( $action == 'dates') {
				$response['description'] = 'inspect diary dates';
				$response['data'] = [];
				if ( $res = $dao->getAllDates()) {
					foreach ( $res as $r) {
						$t = strtotime( $r->date);
						if ( date('Y') == date( 'Y', $t))
							$r->date_formatted = date( 'j M', $t);
						else
							$r->date_formatted = date( \config::$DATE_FORMAT, $t);

						$response['data'][] = $r;

					}

				}

			}

		}
		elseif ( $action == 'get') {
			if ( $id = $this->getPost('id')) {

				$inspect_diary = new dao\inspect_diary;
				if ( $dto = $inspect_diary->getByID( $id)) {
					$j = \Json::ack('ok');
					$dto->datetime = date( 'c', strtotime( sprintf( '%s %s', $dto->date, strings::AMPM( $dto->time))));
					$j->add('data', $dto);

				} else { \Json::nak('not found'); }
			} else { \Json::nak('invalid id'); }
			return;

		}
		elseif ( $action == 'opens') {
			$dao = new dao\inspect_diary;
			$daoProperties = new dao\properties;

			$d = [
				'lastweek' => $dao->getFiltered('lastweek'),
				'thisweek' => $dao->getFiltered('thisweek'),
				'nextweek' => $dao->getFiltered('nextweek'),
				'weekafternext' => $dao->getFiltered('weekafternext')
					];

			$p = [];
			foreach ( $d as $w) {
				foreach ( $w->data as $insp)
					$p[] = $insp->property_id;

			}

			$d['properties'] = [];
			$sql = sprintf( 'SELECT `id`, `address_street`, `street_index` FROM properties WHERE id IN (%s) ORDER BY `street_index`', implode(',', $p));
			//~ sys::logSQL( $sql);
			$secondPass = FALSE;
			if ( $res = $this->db->result( $sql))
				$d['properties'] = $res->dtoSet();

			/*
				Sometimes properties->street_index is blank,
				check and update
				and have another shot if required

				possible second pass to get right order
				*/
			foreach ( $d['properties'] as $p) {
				if ( $p->street_index == '') {
					$secondPass = TRUE;
					$daoProperties->UpdateByID( ['street_index' => \PropertyUtility::street_index( $p->address_street )], $p->id);

				}

			}
			reset( $d['properties']);

			if ( $secondPass) {
				\sys::logger( 'inspect_diary/opens :: second pass');
				if ( $res = $this->db->result( $sql))
					$d['properties'] = $res->dtoSet();

			}

			\Json::ack( 'opens this week')->add( 'data', $d);
			return;

		}
		elseif ( $action == 'delete') {
			$format = $this->getPost('format');
			if ( $id = (int)$this->getPost('id')) {
				$dao = new dao\inspect_diary;
				$dao->delete( $id);
				$response['description'] = 'deleted record';

			}
			else {
				$response['response'] = 'nak';
				$response['description'] = 'invalid id';

			}

		}
		else {
			$response['description'] = sprintf( 'cancelled (%s)', $action);

		}

		if ( $format == 'json')
			new Json($response);
		else
			Response::redirect( self::$url, $response['description']);

	}

	protected function _index() {
		$dao = new dao\inspect_diary;
		//~ $dao->debug = true;
		$dao->populateFromInspect();

		cms\theme::$layout = cms\theme::layout_9_3;

	}


	public function delete( $id = 0) {
		if ( $id = (int)$id) {
			$dao = new dao\inspect_diary;
			$dao->delete( $id);

			Response::redirect( self::$url, 'deleted record');

		}
		else {
			throw new Exceptions\RecordInvalid;

		}

	}

	public function opens() {

		$dao = new dao\inspect_diary;
			//~ $dao->debug = TRUE;
			$dao->populateFromInspect();

		$p = $this->render([
			'title' => $this->title = 'Open this Week',
			'primary' => 'openthisweek',
			'secondary' => 'bs-index',

		]);

	}

	public function ashome() {
		currentUser::option('inspect-home','inspect-diary');
		Response::redirect( 'inspect_diary');

	}

}

