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

$data = $this->data;  ?>

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">

  <input type="hidden" name="action" value="quickbook">
  <input type="hidden" name="property_id" value="<?= $data->property_id ?>">
  <input type="hidden" name="address_street" value="<?= $data->address_street ?>">
  <input type="hidden" name="people_id" value="<?= $data->people_id ?>">
  <input type="hidden" name="inspect_diary_id" value="<?= $data->inspect_diary_id ?>">
  <input type="hidden" name="type" value="<?= $data->type ?>">

  <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-secondary text-white py-2">
          <h5 class="modal-title" id="<?= $_modal ?>Label"><?= $this->title ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>

        </div>

        <div class="modal-body p-1">
          <div class="accordion" id="<?= $_accordion = strings::rand()  ?>">
            <div class="card"><!-- Choose an Inspection -->
              <div class="card-header p-0" id="<?= $_headingInspectDiary = strings::rand() ?>">
                <h2 class="m-0">
                  <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#<?= $_collapseInspectDiary = strings::rand() ?>" aria-expanded="true" aria-controls="<?= $_collapseInspectDiary ?>">
                    Choose an Inspection

                  </button>

                </h2>

              </div>

              <div id="<?= $_collapseInspectDiary ?>" class="collapse show" aria-labelledby="<?= $_headingInspectDiary ?>" data-parent="#<?= $_accordion ?>">
                <div class="card-body p-2" id="<?= $_collapseInspectDiary ?>_entries">&nbsp;</div>

              </div>

            </div>

            <div class="card">
              <div id="<?= $_collapseInspect = strings::rand() ?>" class="collapse" data-parent="#<?= $_accordion ?>">
                <div class="card-body p-2">
                  <div class="form-row mb-2"><!-- name -->
                    <div class="col">
                      <input type="text" name="name" class="form-control" placeholder="name" autocomplete="off" value="<?= $data->name ?>" required>

                    </div>

                  </div>

                  <div class="form-row mb-2"><!-- mobile -->
                    <div class="col">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text"><i class="bi bi-telephone"></i></div>

                        </div>

                        <input type="text" name="mobile" class="form-control" placeholder="phone" autocomplete="off" value="<?= $data->mobile ?>" required>

                      </div>

                    </div>

                  </div>

                  <div class="form-row row mb-2"><!-- email -->
                    <div class="col">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text"><i class="bi bi-at"></i></div>

                        </div>

                        <input type="text" name="email" class="form-control" placeholder="@" autocomplete="off" value="<?= $data->email ?>" required>

                      </div>

                    </div>

                  </div>

                  <div class="form-row row mb-2"><!-- comment -->
                    <div class="col">
                      <textarea name="comment" rows="3" class="form-control" placeholder="comment ..."><?= $data->comment ?></textarea>

                    </div>

                  </div>

                </div>

              </div>

            </div>

          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">close</button>
          <button type="submit" class="btn btn-primary" disabled>Save</button>

        </div>

      </div>

    </div>

  </div>
  <script>
  ( _ => {
    $('#<?= $_form ?>')
    .on( 'get-future', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();

      $('#<?= $_collapseInspectDiary ?>_entries').html('<div class="text-center py-4"><div class="spinner-border"></div></div>');

      _.post({
        url : _.url('<?= $this->route ?>'),
        data : {
          action : 'get-future-inspections',
          property_id : _data.property_id

        },

      }).then( d => {
        if ( 'ack' == d.response) {
          let table = $('<table class="table table-sm"></table>');
          table.append((()  => {
            let thead = $('<thead class="small"></thead>');
            let tr = $('<tr></tr>').appendTo( thead);

            $( '<td>&nbsp;</td>').appendTo( tr);
            $( '<td>date</td>').appendTo( tr);
            $( '<td>time</td>').appendTo( tr);
            $( '<td>address</td>').appendTo( tr);

            return thead;

          })());

          let tbody = $('<tbody></tbody>').appendTo( table);
          $.each( d.data, (e, oh) => {
            let tr = $('<tr></tr>').appendTo( tbody);

            let d = _.dayjs( oh.date);
            $( '<td><i class="bi bi-square" indicator></i></td>').appendTo( tr);
            $( '<td></td>').html( d.format( 'l')).appendTo( tr);
            $( '<td></td>').html( oh.time).appendTo( tr);
            $( '<td></td>').html( oh.address_street).appendTo( tr);

            tr
            .data( 'oh', oh)
            .addClass( 'pointer')
            .on( 'click', function( e) {
              e.stopPropagation();e.preventDefault();

              let _tr = $(this);
              let _data = _tr.data();
              let _oh = _data.oh;

              _tr.siblings().each( (i, tr) => $('[indicator]', tr).removeClass('bi-check-square').addClass('bi-square'));
              $('[indicator]', this).removeClass('bi-square').addClass('bi-check-square');

              $('input[name="address_street"]', '#<?= $_form ?>').val( _oh.address_street);
              $('input[name="inspect_diary_id"]', '#<?= $_form ?>').val( _oh.id);
              $('button[type="submit"]', '#<?= $_form ?>').prop( 'disabled', false);

              let d = _.dayjs( _oh.date + ' ' + _oh.time);

              $('#<?= $_headingInspectDiary ?> button').html( _oh.address_street + ' - ' + d.format('ha ddd, MMM D'));
              $('#<?= $_collapseInspect ?>').collapse('show');

            })

            // console.log( oh);

          });

          table.append((()  => {
            let tfoot = $('<tfoot></tfoot>');
            let tr = $('<tr></tr>').appendTo( tfoot);
            let td = $( '<td colspan="4" class="text-right"></td>').appendTo( tr);

            if ( Number( $('input[name="property_id"]', '#<?= $_form ?>').val()) > 0) {
              $('<button type="button" class="btn btn-outline-secondary mr-2">show all</button>')
              .on( 'click', function( e) {
                e.stopPropagation();e.preventDefault();

                $('input[name="property_id"]', '#<?= $_form ?>').val(0);
                $('#<?= $_form ?>').trigger( 'get-future');

              })
              .appendTo( td);

            }

            $('<button type="button" class="btn btn-outline-secondary"><i class="bi bi-plus"></i> new</button>')
            .on( 'click', function( e) {
              e.stopPropagation();e.preventDefault();

              $('#<?= $_modal ?>').modal('hide');

              _.get.modal( _.url( '<?= $this->route ?>/edit/'))
              .then( modal => {
                let form = modal.closest('form');
                $('input[name="property_id"]', form).val(_data.property_id);
                $('input[name="address_street"]', form).val(_data.address_street);

                modal
                .on( 'success', () => _.get.modal( _.url( '<?= $this->route ?>/quickbook/?property_id=' + _data.property_id)))

              });

            })
            .appendTo( td);

            return tfoot;

          })());

          $('#<?= $_collapseInspectDiary ?>_entries').html('').append( table);

        }
        else {
          _.growl( d);

        }

      });

    })
    .on( 'submit', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();
      let _modalBody = $('.modal-body', _form);

      // console.table( _data);
      _.post({
        url : _.url('<?= $this->route ?>'),
        data : _data,

      }).then( d => {
        if ( 'ack' == d.response) {
          $( '#<?= $_modal ?>').trigger( 'success');

        }
        else {
          _.growl( d);

        }

        $( '#<?= $_modal ?>').modal( 'hide');

      });

      return false;

    })
    .trigger( 'get-future');

    (() => {
      return ('undefined' == typeof _.search || 'undefined' == typeof _.search.inspectdiary_people) ?
        _.get.script( _.url("<?= $this->route ?>/js")) :
        Promise.resolve();

    })().then( () => {
      $('input[name="name"]', '#<?= $_form ?>').autofill({
        autoFocus: false,
        source: _.search.inspectdiary_people,
        select: ( e, ui) => {
          let o = ui.item;
          $('input[name="person_id"]', '#<?= $_form ?>').val( o.id);
          $('input[name="email"]', '#<?= $_form ?>').val( o.email).trigger('change');
          $('input[name="mobile"]', '#<?= $_form ?>').val( String( o.mobile).AsMobilePhone()).trigger('change');

        },

      });

    });

    $('input[name="mobile"]', '#<?= $_form ?>').on( 'change', function(e ) {
      let _me = $(this);

      $('input[name="email"]', '#<?= $_form ?>').prop( 'required', !String( _me.val()).IsPhone());

    });

    $('input[name="email"]', '#<?= $_form ?>').on( 'change', function(e ) {
      let _me = $(this);

      $('input[name="mobile"]', '#<?= $_form ?>').prop( 'required', !String( _me.val()).isEmail());

    });

    $(document).ready( () => {});

  })( _brayworth_);
  </script>

</form>
