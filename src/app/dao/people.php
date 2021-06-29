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

class people extends green\people\dao\people {
  public function isProtectedField( $dto, string $fld) : bool {
    return false;

  }

}
