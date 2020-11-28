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
        <div class="col text-center" title="conflict"><i class="fa fa-exclamation"></i></div>
        <div class="col text-center" title="has home address"><i class="fa fa-address-card-o"></i></div>
        <div class="col text-center" title="has phone"><i class="fa fa-mobile"></i></div>
        <div class="col text-center" title="has email"><i class="fa fa-at"></i></div>
        <div class="col text-center" title="has comment"><i class="fa fa-sticky-note-o"></i></div>
        <div class="col text-center" title="has info"><i class="fa fa-info-circle"></i></div>
        <div class="col text-center" title="has task"><i class="fa fa-tasks"></i></div>
        <div class="col text-center" title="sms"><i class="fa fa-commenting"></i></div>
        <div class="col text-center" title="new seller lead">nsl</div>
        <div class="col text-center" title="buyer">buy</div>
        <div class="col text-center" title="neighbour">nbr</div>
        <div class="col text-center" title="interested party">ip</div>
        <div class="col-2 text-center" title="updated">update</div>
        <div class="col text-center" title="has info">user</div>

      </div>
    </div>

  </div>

  <?php foreach ($this->data->dtoSet as $dto) {
    $conflict = strtolower( trim( $dto->name)) != strtolower( trim( $dto->people_name));
    $mobileConflict = false;
    if ( $dto->people_mobile != '') {
      $mobiles = (array)preg_split( '/(,|#|\*|;|\+)/', preg_replace('/[^0-9,#\*;\+]/','', $dto->mobile));
      $mobileConflict = true;
      foreach ( $mobiles as $mobile) {
        if ( strings::CleanPhoneString( $mobile) == strings::CleanPhoneString( $dto->people_mobile)) {
          $mobileConflict = false;
          break;

        }

      }

    }

    $emailConflict = $dto->people_email != '' && strtolower( trim( $dto->email)) != strtolower( trim( $dto->people_email));
		?>

    <div class="form-row border-bottom"
      data-id="<?= $dto->id ?>"
      data-person_id="<?= $dto->person_id ?>"
      data-name="<?= htmlentities($dto->name) ?>"
      data-mobile="<?= strings::CleanPhoneString( $dto->mobile) ?>"
      data-fu_sms="<?= $dto->fu_sms ?>">
      <div class="col py-2"><?= $dto->name ?></div>
      <div class="d-none d-lg-block col-8">
        <div class="form-row">
          <div class="col text-center py-2"><?php
            if ( $conflict) {
              print '<i class="fa fa-user text-warning" title="name conflict"></i>';

            }
            elseif ( $mobileConflict) {
              print '<i class="fa fa-mobile text-warning" title="mobile conflict"></i>';

            }
            elseif ( $emailConflict) {
              print '<i class="fa fa-at text-warning" title="email conflict"></i>';

            }

          ?></div>
          <div class="col text-center py-2"><?php if ( $dto->home_address) print strings::html_tick ?></div>
          <div class="col text-center py-2"><?php if ( strings::isPhone( $dto->mobile)) print strings::html_tick ?></div>
          <div class="col text-center py-2"><?php if ( strings::isEmail( $dto->email)) print strings::html_tick ?></div>
          <div class="col text-center py-2"><?php if ( $dto->comment) print strings::html_tick ?></div>
          <div class="col text-center py-2"><?php if ( $dto->notes) print strings::html_tick ?></div>
          <div class="col text-center py-2"><?php if ( $dto->tasks) print strings::html_tick ?></div>
          <div class="col text-center py-2" sms><?php
            if ( $dto->fu_sms == 'com') {
              if ( $dto->fu_sms_bulk == 1)
                print '&bull;';
              else
                print '<i class="fa fa-star"></i>';

            }
            elseif ( $dto->fu_sms == 'yes') {
              if ( strings::isMobilePhone( $dto->mobile)) {
                printf(
                  '<div
                    data-role="sms"
                    data-id="%s"
                    data-mobile="%s"
                    data-name="%s"
                    data-property="%s">O</div>',
                  $dto->id,
                  $dto->mobile,
                  htmlentities( $dto->name),
                  htmlentities( $this->data->dto->address_street));

              }
              else {
                print '<i class="fa fa-triangle-exclamation"></i>';

              }

            }

          ?></div>
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

        _context.append( $('<a href="#" class="font-weight-bold">Open Inspection</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _row.trigger( 'view');
          _context.close();

        }));

        _context.append( $('<a href="#"><i class="fa fa-commenting-o"></i>sms</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _row.trigger('sms');
          _context.close();

        }));

        _context.append( $('<a href="#">view '+_data.name+'</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _row.trigger('person-edit');
          _context.close();

        }));

        if ( e.ctrlKey) {
          _context.append( '<hr>');
          _context.append( (() => {
            let ctrl = $('<a href="#">sms complete</a>');

            if ( 'com' == _data.fu_sms) {
              ctrl.prepend( '<i class="fa fa-check"></i>')
              ctrl.on( 'click', function( e) {
                e.stopPropagation();e.preventDefault();

                _row.trigger('sms-complete-undo');
                _context.close();

              });

            }
            else {
              ctrl.on( 'click', function( e) {
                e.stopPropagation();e.preventDefault();

                _row.trigger('sms-complete');
                _context.close();

              });


            }


            return ctrl;

          })());

        }

        _context.open( e);

      };

      _row
      .addClass( 'pointer')
      .on( 'person-edit', function(e) {
        let _row = $(this);
        let _data = _row.data();

        // console.log( _data);

        _.get.modal( _.url('<?= config::$INSPECTDIARY_ROUTE_PEOPLE ?>/edit/' + _data.person_id))
        .then( m => m.on( 'success', e => $(document).trigger( 'refresh-inspects')));

      })
      .on( 'view', function( e) {
        let _me = $(this);
        let _data = _me.data();

        // console.log( _data);

        $(document).trigger( 'view-inspection', _data.id);

      })
      .on( 'sms', function( e) {
        let _me = $(this);
        let _data = _me.data();

        // console.log( _data);

        /*--- -----------[ sms functionality ]----------- ---*/
        if ( !( String( _data.mobile).IsMobilePhone())) return;

        _.post({
          url : _.url('<?= $this->route ?>'),
          data : { action : 'get-sms-template' },

        }).then( d => {
          if ( 'ack' == d.response) {

            let msg = String(d.data).replace( /{address}/, <?= json_encode( $this->data->dto->address_street) ?>);

            _.get.modal( _.url('sms/dialog'))
            .then( modal => {
              modal.trigger( 'add.recipient', _data.mobile);
              $('textarea[name="message"]', modal)
              .val( msg).focus();

              modal.on( 'success', d => _me.trigger( 'sms-complete'));

            });

          } else { _.growl( d); }

        });
        /*--- -----------[ sms functionality ]----------- ---*/

      })
      .on( 'sms-complete', function( e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url: _.url( '<?= $this->route ?>'),
          data : {
            id : _data.id,
            action : 'sms-complete'

          }

        }).then( d => {
          _.growl( d);
          $('[sms]', _me).html( '<i class="fa fa-star"></i>');
          _me.data('fu_sms', 'com');

        })

      })
      .on( 'sms-complete-undo', function( e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url: _.url( '<?= $this->route ?>'),
          data : {
            id : _data.id,
            action : 'sms-complete-undo'

          }

        }).then( d => {
          _.growl( d);
          $('[sms]', _me).html( '');
          _me.data('fu_sms', '');

        })

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
