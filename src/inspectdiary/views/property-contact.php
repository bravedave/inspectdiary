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

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
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
      <h5 class="my-0"><?= \htmlentities( $dto->property_contact_name) ?></h5>
      <div class="text-muted"><em>Property Contact</em></div>

    </div>

  </div>

  <div class="row mb-2">
    <div class="col">
      <div class="input-group">

        <input type="text" class="form-control" name="mobile" readonly tabindex="-1"
          value="<?= $dto->property_contact_mobile ?>">

        <div class="input-group-append d-none" id="<?= $_uidCall = strings::rand() ?>">
          <button type="button" class="btn input-group-text"><i class="fa fa-phone"></i></button>

        </div>

        <div class="input-group-append d-none" id="<?= $_uidSMS = strings::rand() ?>">
          <button type="button" class="btn input-group-text"><i class="fa fa-commenting-o"></i></button>

        </div>

      </div>

    </div>

  </div>

  <div class="row mb-2">
    <div class="col">
      <div class="input-group">

        <input type="text" class="form-control" name="email" readonly tabindex="-1"
          value="<?= \htmlentities( $dto->property_contact_email) ?>">

        <div class="input-group-append d-none" id="<?= $_emailControl = strings::rand() ?>">
          <button type="button" class="btn input-group-text"><i class="fa fa-paper-plane-o"></i></button>

        </div>

      </div>

    </div>

  </div>

  <script>
  ( _ => $(document).ready( () => {
    let fld = $('input[name="email"]', '#<?= $_form ?>');
    let email = fld.val();

    if ( email.isEmail()) {
      $('#<?= $_emailControl ?> > button')
      .on( 'click', e => {
        e.stopPropagation();

        _.email.activate({
          to : _.email.rfc922({ name: <?= \json_encode($dto->property_contact_name) ?>, email: email}),
          subject : <?= \json_encode( $dto->address_street) ?>

        });

      });

      $('#<?= $_emailControl ?>').removeClass( 'd-none');

    }

    fld = $('input[name="mobile"]', '#<?= $_form ?>');
    let mobile = fld.val();

    if ( mobile.IsPhone()) {
      $('#<?= $_uidCall ?> >button')
      .on( 'click', e => {
        e.stopPropagation();

        window.location.href = 'tel:' + mobile.replace( /[^0-9]/, '');

      });

      $('#<?= $_uidCall ?>').removeClass( 'd-none');

    }

    if ( mobile.IsMobilePhone()) {
      $('#<?= $_uidSMS ?> > button')
      .on( 'click', e => {
        e.stopPropagation();

        _.get.sms()
        .then( modal => {
          modal.on( 'shown.bs.modal', e => $('textarea[name="message"]', modal).focus());
          modal.trigger('add.recipient', mobile);

        });

      });

      $('#<?= $_uidSMS ?>').removeClass( 'd-none');

    }

  }))( _brayworth_);
  </script>

</form>
