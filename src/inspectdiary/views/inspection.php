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

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
  <input type="hidden" name="action" value="inspection-save">
  <input type="hidden" name="id" value="<?= $dto->id ?>">
  <input type="hidden" name="type" value="<?= $dto->type ?>">
  <input type="hidden" name="date" value="<?= $dto->date ?>">
  <input type="hidden" name="inspect_time" value="<?= $dto->inspect_time ?>">
  <input type="hidden" name="person_id" value="<?= $dto->person_id ?>">
  <input type="hidden" name="property_id" value="<?= $dto->property_id ?>">
  <input type="hidden" name="property_address" value="<?= $dto->address_street ?>">
  <input type="hidden" name="inspect_diary_id" value="<?= $dto->inspect_diary_id ?>">

  <div class="collapse fade" id="<?= $_collapseDocs = strings::rand() ?>">
    <div class="container-fluid mb-2 border-bottom">
      <div class="row">
        <div class="col">
          <h6 class="mt-1 mb-0">Library</h6>

        </div>

        <div class="col-auto">
          <button type="button" class='close' data-toggle="collapse" data-target="#<?= $_collapseDocs ?>">&times;</button>

        </div>

      </div>

    </div>

    <div class="row">
      <div class="col" id="<?= $_collapseDocs ?>content">&nbsp;</div>

    </div>

  </div>

  <div class="form-row row mb-2"><!-- name -->
    <label class="d-none d-md-block col-md-3 col-form-label" for="<?= $_uid = strings::rand() ?>" >Contact</label>

    <div class="col">
      <div class="input-group">
        <input type="text" name="name" class="form-control" placeholder="name" autocomplete="off"
          id="<?= $_uid ?>"
          value="<?= $dto->name ?>">

        <div class="input-group-append" id="<?= $_LinkedContactControl = strings::rand() ?>">
          <div class="input-group-text">
            <i class="bi bi-link text-<?=  $dto->person_id ? 'success' : 'danger' ?>"></i>

          </div>

        </div>

      </div>

    </div>

  </div>

  <div class="form-row row mb-2 d-none" id="<?= $_uid = strings::rand() ?>_row"><!-- confliction name -->
    <label class="d-none d-md-block col-md-3 col-form-label font-italic" for="<?= $_uid ?>" >file name</label>

    <div class="col">
      <div class="input-group">
        <input type="text" class="form-control bg-warning" readonly id="<?= $_uid ?>">

        <div class="input-group-append" id="<?= $_UseNameControl = strings::rand() ?>">
          <div class="input-group-text">
            <i class="bi bi-chevron-double-up"></i>

          </div>

        </div>

        <div class="input-group-append" id="<?= $_UpdatePerson = strings::rand() ?>">
          <div class="input-group-text">
            <i class="bi bi-chevron-double-down"></i>

          </div>

        </div>

      </div>

    </div>

  </div>
  <script>
  ( _ => {
    $('#<?= $_UseNameControl ?>').on( 'click', function( e) {
      e.stopPropagation();e.preventDefault();

      $('input[name="name"]', '#<?= $_form ?>').val( $('#<?= $_uid ?>').val());

      $('#<?= $_form ?>').trigger( 'save');

    });

    $('#<?= $_UpdatePerson ?>').on( 'click', function( e) {
      e.stopPropagation();e.preventDefault();

      let id = Number( $('input[name="person_id"]', '#<?= $_form ?>').val());
      if ( id > 0) {
        let name = String( $('input[name="name"]', '#<?= $_form ?>').val()).trim();
        if ( '' == name) {
          _.ask.alert({ title : 'Error', text : 'name cannot be empty'});

        }
        else {

          _.post({
            url : _.url('<?= $this->route ?>'),
            data : {
              action : 'update-person-name',
              id : $('input[name="person_id"]', '#<?= $_form ?>').val(),
              name : name

            },

          })
          .then( d => {
            _.growl( d);
            $('#<?= $_form ?>').trigger( 'check-conflicts');

          });

        }

      }
      else {
        _.ask.alert({ title : 'Error', text : 'invalid person'});

      }

    });

    $('#<?= $_form ?>').on( 'check-conflicts', function(e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();

      if ( Number( _data.person_id) > 0) {
        _.post({
          url : _.url('<?= $this->route ?>'),
          data : {
            id : _data.person_id,
            action : 'get-person-by-id'

          },

        }).then( d => {
          if ( 'ack' == d.response) {
            $('#<?= $_uid ?>').val( d.data.name);

            if ( String( d.data.name).toLowerCase() != String( _data.name).toLowerCase()) {
              $('#<?= $_uid ?>_row').removeClass( 'd-none');

            }
            else {
              $('#<?= $_uid ?>_row').addClass( 'd-none');

            }

          }
          else {
            _.growl( d);

          }

        });

      }
      else {
        $('#<?= $_uid ?>_row').addClass( 'd-none');

      }

    });

  }) (_brayworth_);
  </script>

  <div class="form-row row mb-2"><!-- mobile -->
    <div class="offset-md-3 col">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <?= icon::get( icon::phone) ?>

          </div>

        </div>

        <input type="text" name="mobile" class="form-control" placeholder="phone" autocomplete="off"
          value="<?= $dto->mobile ?>">

        <div class="input-group-append">
          <button type="button" class="btn input-group-text d-none" sendsms>
            <i class="bi bi-chat-dots"></i>

          </button>

        </div>

        <div class="input-group-append" >
          <button type="button" class="btn input-group-text d-none" phonecall>
            <i class="bi bi-telephone"></i>

          </button>

        </div>

      </div>

    </div>

  </div>

  <div class="form-row row mb-2"><!-- email -->
    <div class="offset-md-3 col">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="bi bi-at"></i></div>

        </div>

        <input type="text" name="email" class="form-control" placeholder="@" autocomplete="off"
          value="<?= $dto->email ?>">

        <div class="input-group-append">
          <button type="button" class="btn input-group-text d-none" sendemail>
            <i class="bi bi-cursor"></i>

          </button>

        </div>

      </div>

    </div>

  </div>

  <div class="form-row row mb-2"><!-- property2sell -->
    <label class="col-md-3 col-form-label d-none d-md-block" for="<?= $_uid = strings::rand() ?>">Property to Sell</label>

    <div class="col">
      <div class="input-group">
        <div class="input-group-prepend d-md-none">
          <div class="input-group-text">p2s</div>
        </div>

        <input type="text" name="property2sell" class="form-control" placeholder="p2s"
          id="<?= $_uid ?>"
          value="<?= $dto->property2sell ?>">

      </div>

    </div>

  </div>

  <div class="form-row row mb-2"><!-- comment -->
    <label class="col-md-3 col-form-label pb-0" for="<?= $_comment = strings::rand() ?>">Comment</label>

    <div class="col">
      <textarea name="comment" class="form-control" placeholder="comment"
        id="<?= $_comment ?>"><?= $dto->comment ?></textarea>

    </div>

  </div>

  <div class="form-row row mb-2"><!-- notes -->
    <label class="col-md-3 col-form-label pb-0" for="<?= $_notes = strings::rand() ?>">Notes</label>

    <div class="col">
      <textarea name="notes" class="form-control" placeholder="notes"
        id="<?= $_notes ?>"><?= $dto->notes ?></textarea>

    </div>

  </div>

  <div class="form-row row mb-2"><!-- tasks -->
    <label class="col-md-3 col-form-label pb-0" for="<?= $_tasks = strings::rand() ?>">Tasks</label>

    <div class="col">
      <textarea name="tasks" class="form-control" placeholder="tasks"
        id="<?= $_tasks ?>"><?= $dto->tasks ?></textarea>

    </div>

  </div>

  <div class="form-row row"><!-- fu_buyer / fu_neighbour / fu_interested_party / fu_nsl -->
    <div class="offset-md-3 col text-center"><!-- fu_buyer -->
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="fu_buyer" value="yes"
          <?php if ( 'yes' == $dto->fu_buyer) print 'checked' ?>
          id="<?= $uid = strings::rand() ?>">

        <label class="form-check-label" for="<?= $uid ?>">
          buy

        </label>

      </div>

    </div>

    <div class="col text-center"><!-- fu_neighbour -->
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="fu_neighbour" value="yes"
          <?php if ( 'yes' == $dto->fu_neighbour) print 'checked' ?>
          id="<?= $uid = strings::rand() ?>">

        <label class="form-check-label" for="<?= $uid ?>">
          nbr

        </label>

      </div>

    </div>

    <div class="col text-center"><!-- fu_interested_party -->
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="fu_interested_party" value="yes"
          <?php if ( 'yes' == $dto->fu_interested_party) print 'checked' ?>
          id="<?= $uid = strings::rand() ?>">

        <label class="form-check-label" for="<?= $uid ?>">
          ip

        </label>

      </div>

    </div>

    <div class="col text-center"><!-- fu_nsl -->
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="fu_nsl" value="yes"
          <?php if ( 'yes' == $dto->fu_nsl) print 'checked' ?>
          id="<?= $uid = strings::rand() ?>">

        <label class="form-check-label" for="<?= $uid ?>">
          nsl

        </label>

      </div>

    </div>

  </div>

  <script>
  ( _ => {
    $('#<?= $_LinkedContactControl ?>')
    .addClass( 'pointer')
    .on( 'click', function( e) {
      e.stopPropagation();

      $('input[name="person_id"]', '#<?= $_form ?>').val( 0);
      $('.bi', this).removeClass( 'text-success').addClass( 'text-danger');

    });

    if ( !!window.documentsButton) documentsButton().attr( 'data-target', '#<?= $_collapseDocs ?>');

    $('input[name="name"]', '#<?= $_form ?>').autofill({
      autoFocus: false,
      source: _.search.inspectdiary_people,
      select: ( e, ui) => {
        let o = ui.item;
        $('input[name="person_id"]', '#<?= $_form ?>').val( o.id);
        $('input[name="email"]', '#<?= $_form ?>').val( o.email);
        $('input[name="mobile"]', '#<?= $_form ?>').val( o.mobile);
        $('input[name="property2sell"]', '#<?= $_form ?>').val( o.property2sell);
        $('#<?= $_LinkedContactControl ?> .bi').removeClass( 'text-danger').addClass( 'text-success');

        $('#<?= $_form ?>').trigger( 'save');

        $('input[name="mobile"]', '#<?= $_form ?>').autofill('destroy');

      },

    });

    (() => {
      let id = $('input[name="person_id"]', '#<?= $_form ?>').val();
      if ( Number( id) > 0 ) return;

      $('input[name="mobile"]', '#<?= $_form ?>').autofill({
        autoFocus: false,
        source: _.search.inspectdiary_people,
        select: ( e, ui) => {
          let o = ui.item;
          $('input[name="person_id"]', '#<?= $_form ?>').val( o.id);
          $('input[name="email"]', '#<?= $_form ?>').val( o.email);
          $('input[name="mobile"]', '#<?= $_form ?>').val( o.mobile);
          $('input[name="property2sell"]', '#<?= $_form ?>').val( o.property2sell);
          $('#<?= $_LinkedContactControl ?> .bi').removeClass( 'text-danger').addClass( 'text-success');

          if ( '' == $('input[name="name"]', '#<?= $_form ?>').val()) {
            $('input[name="name"]', '#<?= $_form ?>').val( o.name);

          }

          $('#<?= $_form ?>').trigger( 'save');

          $('input[name="mobile"]', '#<?= $_form ?>').autofill('destroy');

        },

      });

    })();

    $('#<?= $_comment ?>').autoResize();
    $('#<?= $_notes ?>').autoResize();
    $('#<?= $_tasks ?>').autoResize();

    $('button[sendsms]', '#<?= $_form ?>')
    .addClass( 'pointer')
    .on( 'click', e => {
      e.stopPropagation();

      let _fld = $('input[name="mobile"]', '#<?= $_form ?>');
      let tel = String( _fld.val());

      tel = tel.replace( /\s/g, '');

      _.get.sms().then( modal => {
        modal.trigger( 'add.recipient', tel);
        $('textarea[name="message"]', modal).focus();

      });

    });

    $('button[phonecall]', '#<?= $_form ?>')
    .addClass( 'pointer')
    .on( 'click', e => {
      e.stopPropagation();

      let _fld = $('input[name="mobile"]', '#<?= $_form ?>');
      let tel = String( _fld.val());

      tel = tel.replace( /\s/g, '');
      tel = tel.replace( /^0/, '+61');

      window.location.href = 'tel:' + tel;

    });

    $('input[name="mobile"]', '#<?= $_form ?>')
    .on( 'change', function(e) {
      let _me = $(this);
      let grp = _me.closest('.input-group')
      let tel = String( _me.val());

      if ( tel.IsMobilePhone()) {
        _me.val( tel.AsMobilePhone());

        _.get.sms.enabled().then( () => $('[sendsms]', grp).removeClass( 'd-none'));

      }
      else {
        $('[sendsms]', grp).addClass( 'd-none');
        if ( tel.IsPhone()) _me.val( tel.AsLocalPhone());

      }

      if ( tel.IsPhone()) {
        $('[phonecall]', grp).removeClass( 'd-none');

      }
      else {
        $('[phonecall]', grp).addClass( 'd-none');

      }

    })
    .trigger('change');

    $('button[sendemail]', '#<?= $_form ?>')
    .addClass( 'pointer')
    .on( 'click', e => {
      e.stopPropagation();

      let _fld = $('input[name="email"]', '#<?= $_form ?>');
      let email = String( _fld.val());

      let person = {
        name : $('input[name="name"]', '#<?= $_form ?>').val(),
        email : email

      };

      let ea = {
        to : _.email.rfc922( person),
        subject : <?= json_encode( $dto->address_street) ?>

      }

      _.email.activate(ea);

    });

    $('input[name="email"]', '#<?= $_form ?>')
    .on( 'change', function(e) {
      let _me = $(this);
      let grp = _me.closest('.input-group')
      let email = String( _me.val());

      if ( email.isEmail()) {
        if (!!_.email.activate) {
          $('[sendemail]', grp).removeClass( 'd-none');

        }
        else {
          $('[sendemail]', grp).addClass( 'd-none');

        }

      }
      else {
        $('[sendemail]', grp).addClass( 'd-none');

      }

    })
    .trigger('change');

    let thisPerson = () => {
      let _form = $('#<?= $_form ?>');  // the latest
      let _data = _form.serializeFormJSON();

      let r = {
        id : _data.person_id,
        name : _data.name,
        email : String( _data.email).trim(),
        mobile : _data.mobile,

      };

      return r;

    };

    $('#<?= $_form ?>')
    .on( 'property-id-change', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();

      if ( !!window.documentsButton) documentsButton().addClass('d-none');

      if ( parseInt( _data.property_id) < 1) return;

      if ( !!window._cms_) {
        if ( !!window._cms_.property) {
          if ( !!window._cms_.property.extensions) {
            _cms_.property.extensions({
              host : '#<?= $_collapseDocs ?>content',
              inspect_id : _data.id,
              person : thisPerson,
              property_id : _data.property_id,
              inspect_type : _data.type,

            })
            .then( () => documentsButton().removeClass('d-none'));

          }

        }

      }

    })
    .on( 'save', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();

      if ( _data.email != String( _data.email).trim()) {
        _data.email = String( _data.email).trim();
        $('input[name="email"]', '#<?= $_form ?>').val(_data.email);

      }

      $(document).trigger('candidate-saving');

      _.post({
        url : _.url('<?= $this->route ?>'),
        data : _data,

      }).then( d => {
        if ( 'ack' == d.response) {
          $('input[name="id"]', '#<?= $_form ?>').val( d.id);
          $('input[name="name"]', '#<?= $_form ?>').val( d.name);
          $(document).trigger('candidate-saved');
          $('#<?= $_form ?>').trigger( 'check-conflicts');

        }
        else {
          $(document).trigger('candidate-saving-error');

        }

      });

    })
    .on( 'submit', function( e) {
      return false;

    });

    $('#<?= $_form ?>')
    .trigger( 'property-id-change')
    .trigger( 'check-conflicts');

    $('input, textarea, select', '#<?= $_form ?>').on( 'change', e => $('#<?= $_form ?>').trigger( 'save'));

    <?php
      if ( !$dto->id) {
        print '$(document).ready( () => $(document).trigger(\'candidate-unsaved\'));';

      }

      if ( !$dto->person_id) {
        printf( '$(document).ready( () => $(\'input[name="name"]\', \'#%s\').focus());', $_form);

      }

    ?>

    let contextMenu = function( e) {
      if ( e.shiftKey)
        return;

      e.stopPropagation();e.preventDefault();

      _.hideContexts();

      let _context = _.context();
      let id = Number( $('input[name="id"]', '#<?= $_form ?>').val());

      if ( !!window._cms_) {
        if ( !!window._cms_.property) {

          let _form = $('#<?= $_form ?>');
          let _data = _form.serializeFormJSON();

          if ( !!window._cms_.property.reminderButton) {
            let ctrl = $('<a href="#"><i class="bi bi-bell"></i>Reminder</a>');
            ctrl.on( 'click', e => _context.close());

            _cms_.property.reminderButton({
              button : ctrl,
              person : thisPerson,
              property_id : _data.property_id,
              inspect_id : _data.id,
              inspect_type : _data.type,

            })
            .then( () => {});

            _context.append( ctrl);

          }

          if ( !!window._cms_.property.taskButton) {
            let ctrl = $('<a href="#"><i class="bi bi-list-task"></i>Task</a>');
            ctrl.on( 'click', e => _context.close());

            _cms_.property.taskButton({
              button : ctrl,
              person : thisPerson,
              property_id : _data.property_id,
              inspect_id : _data.id,
              inspect_type : _data.type,

            })
            .then( () => {});
            _context.append( ctrl);

          }

        }

      }

      if ( _context.length > 0) _context.append( '<hr>');

      if ( id > 0) {
        _context.append(
          $('<a href="#"><i class="bi bi-trash"></i>delete</a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _context.close();

            confirmDeleteAction().then( () => {
              deleteInspection( id).then( response => {
                gotoPeopleList();
                $(document).trigger('invalidate-counts');

              });

            });

          })

        );

      }
      else {
        _context.append(
          $('<a href="#" class="text-muted"><i class=bi bi-trash"></i>delete</a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

          })

        );

      }

      let target = $(this);
      let offsets = target.offset();
      let _e = {
        pageX : offsets.left,
        pageY : offsets.top + target.outerHeight(),
        target : e.target

      };

      _context.open( _e);

    };

    if ( !!window.setPersonContext) setPersonContext( contextMenu);

  })( _brayworth_);
  </script>

</form>