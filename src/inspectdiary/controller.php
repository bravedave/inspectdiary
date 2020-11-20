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

use Json;
use green;
use strings;
use Response;

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

  protected function page( $params) {

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
    elseif ( 'search-people' == $action) {
			if ( $term = $this->getPost('term')) {
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