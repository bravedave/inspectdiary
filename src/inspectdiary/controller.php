<?php
/**
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace inspectdiary;

use db;
use currentUser;
use green;
use Json;
use Response;
use strings;
use sys;

class controller extends \Controller {
  protected $viewPath = __DIR__ . '/views/';

  protected $theme = [
    'navbar' => 'navbar navbar-dark bg-primary',
    'navbutton' => 'btn-primary',

  ];

  // protected $theme = [
  //   'navbar' => 'bg-light navbar-light',
  //   'navbutton' => 'btn-light',

  // ];

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


    $primary = [ 'report' ];
    $secondary = [
      'index',
      'index-templates'

    ];

    if ( currentUser::option( 'inspect-interface-modern')) {
      /** cleanup old key */
      currentUser::option( 'inspect-interface-modern','');

    }

    if ( config::$INSPECTDIARY_DEVELOPER ) {
      $secondary[] = 'index-developer';

    }

    $this->render([
      'title' => $this->title = 'Inspect : ' . $this->data->scope,
      'primary' => $primary,
			'secondary' => $secondary

    ]);

  }

	protected function before() {
		config::inspectdiary_checkdatabase();
    parent::before();

    if ( \class_exists('cms\theme')) {
      $this->theme['navbar'] = \cms\theme::navbar(['defaults' => 'navbar', 'sticky' => '']);
      $this->theme['navbutton'] = \cms\theme::navbutton();

    }

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

		if ( 'change-inspection-of-inspect' == $action) {
      if ( $id = $this->getPost('id')) {
        $a = [
          'inspect_diary_id' => $this->getPost('inspect_diary_id')

        ];

        $dao = new dao\inspect;
        $dao->UpdateByID( $a, $id);

        Json::ack( $action);

      } else { Json::nak( $action); }

    }
		elseif ( 'email-sent' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\inspect;
        if ( $dto = $dao->getByID( $id)) {

          $a = [ 'email_sent' => db::dbTimeStamp()];
          if ( 'yes' == $dto->fu_sms) $a['fu_sms'] = '';

          $dao->UpdateByID( $a, $id);
          Json::ack( $action);

        } else { Json::nak( $action); }

      } else { \Json::nak( $action); }

    }
    elseif ( 'get-by-id' == $action) {
      /*
        ( _ => {
          _.post({
            url : _.url('inspectdiary'),
            data : {
              action : 'get-by-id',
              id : 4
            },

          }).then( d => console.log( d));

        }) (_brayworth_);
       */

      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\inspect_diary;
        if ( $dto = $dao->getByID( $id)) {

          $dto = $dao->getDetail( $dto);
          $dto->shortdate = strings::asShortDate( $dto->date);
          $time = strings::AMPM( $dto->time);
          if ( preg_match( '@[0-9][0-9]:00@', $dto->time)) {
            $dto->shorttime = $time;

          }
          else {
            $dto->shorttime = preg_replace( '@(am|pm)$@i', '', $time);

          }
          $dto->pretty_street = strings::GoodStreetString( $dto->address_street);

          Json::ack( $action)
            ->add( 'data', $dto);

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
    elseif ( 'get-person-by-id' == $action) {
      /*
        ( _ => {
          _.post({
            url : _.url('inspectdiary'),
            data : {
              action : 'get-person-by-id',
              id : 1
            },

          }).then( d => console.log( d));

        }) (_brayworth_);
       */

      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\people;
        if ( $dto = $dao->getByID( $id)) {
          Json::ack( $action)
            ->add( 'data', $dto);

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
    elseif ( 'get-property-by-id' == $action) {
      /*
        ( _ => {
          _.post({
            url : _.url('inspectdiary'),
            data : {
              action : 'get-property-by-id',
              id : 1
            },

          }).then( d => console.log( d));

        }) (_brayworth_);
       */

      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\properties;
        if ( $dto = $dao->getByID( $id)) {
          Json::ack( $action)
            ->add( 'data', $dto);

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
		elseif ( 'get-sms-template' == $action) {
      Json::ack( $action)
        ->add( 'data', currentUser::option( 'inspect-sms-template'));

		}
		elseif ( 'save-owner-report-template' == $action) {
      $text = $this->getPost('text');
      sys::option('inspect-owner-report-template', $text);

      Json::ack( $action);

		}
		elseif ( 'save-sms-template' == $action) {
      $text = $this->getPost('text');
      currentUser::option('inspect-sms-template', $text);

      Json::ack( $action);

		}
		elseif ( 'sms-complete' == $action) {
      if ( $id = (int)$this->getPost( 'id')) {
        $a = [
          'fu_sms' => 'com',
          'fu_sms_complete' => db::dbTimeStamp()
        ];

        $dao = new dao\inspect;
        $dao->UpdateByID( $a, $id);

        Json::ack( $action);

      } else { \Json::nak( $action); }

    }
		elseif ( 'sms-complete-bulk' == $action) {
      $a = [
        'fu_sms' => 'com',
        'fu_sms_bulk' => 1,
        'fu_sms_complete' => db::dbTimeStamp()
      ];

      if ( $ids = $this->getPost('ids')) {
        $ids = explode( ',', $ids);

        $dao = new dao\inspect;
        foreach ($ids as $id) {
          if ( $id = (int)$id) {
            \sys::logger( sprintf('<%s> %s', $id, __METHOD__));
            $dao->UpdateByID( $a, $id);

          }

        }
      }

      Json::ack( $action);

    }
		elseif ( 'sms-complete-undo' == $action) {
      if ( $id = (int)$this->getPost( 'id')) {
        $a = [
          'fu_sms' => '',
          'fu_sms_complete' => db::dbTimeStamp()
        ];

        $dao = new dao\inspect;
        $dao->UpdateByID( $a, $id);

        Json::ack( $action);

      } else { \Json::nak( $action); }

    }
    elseif ( 'inspect-diary-delete' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\inspect_diary;
        if ( $dto = $dao->getByID( $id)) {
          if ( 'Inspect' == $dto->type) {
            $count = $dao->getInspectionCount( $dto);
            $dao->Q( sprintf( 'DELETE FROM inspect WHERE inspect_diary_id = %d', $dto->id));
            \sys::logger( sprintf('<there were %s inspects> %s', $count, __METHOD__));
            $dao->delete( $dto->id);
            Json::ack( $action);

          }
          else {
            $count = $dao->getInspectionCount( $dto);
            if ( $count) {
              Json::nak( $action);

            }
            else {
              $dao->delete( $dto->id);
              Json::ack( $action);

            }

          }

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
    elseif ( 'inspect-diary-save' == $action) {
			$a = [
        'property_id' => (int)$this->getPost('property_id'),
				'date' => $this->getPost('date'),
				'time' => strings::HoursMinutes( $this->getPost('time')),
        'auto' => 0

      ];

      if ( $type = (string)$this->getPost('type')) {
        $a['type'] = $type;

      }

			if ( $a['property_id'] > 0) {
        $id = (int)$this->getPost('id');
				$dao = new dao\inspect_diary;
				if ( $id > 0) {
					$dao->UpdateByID( $a, $id);

				}
				else {
					$id = $dao->Insert( $a);

				}

        if ( $dto = $dao->getById( $id)) {
          $qp = QuickPerson::find([
            'name' => $this->getPost('contact_name'),
            'mobile' => $this->getPost('contact_mobile'),
            'email' => $this->getPost('contact_email'),

          ]);

          $pid = (int)$this->getPost('contact_id');
          if ( !$pid) {
            if ( $qp->id) {
              $pid = $qp->id;

            }

          }

          $aI = [
            'type' => $dto->type,
            'property_id' => $a['property_id'],
            'date' => $a['date'],
            'person_id' => $pid,
            'name' => $this->getPost('contact_name'),
            'mobile' => $this->getPost('contact_mobile'),
            'email' => $this->getPost('contact_email'),
            'inspect_diary_id' => $id

          ];

          if ( $dto->inspect_id) {
            if ( 'Inspect' == $dto->type) {
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
              $dao->UpdateByID( ['inspect_id' => 0], $id);
              \sys::logger( sprintf('<%s> %s', 'cleared - not Inspect', __METHOD__));


            }

          }
          else {
            if ( 'Inspect' == $dto->type) {
              $dao = new dao\inspect;
              $dto->inspect_id = $dao->Insert( $aI);

              $dao = new dao\inspect_diary;
              $dao->UpdateByID( ['inspect_id' => $dto->inspect_id], $dto->id);

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
    elseif ( 'inspection-delete' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\inspect;
        $dao->delete( $id);
        Json::ack( $action);

      } else { Json::nak( $action); }

    }
    elseif ( 'inspection-save' == $action) {
      $a = [
        'type' => $this->getPost('type'),
        'property_id' => $this->getPost('property_id'),
        'name' => $this->getPost('name'),
        'mobile' => strings::cleanPhoneString( $this->getPost('mobile')),
        'email' => $this->getPost('email'),
        'comment' => $this->getPost('comment'),
        'notes' => $this->getPost('notes'),
        'tasks' => $this->getPost('tasks'),
        'fu_buyer' => $this->getPost('fu_buyer'),
        'fu_interested_party' => $this->getPost('fu_interested_party'),
        'fu_neighbour' => $this->getPost('fu_neighbour'),
        'fu_nsl' => $this->getPost('fu_nsl'),
        'fu_attend' => $this->getPost('fu_attend'),
        'property2sell' => $this->getPost('property2sell'),
        'user_id' => currentUser::id(),

      ];

      $qp = QuickPerson::find([
        'name' => $this->getPost('name'),
        'mobile' => $this->getPost('mobile'),
        'email' => $this->getPost('email'),

        ]

      );

      if ( $a['name'] != $qp->name && strtolower( $a['name']) == strtolower( $qp->name)) {
        $a['name'] = $qp->name;

      }

      $pid = (int)$this->getPost('person_id');
      if ( !$pid) {
        if ( $qp->id) {
          $pid = $qp->id;

        }

      }

      $a['person_id'] = $pid;

      $dao = new dao\inspect;
      if ( $id = (int)$this->getPost('id')) {
        $dao->UpdateByID( $a, $id);

      }
      else {
        $a['inspect_diary_id'] = $this->getPost('inspect_diary_id');
        $id = $dao->Insert( $a);

      }

      if ( $pid) {
        $dao = new dao\people;
        $dao->UpdateByID([
          'property2sell' => $this->getPost('property2sell')

        ], $pid);

      }

      Json::ack( $action)
        ->add( 'id', $id)
        ->add( 'name', $a['name']);

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
    elseif ( 'set-inspect-interface' == $action) {
			currentUser::option( 'inspect-interface', $this->getPost('value'));
      Json::ack( $action);

    }
    elseif ( 'update-person-email' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        if ( $email = $this->getPost('email')) {
          $dao = new dao\people;
          $dao->UpdateByID([
            'email' => $email

          ], $id);

          Json::ack( $action);

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
    elseif ( 'update-person-mobile' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        if ( $mobile = $this->getPost('mobile')) {
          $dao = new dao\people;
          $dao->UpdateByID([
            'mobile' => $mobile

          ], $id);

          Json::ack( $action);

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
    elseif ( 'update-person-mobile2' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        if ( $mobile = $this->getPost('mobile')) {
          $dao = new dao\people;
          $dao->UpdateByID([
            'mobile2' => $mobile

          ], $id);

          Json::ack( $action);

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
    elseif ( 'update-person-name' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        if ( $name = $this->getPost('name')) {
          $dao = new dao\people;
          $dao->UpdateByID([
            'name' => $name

          ], $id);

          Json::ack( $action);

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
    else {
      parent::postHandler();

    }

  }

  /*

    public function calendar() {
      $from = $this->getParam( 'from');
      if ( !$from || strtotime( $from) < 1) {
        $from = date( 'Y-m-d', strtotime('-2 months'));

      }

      $to = $this->getParam( 'to');
      if ( !$to || strtotime( $to) < 1) {
        $to = date( 'Y-m-d', strtotime('+2 months'));

      }

      $dao = new dao\inspect_diary;
      $cal = $dao->getCalendary( $from, $to);

      // Response::headers('text/plain');
      Response::headers('text/calendar');
      header( sprintf( 'Content-disposition: inline; name="%s"; filename="%s.ics"',
        \config::$WEBNAME,
        strtolower( preg_replace( '@[^a-zA-Z0-9\.]@', '_', \config::$WEBNAME))
        ));

      print $cal;


    }

  */

	public function changeInspectionofInspect( $id = 0) {

    $dao = new dao\inspect;
		if ( $id = (int)$id) {
      if ( $dto = $dao->getByID( $id)) {
        $dto = $dao->getDetail( $dto);
        if ( $dto->inspect_diary_id) {
          $dao = new dao\inspect_diary;
          if ( $dtoID = $dao->getByID( $dto->inspect_diary_id)) {
            $this->data = (object)[
              'title' => $this->title = 'Change Inspection',
              'dto' => $dto,
              'inspects' => $dao->getInspectsForDate( $dtoID->date)

            ];

            $this->load('change-Inspection-of-Inspect');

          } else { $this->load('inspect-diary-not-found'); }

        } else { $this->load('inspect-diary-not-found'); }

      } else { $this->load('inspect-not-found'); }

    } else { $this->load('inspect-not-found'); }

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
          $this->data->title = $this->title = 'Edit Diary Entry';

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

  public function editTemplate() {
    $template = $this->getParam( 't');
    if ( 'ownerreport' == $template) {
      $action = 'save-owner-report-template';
      $text = sys::option( 'inspect-owner-report-template');
      $template = 'owner-report';
      $title = $this->title = 'Owner Report Template';

    }
    else {
      $action = 'save-sms-template';
      $text = currentUser::option( 'inspect-sms-template');
      $template = 'sms';
      $title = $this->title = 'Edit SMS Template';

    }

    $this->data = (object)[
      'action' => $action,
      'text' => $text,
      'template' => $template,
      'title' => $title

    ];

    $this->load( 'template-editor');

  }

  public function inspection( $id = 0) {
    $this->data = (object)[
      'title' => $this->title = 'Add Inspection',
      'dto' => new dao\dto\inspect

    ];

    $dao = new dao\inspect;
		if ( $id = (int)$id) {
      if ( $dto = $dao->getByID( $id)) {
        $this->data->title = $this->title = 'Edit Inspection';
        $this->data->dto = $dto;

      }

    }
    else {
      if ( $idid = (int)$this->getParam( 'idid')) {
        $this->data->dto->inspect_diary_id = $idid;

      }

    }

    $this->data->dto = $dao->getDetail($this->data->dto);
    $this->load( 'inspection');

  }

  public function inspects( $inspectdiaryID = 0) {
    if ( $inspectdiaryID = (int)$inspectdiaryID) {
      $dao = new dao\inspect;

      $this->data = (object)[
        'dto' => false,
        'dtoSet' => $dao->prendiIlDiario($inspectdiaryID)

      ];

      $dao = new dao\inspect_diary;
      if ( $dto = $dao->getByID($inspectdiaryID)) {
        $this->data->dto = $dao->getDetail( $dto);

      }

      // \sys::dump( $this->data, null, false);return;
      $this->load( 'report-for-diary-id');

    }

  }

  public function noinspectoninspect() {
    $this->load( 'no-inspect-on-inspect');

  }

  public function noreminderstoset() {
    $this->load( 'no-reminders-to-set');

  }

  public function nosmstosend() {
    $this->load( 'no-sms-to-send');

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

  public function openThisWeek() {
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
      foreach ( $w->data as $insp) {
        $p[] = $insp->property_id;

      }

    }

    $d['properties'] = [];
    $sql = sprintf(
      'SELECT `id`, `address_street`, `street_index`
      FROM properties
      WHERE id IN (%s)
      ORDER BY `street_index`',
      implode(',', $p)

    );
    //~ sys::logSQL( $sql);
    $secondPass = false;
    if ( $res = $this->db->result( $sql)) {
      $d['properties'] = $res->dtoSet();

    }

    /*
      Sometimes properties->street_index is blank,
      check and update
      and have another shot if required

      possible second pass to get right order
      */
    foreach ( $d['properties'] as $p) {
      if ( $p->street_index == '') {
        $secondPass = true;
        $daoProperties->UpdateByID( ['street_index' => strings::street_index( $p->address_street )], $p->id);

      }

    }
    // reset( $d['properties']);

    if ( $secondPass) {
      \sys::logger( 'inspect_diary/opens :: second pass');
      if ( $res = $this->db->result( $sql))
        $d['properties'] = $res->dtoSet();

    }

    $this->data = (object)[
      'title' => $this->title = 'Open This Week',
      'inspectdata' => $d

    ];

    // $this->load( 'dump'); return;
    $this->load( 'openThisWeek');

  }

  public function propertycontact( $id = 0) {
    if ( $id = (int)$id) {

      // \sys::logger( sprintf('<%s> %s', $id, __METHOD__));

      $dao = new dao\inspect_diary;
      if ( $dto = $dao->getByID( $id)) {

        // \sys::logger( sprintf('<%s> %s', $dto->id, __METHOD__));

        $dto = $dao->getDetail( $dto);
        $stats = $dao->statistics( $dto);
        $text = strings::text2html( sys::option( 'inspect-owner-report-template'));
        $search = [
          '@{address}@',
          '@{stats}@',


        ];

        $replace = [
          $dto->address_street,
          $stats->text

        ];

        $this->data = (object)[
          'title' => $this->title = 'Property Contact',
          'dto' => $dto,
          'stats' => $stats,
          'report' => preg_replace( $search, $replace, $text)

        ];

        $this->load( 'property-contact');

      }
      else {
        $this->load( 'inspection-not-found');

      }

    }
    else {
      $this->load( 'inspection-not-found');

    }

  }

}
