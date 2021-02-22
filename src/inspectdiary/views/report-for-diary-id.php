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
use strings;
use sys;

$emailLog = sys::dbi()->table_exists( 'email_log'); ?>

<div id="<?= $_wrapper = strings::rand() ?>">

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

  <div class="form-row d-none d-lg-flex border-bottom" id="<?= $_headline = strings::rand()  ?>">
    <div class="col">name</div>
    <div class="col-8">
      <div class="form-row">
        <div class="col text-center" title="conflict"><i class="bi bi-exclamation"></i></div>
        <div class="col d-none d-xl-block text-center" title="has home address"><i class="bi bi-person-lines-fill"></i></div>
        <div class="col d-none d-xl-block text-center" title="has phone"><?= icon::get( icon::phone ) ?></div>
        <div class="col d-none d-xl-block text-center" title="has email"><i class="bi bi-at"></i></div>
        <div class="col text-center" title="has comment"><i class="bi bi-sticky"></i></div>
        <div class="col text-center" title="has info"><i class="bi bi-info"></i></div>
        <div class="col text-center" title="has task"><i class="bi bi-list-task"></i></div>
        <div class="col text-center" title="reminder"><i class="bi bi-bell"></i></div>
        <div class="col text-center" title="sms"><?= icon::get( icon::chat_dots_fill ) ?></div>
        <?php if ( $emailLog) { ?>
        <div class="col text-center" title="documents sent"><i class="bi bi-file-text"></i></div>
        <div class="col text-center text-truncate" title="offer to buy">otb</div>
        <?php } // if ( $emailLog) ?>
        <div class="col text-center text-truncate" title="attend"><i class="bi bi-check-circle"></i></div>
        <div class="col text-center text-truncate" title="new seller lead">nsl</div>
        <div class="col text-center text-truncate" title="buyer">buy</div>
        <div class="col text-center text-truncate" title="neighbour">nbr</div>
        <div class="col text-center text-truncate" title="interested party">ip</div>
        <div class="col-2 d-none d-xl-block text-center text-truncate" title="updated">update</div>
        <div class="col d-none d-xl-block text-center text-truncate" title="user">user</div>

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
    $ip = 'yes' == $dto->fu_interested_party;
		?>

    <div class="form-row border-bottom"
      data-id="<?= $dto->id ?>"
      data-type="<?= $dto->type ?>"
      data-person_id="<?= $dto->person_id ?>"
      data-name=<?= json_encode( $dto->name, JSON_UNESCAPED_SLASHES)?>
      data-mobile="<?= strings::CleanPhoneString( $dto->mobile) ?>"
      data-email=<?= json_encode( $dto->email, JSON_UNESCAPED_SLASHES)?>
      data-fu_sms="<?= $dto->fu_sms ?>"
      data-has_reminder="<?= (int)$dto->reminder > 0 ? 'yes' : 'no' ?>">
      <div class="col py-2 <?php if ( $ip) print 'text-danger font-weight-bold'; ?>"><?= $dto->name ?></div>
      <div class="col-4 col-lg-8">
        <div class="form-row">
          <div class="col d-none d-sm-block text-center py-2"><?php
            if ( $conflict) {
              print '<i class="bi bi-person-fill text-warning" title="name conflict"></i>';

            }
            elseif ( $mobileConflict) {
              print '<i class="bi bi-phone text-warning" title="mobile conflict"></i>';

            }
            elseif ( $emailConflict) {
              print '<i class="bi bi-at text-warning" title="email conflict"></i>';

            }

          ?></div>
          <div class="col d-none d-xl-block text-center py-2"><?php if ( $dto->home_address) print strings::html_tick ?></div>
          <div class="col d-none d-xl-block text-center py-2"><?php if ( strings::isPhone( $dto->mobile)) print strings::html_tick ?></div>
          <div class="col d-none d-xl-block text-center py-2"><?php if ( strings::isEmail( $dto->email)) print strings::html_tick ?></div>
          <div class="col text-center py-2"><?php if ( $dto->comment) print 'C' ?></div>
          <div class="col d-none d-lg-block text-center py-2"><?php if ( $dto->notes) print strings::html_tick ?></div>
          <div class="col d-none d-lg-block text-center py-2"><?php if ( $dto->tasks) print strings::html_tick ?></div>
          <div class="col d-none d-lg-block text-center py-2"><?php if ( (int)$dto->reminder > 0) print strings::html_tick ?></div>
          <div class="col d-lg-none text-center py-2"><?= 'yes' == $dto->fu_attend ? '<i class="bi bi-check"></i>' : '' ?></div>
          <div class="col text-center py-2" sms><?php
            if ( $dto->fu_sms == 'com') {
              if ( $dto->fu_sms_bulk == 1)
                print '&bull;';
              else
                print '<i class="bi bi-star"></i>';

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
                print '<i class="bi bi-exclamation"></i>';

              }

            }

          ?></div>

          <?php if ( $emailLog) {
            printf(
              '<div class="col text-center py-2">%s</div>',
              $dto->attachment_count ? $dto->attachment_count : '&nbsp;'

            );

            if ( strtotime( $dto->offer_to_buy) > 0) {
              printf(
                '<div class="col d-none d-sm-block text-center py-2" title="%s"><i class="bi bi-patch-check"></i></div>',
                strings::asLocalDate( $dto->offer_to_buy)

              );

            }
            else {
              print '<div class="col d-none d-sm-block"></div>';

            }

          } // if ( $emailLog) ?>

          <div class="col d-none d-lg-block text-center text-truncate py-2"><?= $dto->fu_attend ?></div>
          <div class="col d-none d-lg-block text-center text-truncate py-2"><?= $dto->fu_nsl ?></div>
          <div class="col d-none d-lg-block text-center text-truncate py-2"><?= $dto->fu_buyer ?></div>
          <div class="col d-none d-lg-block text-center text-truncate py-2"><?= $dto->fu_neighbour ?></div>
          <div class="col d-none d-lg-block text-center text-truncate py-2"><?= $dto->fu_interested_party ?></div>
          <div class="col-2 d-none d-xl-block text-center text-truncate py-2"><?= strings::asShortDate( $dto->updated) ?></div>
          <div class="col d-none d-xl-block text-center"><?= \html::icon( $dto->user_name, $dto->user_name ) ?></div>

        </div>

      </div>

    </div>

  <?php } ?>

  <?php if ( $this->data->dto->property_contact_id) { ?>
    <div class="form-row mt-5"
      data-contact_id="<?= $this->data->dto->property_contact_id ?>"
      data-inspect_diary_id="<?= $this->data->dto->id ?>">
      <div class="col">
        <div class="text-muted">Property Contact</div>
        <?= $this->data->dto->property_contact_name ?>

      </div>

      <div class="col-auto" style="font-size: 2rem;">
        <?= icon::get( icon::chevronRight ) ?>

      </div>

    </div>

  <?php } ?>

