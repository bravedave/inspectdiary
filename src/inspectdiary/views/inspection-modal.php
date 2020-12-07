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

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
  <input type="hidden" name="action" value="inspection-save">
  <input type="hidden" name="id" value="<?= $dto->id ?>">
  <input type="hidden" name="type" value="<?= $dto->type ?>">
  <input type="hidden" name="person_id" value="<?= $dto->person_id ?>">
  <input type="hidden" name="property_id" value="<?= $dto->property_id ?>">
  <input type="hidden" name="inspect_diary_id" value="<?= $dto->inspect_diary_id ?>">

  <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-secondary text-white py-2 pl-2">
          <h5 class="modal-title" id="<?= $_modal ?>Label"><?= $dto->address_street ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>

        </div>

        <div class="modal-body px-2">

          <div class="collapse" id="<?= $_collapseDocs = strings::rand() ?>">
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
                    <i class="fa <?=  $dto->person_id ? 'fa-chain' : 'fa-chain-broken' ?>"></i>

                  </div>

                </div>

              </div>

            </div>

          </div>

          <div class="form-row row mb-2"><!-- mobile -->
            <div class="offset-md-3 col">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <i class="fa fa-mobile"></i>
                  </div>


                </div>

                <input type="text" name="mobile" class="form-control" placeholder="phone" autocomplete="off"
                  value="<?= $dto->mobile ?>">

                <div class="input-group-append">
                  <button type="button" class="btn input-group-text d-none" sendsms>
                    <i class="fa fa-commenting-o"></i>

                  </button>

                </div>

                <div class="input-group-append" >
                  <button type="button" class="btn input-group-text d-none" phonecall>
                    <i class="fa fa-phone"></i>

                  </button>

                </div>

              </div>

            </div>

          </div>

          <div class="form-row row mb-2"><!-- email -->
            <div class="offset-md-3 col">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">@</div>

                </div>

                <input type="text" name="email" class="form-control" placeholder="@" autocomplete="off"
                  value="<?= $dto->email ?>">

                <div class="input-group-append">
                  <button type="button" class="btn input-group-text d-none" sendemail>
                    <i class="fa fa-envelope-o"></i>

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

        </div>

        <div class="modal-footer px-2">
          <div class="btn-group mr-auto" role="group">
            <button type="button" class="btn btn-secondary d-none" id="<?= $_btnReminder = strings::rand() ?>"><i class="fa fa-fw fa-bell-o"></i>remind</button>
            <button type="button" class="btn btn-secondary d-none" id="<?= $_btnTask = strings::rand() ?>"><i class="fa fa-fw fa-tasks"></i>task</button>

          </div>

          <button type="button" class="btn btn-secondary d-none" data-toggle="collapse"
            id="<?= $_docs_Button = strings::rand() ?>" data-target="#<?= $_collapseDocs ?>">docs</button>
          <!-- button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button -->
          <button type="submit" class="btn btn-primary">Save</button>

        </div>

      </div>

    </div>

  </div>
  <script>
  ( _ => {
    $('#<?= $_modal ?>').on( 'shown.bs.modal', e => {
      <?php if ( !$dto->person_id) { ?>
        $('input[name="name"]', '#<?= $_form ?>').focus();

      <?php } ?>

      $('#<?= $_comment ?>').trigger('resize');
      $('#<?= $_notes ?>').trigger('resize');
      $('#<?= $_tasks ?>').trigger('resize');

    });

    $('#<?= $_LinkedContactControl ?>')
    .addClass( 'pointer')
    .on( 'click', function( e) {
      e.stopPropagation();

      $('input[name="person_id"]', '#<?= $_form ?>').val( 0);
      $('.fa', this).removeClass( 'fa-chain').addClass( 'fa-chain-broken');

    })

    $('input[name="name"]', '#<?= $_form ?>').autofill({
      autoFocus: false,
      source: _.search.inspectdiary_people,
      select: ( e, ui) => {
        let o = ui.item;
        $('input[name="person_id"]', '#<?= $_form ?>').val( o.id);
        $('input[name="mobile"]', '#<?= $_form ?>').val( o.mobile).trigger('change');
        $('input[name="email"]', '#<?= $_form ?>').val( o.email);
        $('input[name="property2sell"]', '#<?= $_form ?>').val( o.property2sell);
        $('#<?= $_LinkedContactControl ?> .fa').removeClass( 'fa-chain-broken').addClass( 'fa-chain');

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
          $('input[name="mobile"]', '#<?= $_form ?>').val( o.mobile).trigger('change');
          $('input[name="email"]', '#<?= $_form ?>').val( o.email);
          $('input[name="property2sell"]', '#<?= $_form ?>').val( o.property2sell);
          $('#<?= $_LinkedContactControl ?> .fa').removeClass( 'fa-chain-broken').addClass( 'fa-chain');

          if ( '' == $('input[name="name"]', '#<?= $_form ?>').val()) {
            $('input[name="name"]', '#<?= $_form ?>').val( o.name).trigger('change');

          }
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
        _.get.sms.enabled().then( () => $('[sendsms]', grp).removeClass( 'd-none'));

      }
      else {
        $('[sendsms]', grp).addClass( 'd-none');

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

      console.log( ea);
      _.email.activate(ea);

    });

    $('input[name="email"]', '#<?= $_form ?>')
    .on( 'change', function(e) {
      let _me = $(this);
      let grp = _me.closest('.input-group')
      let email = String( _me.val());

      if ( email.isEmail() && !!_.email.activate) {
        _.get.sms.enabled().then( () => $('[sendemail]', grp).removeClass( 'd-none'));

      }
      else {
        $('[sendemail]', grp).addClass( 'd-none');

      }

    })
    .trigger('change');

    $('#<?= $_form ?>')
    .on( 'property-id-change', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();

      $('#<?= $_docs_Button ?>').addClass('d-none');

      if ( parseInt( _data.property_id) < 1) return;

      if ( !!window._cms_) {
        if ( !!window._cms_.property) {
          let person = () => {
            let _form = $('#<?= $_form ?>');  // the latest
            let _data = _form.serializeFormJSON();

            let r = {
              id : _data.person_id,
              name : _data.name,
              email : _data.email,
              mobile : _data.mobile,

            };

            return r;

          };

          if ( !!window._cms_.property.extensions) {
            _cms_.property.extensions({
              host : '#<?= $_collapseDocs ?>content',
              inspect_id : _data.id,
              person : person,
              property_id : _data.property_id,
              inspect_type : _data.type,

            })
            .then( () => $('#<?= $_docs_Button ?>').removeClass('d-none'));

          }

          if ( !!window._cms_.property.reminderButton) {
            _cms_.property.reminderButton({
              button : '#<?= $_btnReminder ?>',
              person : person,
              property_id : _data.property_id,
              inspect_id : _data.id,
              inspect_type : _data.type,

            })
            .then( () => $('#<?= $_btnReminder ?>').removeClass('d-none'));

          }

          if ( !!window._cms_.property.taskButton) {
            _cms_.property.taskButton({
              button : '#<?= $_btnTask ?>',
              person : person,
              property_id : _data.property_id,
              inspect_id : _data.id,
              inspect_type : _data.type,

            })
            .then( () => $('#<?= $_btnTask ?>').removeClass('d-none'));

          }

        }

      }

    })
    .on( 'submit', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();
      // let _modalBody = $('.modal-body', _form);

      _.post({
        url : _.url('<?= $this->route ?>'),
        data : _data,

      }).then( d => {
        if ( 'ack' == d.response) {
          $('#<?= $_modal ?>').trigger('success');

        }
        else {
          _.growl( d);

        }

        $('#<?= $_modal ?>').modal('hide');

      });

      return false;

    });

    $('#<?= $_form ?>').trigger( 'property-id-change');

    $(document).ready( () => {});

  })( _brayworth_);
  </script>

</form>