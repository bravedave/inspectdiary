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

  <div class="form-row d-none d-lg-flex border-bottom">
    <div class="col">name</div>
    <div class="col-8">
      <div class="form-row">
        <div class="col text-center" title="has home address"><i class="fa fa-address-card-o"></i></div>
        <div class="col text-center" title="has phone"><i class="fa fa-mobile"></i></div>
        <div class="col text-center" title="has email"><i class="fa fa-at"></i></div>
        <div class="col text-center" title="has comment"><i class="fa fa-sticky-note-o"></i></div>
        <div class="col text-center" title="has info"><i class="fa fa-info-circle"></i></div>
        <div class="col text-center" title="new seller lead">nsl</div>
        <div class="col text-center" title="buyer">buy</div>
        <div class="col text-center" title="neighbour">nbr</div>
        <div class="col text-center" title="interested party">ip</div>
        <div class="col-2 text-center" title="updated">update</div>
        <div class="col text-center" title="has info">user</div>

      </div>
    </div>

  </div>

  <?php foreach ($this->data->dtoSet as $dto) { ?>

    <div class="form-row border-bottom"
      data-id="<?= $dto->id ?>"
      data-name="<?= htmlentities($dto->name) ?>">
      <div class="col py-2"><?= $dto->name ?></div>
      <div class="d-none d-lg-block col-8">
        <div class="form-row">
          <div class="col text-center py-2"><?php if ( $dto->home_address) print strings::html_tick ?></div>
          <div class="col text-center py-2"><?php if ( strings::isPhone( $dto->mobile)) print strings::html_tick ?></div>
          <div class="col text-center py-2"><?php if ( strings::isEmail( $dto->email)) print strings::html_tick ?></div>
          <div class="col text-center py-2"><?php if ( $dto->comment) print strings::html_tick ?></div>
          <div class="col text-center py-2"><?php if ( $dto->notes) print strings::html_tick ?></div>
          <div class="col text-center py-2"><?= $dto->fu_nsl ?></div>
          <div class="col text-center py-2"><?= $dto->fu_buyer ?></div>
          <div class="col text-center py-2"><?= $dto->fu_neighbour ?></div>
          <div class="col text-center py-2"><?= $dto->fu_interested_party ?></div>
          <div class="col-2 text-center py-2"><?= strings::asShortDate( $dto->updated) ?></div>
          <div class="col text-center"><?= \html::icon( $dto->user_name, $dto->user_name ) ?></div>

        </div>

      </div>

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

      let contextMenu = function(e) {
        if ( e.shiftKey)
          return;

        e.stopPropagation();e.preventDefault();

        _brayworth_.hideContexts();

        let _row = $(this);
        let _data = _row.data();
        let _context = _brayworth_.context();

        _context.append( $('<a href="#">view '+_data.name+'</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _row.trigger('person-edit');
          _context.close();

        }));

        _context.open( e);

      };

      _row
      .addClass( 'pointer')
      .on( 'person-edit', function(e) {
        let _row = $(this);
        let _data = _row.data();

        _.get.modal( _.url('<?= config::$INSPECTDIARY_ROUTE_PEOPLE ?>/edit/' + _data.id))
        .then( m => m.on( 'success', e => window.location.reload()));

      })
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
      .on( 'contextmenu', contextMenu);

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
