<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace tests;

use dvc;
use inspectdiary;;

class feed extends dvc\service {
  public static function test() {
    $app = new self( \application::startDir());
    $app->_test();

  }

  protected function _test() {
    $from = date( 'Y-m-d', strtotime('-2 months'));
    $to = date( 'Y-m-d', strtotime('+2 months'));

    $dao = new inspectdiary\dao\inspect_diary;
    $cal = $dao->getCalendary( $from, $to);

    print_r( $cal);

  }

}