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

use dao;

class users extends dao\users {
	public function getTeam( string $team = '', string $fields = 'id, name, email', string $order = 'ORDER BY name' ) : object {

    if ( (string)$team == '') {
      // return everyone
      $sql = sprintf(
        'SELECT %s FROM users WHERE active AND name != "" %s',
        $fields,
        $order

      );

      return ( $this->Result( $sql));

    }

    $sql = sprintf(
      'SELECT %s FROM users WHERE `group` = "%s" AND active AND name != "" %s',
      $fields,
      $this->escape( $team),
      $order

    );

		return ( $this->Result( $sql));

	}

}
