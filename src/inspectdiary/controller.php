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

use currentUser;
use green;
use Json;
use Response;
use strings;

class controller extends \Controller {
  protected $viewPath = __DIR__ . '/views/';

  protected function _index() {

		$dao = new dao\inspect_diary;
		if ( $filter = $this->getParam( 'filter')) {
			setcookie('filter', $filter, [
				'expires' => time()+3600,
				'path' => '/',
				'domain' => '',
				'secure' => !\application::Request()->ServerIsLocal(),
				'httponly' => false,
				'samesite' => 'lax'

			]);

		}
		elseif ( isset( $_COOKIE['filter'])) {
			$filter = $_COOKIE['filter'];

		}

		if ( $seed = $this->getParam( 'seed')) {
			setcookie('seed', $seed, [
				'expires' => time()+3600,
				'path' => '/',
				'domain' => '',
				'secure' => !\application::Request()->ServerIsLocal(),
				'httponly' => false,
				'samesite' => 'lax'

			]);

		}
		elseif ( isset( $_COOKIE['seed'])) {
			$seed = $_COOKIE['seed'];

		}

		//~ $this->data = $dao->getAll();
		$this->data = $dao->getFiltered( $filter, $seed);
		// \sys::dump( $this->data);

    // 'primary' => 'bs-report',

    $this->render([
      'title' => $this->title = 'Inspect Home : ' . $this->data->scope,
      'primary' => 'report',
			'secondary' => [
        'index'
      ]

    ]);


    // 'scripts' => [ url::tostring('inspect/jsd?v=' . jslib::jsd_timestamp())]

  }

	protected function before() {
		config::inspectdiary_checkdatabase();
		parent::before();

  }

  protected function page( $params) {

    \dvc\pages\bootstrap::$primaryClass = str_replace( 'pt-3', 'pt-0 pt-md-3', \dvc\pages\bootstrap::$primaryClass);

    if ( !isset( $params['latescripts'])) $params['latescripts'] = [];
    $params['latescripts'][] = sprintf(
      '<script type="text/javascript" src="%s"></script>',
      strings::url( $this->route . '/js')

    );

		return parent::page( $params);

  }

	protected function postHandler() {
    $action = $this->getPost('action');

    if ( 'inspect-diary-save' == $action) {
			$a = [
				'type' => (string)$this->getPost('type'),
				'property_id' => (int)$this->getPost('property_id'),
				'date' => $this->getPost('date'),
				'time' => strings::HoursMinutes( $this->getPost('time')),
        'auto' => 0

      ];

			if ( $a['property_id'] > 0) {

        $id = (int)$this->getPost('id');
				$dao = new dao\inspect_diary;
				if ( $id > 0) {
					$dao->UpdateByID( $a, $id);

				}
				else {
					$id = $dao->Insert( $a);

				}

        if ( $pid = (int)$this->getPost('contact_id')) {
          if ( $dto = $dao->getById( $id)) {
            $aI = [
              'type' => $a['type'],
              'property_id' => $a['property_id'],
              'date' => $a['date'],
              'person_id' => $pid,
              'name' => $this->getPost('contact_name'),
              'mobile' => $this->getPost('contact_mobile'),
              'email' => $this->getPost('contact_email')

            ];

            if ( $dto->inspect_id) {
              if ( 'Inspect' == $a['type']) {
                $dao = new dao\inspect;
                if ( $dao->getByID( $dto->inspect_id)) {
                  $dao->UpdateByID( $aI, $dto->inspect_id);

                }
                else {
                  $dao = new dao\inspect;
                  $dto->inspect_id = $dao->Insert( $aI);

                  $dao = new dao\inspect_diary;
                  $dao->UpdateByID( ['inspect_id' => $dto->inspect_id], $dto->id);

                }

              }
              else {
                $dao = new dao\inspect_diary;
                $dao->UpdateByID( ['inspect_id' => 0]);

              }

            }
            else {
              if ( 'Inspect' == $a['type']) {
                $dao = new dao\inspect;
                $dto->inspect_id = $dao->Insert( $aI);

                $dao = new dao\inspect_diary;
                $dao->UpdateByID( ['inspect_id' => $dto->inspect_id], $dto->id);

              }

            }

          }

        }

        // \sys::logger( sprintf('<%s> %s', $action, __METHOD__));
        Json::ack( $action);

			}
			else {
				Json::nak( sprintf( 'invalid property : %s', $action));

      }

    }
    elseif ( 'inspection-save' == $action) {
      $a = [
        'type' => $this->getPost('type'),
        'person_id' => $this->getPost('person_id'),
        'property_id' => $this->getPost('property_id'),
        'name' => $this->getPost('name'),
        'mobile' => $this->getPost('mobile'),
        'email' => $this->getPost('email'),
        'comment' => $this->getPost('comment'),
        'notes' => $this->getPost('notes'),
        'tasks' => $this->getPost('tasks'),
        'fu_buyer' => $this->getPost('fu_buyer'),
        'fu_interested_party' => $this->getPost('fu_interested_party'),
        'fu_neighbour' => $this->getPost('fu_neighbour'),
        'fu_nsl' => $this->getPost('fu_nsl'),
        'property2sell' => $this->getPost('property2sell'),
        'user_id' => currentUser::id(),

      ];

      $dao = new dao\inspect;
      if ( $id = (int)$this->getPost('id')) {
        $dao->UpdateByID( $a, $id);

      }
      else {
        $a['inspect_diary_id'] = $this->getPost('inspect_diary_id');
        $id = $dao->Insert( $a);

      }

      Json::ack( $action)
        ->add( 'id', $id);

      // $dbc->defineField('date', 'date');
      // $dbc->defineField('inspect_time', 'varchar', 10);
      // $dbc->defineField('home_address', 'varchar', 100 );
      // $dbc->defineField('fu_info', 'varchar', 3 );
      // $dbc->defineField('fu_info_complete', 'datetime');
      // $dbc->defineField('fu_task', 'varchar', 3 );
      // $dbc->defineField('fu_task_complete', 'datetime');
      // $dbc->defineField('fu_sms', 'varchar', 3 );
      // $dbc->defineField('fu_sms_complete', 'datetime');
      // $dbc->defineField('fu_sms_bulk', 'tinyint' );
      // $dbc->defineField('email_sent', 'datetime');
      // $dbc->defineField('reminder', 'bigint' );

    }
    elseif ( 'search-people' == $action) {
      if ( $term = $this->getPost('term')) {

        green\search::$peopleFields[] = 'property2sell';

				Json::ack( $action)
					->add( 'term', $term)
					->add( 'data', green\search::people( $term));

			} else { Json::nak( $action); }

    }
    elseif ( 'search-properties' == $action) {
			if ( $term = $this->getPost('term')) {
				Json::ack( $action)
					->add( 'term', $term)
					->add( 'data', green\search::properties( $term));

			} else { Json::nak( $action); }

    }
    else {
      parent::postHandler();

    }

  }