</div>
<script>
  // window._cms_ = {
  //   property : {
  //     reminderAuto : ( parms) => {
  //       return new Promise( r => {
  //         console.log( parms);
  //         r();

  //       });

  //     }

  //   }

  // };

  ( _ => $(document).ready( () => {
    $('#<?= $_wrapper ?> > [data-id]').each( (i, row) => {
      let _row = $(row);

      let contextMenu = function(e) {
        if ( e.shiftKey)
          return;

        e.stopPropagation();e.preventDefault();

        _.hideContexts();

        let _row = $(this);
        let _data = _row.data();
        let _context = _.context();

        _context.append( $('<a href="#" class="font-weight-bold">Open Inspection</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _row.trigger( 'view');
          _context.close();

        }));

        if ( String( _data.mobile).IsMobilePhone()) {
          ( ctrl => {
            ctrl.addClass('d-none');
            _.get.sms.enabled().then( () => ctrl.removeClass( 'd-none'));

          })(_context.create( $('<a href="#"><i class="bi bi-chat-dots"></i>sms</a>').on( 'click', e => {
            e.stopPropagation();e.preventDefault();

            _row.trigger('sms');
            _context.close();

          })));

        }

        if ( String( _data.email).isEmail() && !!_.email.activate) {
          _context.append( $('<a href="#"><i class="bi bi-cursor"></i>email</a>').on( 'click', e => {
            e.stopPropagation();e.preventDefault();

            _row.trigger('email');
            _context.close();

          }));

        }

        _context.append( $('<a href="#">change inspection</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _row.trigger('change-inspection');
          _context.close();

        }));

        _context.append( $('<a href="#"><i class="bi bi-trash"></i>delete</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _row.trigger('delete-inspection');
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
              ctrl.prepend( '<i class="bi bi-check"></i>')
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
      .on( 'change-inspection', function( e) {
        e.stopPropagation();

        let _me = $(this);
        let _data = _me.data();

        $(document).trigger( 'change-inspection-of-inspect', _data.id);

      })
      .on( 'click', function( e) {
        e.stopPropagation();

        _.hideContexts();

        let _me = $(this);
        _me.trigger( 'view');

      })
      .on( 'contextmenu', contextMenu)
      .on( 'delete-inspection', function( e) {
        e.stopPropagation();

        let _me = $(this);
        let _data = _me.data();

				confirmDeleteAction().then( () => {
          deleteInspection( _data.id).then( response => {
            refreshPeople();
            $(document).trigger('invalidate-counts');

          });

        });

      })
      .on( 'email', function( e) {
        let _me = $(this);
        let _data = _me.data();

        // console.log( _data);

        _.email.activate({
          to : _.email.rfc922(_data.email),
          subject : <?= json_encode( $this->data->dto->address_street) ?>

        });

      })
      .on( 'person-edit', function(e) {
        let _row = $(this);
        let _data = _row.data();

        // console.log( _data);

        _.get.modal( _.url('<?= config::$INSPECTDIARY_ROUTE_PEOPLE ?>/edit/' + _data.person_id))
        .then( m => m.on( 'success', e => $(document).trigger( 'refresh-inspects')));

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

            _.get.sms().then( modal => {
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
          $('[sms]', _me).html( '<i class="bi bi-star"></i>');
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
      .on( 'view', function( e) {
        let _me = $(this);
        let _data = _me.data();

        // console.log( _data);

        viewInspection( _data.id);

      });

    });

    _.get.sms.enabled();  // .then( () => console.log( 'sms enabled...'));

    $('#<?= $_wrapper ?>')
    .on('send-sms-followup', function( e) {
      let ids = [];
      let mobiles = [];

      $('>[data-id]', this).each( (i, row) => {

        let _row = $(row);
        let _data = _row.data();

        if ( !/^(yes|com)$/.test( _data.fu_sms)) {
          if ( String(_data.mobile).IsMobilePhone()) {
            ids.push( _data.id);
            mobiles.push( _data.mobile);

          }

        }

      });

      if ( mobiles.length > 0) {
        _.post({
          url : _.url('<?= $this->route ?>'),
          data : { action : 'get-sms-template' },

        }).then( d => {
          if ( 'ack' == d.response) {

            let msg = String(d.data).replace( /{address}/, <?= json_encode( $this->data->dto->address_street) ?>);

            _.get.sms().then( modal => {
              $.each( mobiles, (i,m) => modal.trigger( 'add.recipient', m));
              $('textarea[name="message"]', modal)
              .val( msg).focus();

              // console.log( ids.join(','));

              modal.on( 'success', d => {
                _.post({
                  url: _.url( '<?= $this->route ?>'),
                  data : {
                    ids : ids.join(','),
                    action : 'sms-complete-bulk'

                  }

                }).then( d => {
                  _.growl( d);
                  $(document).trigger( 'refresh-inspects');

                })

              });

            });

          } else { _.growl( d); }

        });

      }
      else {
        _.get.modal( _.url('<?= $this->route ?>/nosmstosend'));

      }

    })
    .on('set-reminders', function( e) {
      // console.log( 'here');

      if ( !!window._cms_) {
        if ( !!window._cms_.property) {

          let _me = $(this);
          let c = 0;
          let f = () => {
            c--;
            if ( 0 == c) $(document).trigger( 'refresh-inspects');

          };

          let iSet = 0;
          $('>[data-id]', this).each( (i, row) => {

            let _row = $(row);
            let _data = _row.data();

            if ( 'no' == _data.has_reminder) {
              let parms = {
                person : () => {
                  return {
                    id : _data.person_id,
                    name : _data.name

                  };

                },
                property_id: <?= (int)$this->data->dto->property_id ?>,
                inspect_id: _data.id,
                inspect_type : 'Inspect' == _data.type ? 'insp' : 'oh',

              }

              c++;
              iSet ++;

              _cms_.property.reminderAuto( parms)
              .then( d => {
                _row.addClass( 'text-warning');
                f();

              });

            }

          });

          if ( 0 == iSet) {
            _.get.modal( _.url( '<?= $this->route ?>/noreminderstoset'));

          }

          return;

        }

      }

      console.log( '_cms_.reminders not available')

    });

    let headlineContext = function( e) {
      if ( e.shiftKey)
        return;

      e.stopPropagation();e.preventDefault();

      _.hideContexts();

      let _context = _.context();

      _context.append( $('<a href="#">set reminders</a>').on( 'click', function( e) {
        e.stopPropagation();e.preventDefault();

        $('#<?= $_wrapper ?>').trigger('set-reminders');
        _context.close();

      }));

      _context.append( $('<a href="#">send sms followup</a>').on( 'click', function( e) {
        e.stopPropagation();e.preventDefault();

        $('#<?= $_wrapper ?>').trigger('send-sms-followup');
        _context.close();

      }));

      _context.append( $('<a href="#"><i class="bi bi-pencil"></i>edit inspection</a>').on( 'click', e => {
        e.stopPropagation();e.preventDefault();

        _context.close();

        $(document).trigger( 'edit-inspection-by-id', <?= $this->data->dto->id ?>);

      }));

      <?php if ( !$this->data->dtoSet) {  ?>
        _context.append( '<hr>');
        _context.append( $('<a href="#"><i class="bi bi-trash"></i>delete inspection</a>').on( 'click', e => {
          e.stopPropagation();e.preventDefault();

          _context.close();

          $(document).trigger( 'delete-inspection-by-id', <?= $this->data->dto->id ?>);

        }));

      <?php } // if ( !$this->data->dtoSet)  ?>

      if ( /button/i.test( String( this.tagName))) {
        let target = $(this);
        let offsets = target.offset();
        let _e = {
          pageX : offsets.left,
          pageY : offsets.top + target.outerHeight(),
          target : e.target

        };

        _context.open( _e);

      }
      else {
        _context.open( e);

      }


    };

    $('#<?= $_headline ?>').on( 'contextmenu', headlineContext);
    setPeopleContext( headlineContext);

    $('#<?= $_wrapper ?> > [data-contact_id]').each( (i, row) => {
      let _row = $(row);

      _row
      .addClass( 'pointer')
      .on( 'click', function( e) {
        e.stopPropagation();

        _.hideContexts();

        let _me = $(this);
        let _data = _me.data();

        viewPropertyContact( _data.inspect_diary_id);

      });

    });

  }))( _brayworth_);
</script>
