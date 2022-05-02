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

use green;

class users extends green\users\dao\users {
  public function getTeams(): array {

    $sql = 'SELECT
			`group`, `id`
		FROM
			users
		WHERE
			`group` != ""
			AND active
			AND name != ""';

    $ret = [];
    if ($res = $this->Result($sql)) {
      while ($dto = $res->dto()) {
        if (!isset($ret[$dto->group])) $ret[$dto->group] = [];
        $ret[$dto->group][] = $dto->id;
      }
    }

    return $ret;
  }
}
