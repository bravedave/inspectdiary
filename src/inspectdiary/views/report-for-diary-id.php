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

use dvc\icon;
use strings;  ?>

<div id="<?= $_wrapper = strings::rand() ?>">

  <style>
    @media screen and ( max-width: 767px) {
      body > .navbar,
      [data-role="content-secondary"]
      { display : none };

    }
  </style>

  <?php foreach ($this->data->dtoSet as $dto) { ?>

    <div class="row border-bottom"
      data-id="<?= $dto->id ?>">
      <div class="col py-2"><?= $dto->name ?></div>

    </div>

  <?php } ?>

  <div class="row">
    <div class="col py-2 text-right">
      <button class="btn btn-primary" id="<?= $_addInspection = strings::rand() ?>"><?= icon::get( icon::person_plus ) ?></button>

    </div>

  </div>

</div>
<script>
  ( _ => $(document).ready( () => {
    $('#<?= $_addInspection ?>').on( 'click', e => {
      e.stopPropagation();
      $(document).trigger('add-inspection');

    });

    $('#<?= $_wrapper ?> > [data-id]').each( (i, row) => {
      let _row = $(row);

      _row
      .addClass( 'pointer')
      .on( 'view', function( e) {
        let _me = $(this);
        let _data = _me.data();

        // console.log( _data);

        $(document).trigger( 'view-inspection', _data.id);

      })
      .on( 'click', function( e) {
        e.stopPropagation();

        let _me = $(this);
        _me.trigger( 'view');

      })

    });

  }))( _brayworth_);
</script>

<div class="row">
  <div class="col">
    <?php
    //  \sys::dump( $this->data, null, false) ?>

  </div>

</div>

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
  <script>
  $(document).ready( () => {

    $('#<?= $_form ?>')
    .on( 'submit', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();
      let _modalBody = $('.modal-body', _form);

      // console.table( _data);

      return false;
    });
  });
  </script>
</form>