	public function edit( $id = 0) {
		$this->data = (object)[
      'dto' => new dao\dto\inspect_diary,
      'title' => $this->title = 'New Diary Entry'
    ];

		if ( ( $id = (int)$id) || ( $clone = (int)$this->getParam('clone'))) {
			$dao = new dao\inspect_diary;
			if ( $id) {
        if ( $dto = $dao->getByID( $id)) {
          $dto = $dao->getDetail( $dto);

					$this->data->dto = $dto;
          $this->data->title = 'edit';

        }

			}
			elseif ( $clone) {
				if ( $dto = $dao->getByID( $clone)) {
					//~ $this->data = $dto;
					$this->data->dto->time = $dto->time;
					$this->data->dto->property_id = $dto->property_id;
					$this->data->dto->address_street = $dto->address_street;
          $this->data->dto->type = $dto->type;
          $this->data->dto->title = 'clone';

				}

			}

		}

    $this->load('edit');

  }

  public function inspection( $id = 0) {
    $this->data = (object)[
      'title' => $this->title = 'Add Inspection',
      'dto' => new dao\dto\inspect

    ];

    $dao = new dao\inspect;
		if ( $id = (int)$id) {
      if ( $dto = $dao->getByID( $id)) {
        $this->data->dto = $dto;

      }
    }
    else {
      $this->data->dto->inspect_diary_id = (int)$this->getParam( 'idid');

    }

    $this->data->dto = $dao->getDetail($this->data->dto);

    $this->load( 'inspection');

  }

  public function inspects( $inspectdiaryID = 0) {
    if ( $inspectdiaryID = (int)$inspectdiaryID) {
      $dao = new dao\inspect;

      $this->data = (object)[
        'dtoSet' => $dao->prendiIlDiario($inspectdiaryID)

      ];

      $this->load( 'report-for-diary-id');

    }

  }

  public function js( $lib = '') {
    $s = [];
    $r = [];

    $s[] = '@{{route}}@';
    $r[] = strings::url( $this->route);

    $js = \file_get_contents( __DIR__ . '/js/custom.js');
    $js = preg_replace( $s, $r, $js);

    Response::javascript_headers();
    print $js;

  }

}