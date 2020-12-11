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

use strings;

$dto = $this->data->dto;  ?>

<style>
  @media screen and ( max-width: 767px) {
    body > .navbar,
    [data-role="content-secondary"]
    {
      transition: opacity .30s linear;
      display : none;

    };

  }
</style>

<div class="row mb-2">
  <div class="col pt-2">
    <h5 class="my-0"><?= $dto->property_contact_name ?></h5>
    <div class="text-muted"><em>Property Contact</em></div>

  </div>

</div>

<div class="row mb-2">
  <div class="col">
    <div class="input-group">

      <input type="text" class="form-control" name="mobile" readonly
        value="<?= $dto->property_contact_mobile ?>">

      <div class="input-group-append">
        <div class="input-group-text"><i class="fa fa-phone"></i></div>

      </div>

      <div class="input-group-append">
        <div class="input-group-text"><i class="fa fa-commenting-o"></i></div>

      </div>

    </div>

  </div>

</div>

<div class="row mb-2">
  <div class="col">
    <div class="input-group">

      <input type="text" class="form-control" name="email" readonly
        value="<?= $dto->property_contact_email ?>">

      <div class="input-group-append">
        <div class="input-group-text"><i class="fa fa-paper-plane-o"></i></div>

      </div>

    </div>

  </div>

</div>

<div class="alert alert-warning">
  nothing works on this page yet ...

</div>