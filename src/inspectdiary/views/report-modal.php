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

$_report = strings::rand();

?>

<div id="<?= $_collapse = strings::rand() ?>">
  <div class="collapse" id="<?= $_inspection = strings::rand() ?>" data-parent="#<?= $_collapse ?>">
    <div style="margin-left: -15px; margin-right: -15px;">
      <nav class="navbar navbar-light bg-light" style="padding-left: 15px; padding-right: 15px;">
        <div class="d-flex flex-fill">
          <div class="navbar-brand" id="<?= $_title = strings::rand() ?>">Inspection</div>
          <button type="button" class="btn btn-light ml-auto"
            title="add inspection"
            id="<?= $_addInspection = strings::rand() ?>"><?= icon::get( icon::person_plus ) ?></button>
          <button type="button" class="btn btn-light d-none" aria-label="context menu"
            id="<?= $_context = strings::rand() ?>"><?= icon::get( icon::menu_up ) ?></button>
          <button type="button" class="btn btn-light" aria-label="Close" data-toggle="collapse" data-target="#<?= $_report ?>"><?= icon::get( icon::x ) ?></button>

        </div>

      </nav>

    </div>

    <div id="<?= $_inspection ?>content"></div>

  </div>

  <div class="collapse" id="<?= $_report ?>" data-parent="#<?= $_collapse ?>">
    <?php
      $_uid = strings::rand();
      if ( false) { ?>
      <div class="form-row mb-2">
        <div class="col">
          <div class="form-check form-check-inline">
            <input type="checkbox" class="form-check-input" id="<?= $_uid ?>chk-open-homes" />
            <label class="form-check-label" for="<?= $_uid ?>chk-open-homes">
              OH

            </label>

          </div>

          <div class="form-check form-check-inline">
            <input type="checkbox" class="form-check-input" id="<?= $_uid ?>chk-inspections" />
            <label class="form-check-label" for="<?= $_uid ?>chk-inspections">
              Inspections

            </label>

          </div>

          <div class="form-check form-check-inline">
            <input type="checkbox" class="form-check-input" id="<?= $_uid ?>chk-rental-open-homes" />
            <label class="form-check-label" for="<?= $_uid ?>chk-rental-open-homes">
              Rental OH

            </label>

          </div>

        </div>

      </div>
      <script>
      ( _ => $(document).ready( () => {
        (params => {
          let j = {
            OH : true,
            inspections : true,
            ROH : true,

          }

          $.extend( j, JSON.parse( params));

          $('#<?= $_uid ?>chk-open-homes').prop( 'checked', j.OH).trigger( 'change');
          $('#<?= $_uid ?>chk-inspections').prop( 'checked', j.inspections).trigger( 'change');
          $('#<?= $_uid ?>chk-rental-open-homes').prop( 'checked', j.ROH).trigger( 'change');

        })( localStorage.getItem('inspect_diary'));

        let setDefault = () => {
          let j = {
            OH : $('#<?= $_uid ?>chk-open-homes').prop( 'checked'),
            inspections : $('#<?= $_uid ?>chk-inspections').prop( 'checked'),
            ROH : $('#<?= $_uid ?>chk-rental-open-homes').prop( 'checked'),

          }

          localStorage.setItem( 'inspect_diary', JSON.stringify( j));

        };

        let setVisibility = () => {
          $('#<?= $_uid ?>chk-open-homes').prop('checked') ?
            $('#<?= $_uid ?>RentalDiary').addClass('show-oh') :
            $('#<?= $_uid ?>RentalDiary').removeClass('show-oh');

          $('#<?= $_uid ?>chk-inspections').prop('checked') ?
            $('#<?= $_uid ?>RentalDiary').addClass('show-insp') :
            $('#<?= $_uid ?>RentalDiary').removeClass('show-insp');

          $('#<?= $_uid ?>chk-rental-open-homes').prop('checked') ?
            $('#<?= $_uid ?>RentalDiary').addClass('show-roh') :
            $('#<?= $_uid ?>RentalDiary').removeClass('show-roh');

        }

        $('#<?= $_uid ?>chk-open-homes').on( 'change', () => { setVisibility(); setDefault();});
        $('#<?= $_uid ?>chk-inspections').on( 'change', () => { setVisibility(); setDefault(); });
        $('#<?= $_uid ?>chk-rental-open-homes').on( 'change', () => { setVisibility(); setDefault(); });

        setVisibility();

      }))( _brayworth_);
      </script>

    <?php } ?>

    <div class="row" id="<?= $_uid ?>RentalDiary">
      <div class="col">
        <div class="row bg-light border-bottom">
          <div class="d-none d-md-block col-1 text-center">
            <i class="fa fa-calendar-plus-o" data-role="new-inspect-diary-control" title="add new"></i>

          </div>
          <div class="col-3">date</div>
          <div class="col">address</div>
          <div class="col-2">
            <div class="row">
              <div class="col text-center">type</div>
              <div class="col d-none d-md-block text-center">no.</div>

            </div>

          </div>

          <div class="col d-none d-md-block">person</div>

        </div>

        <?php
          $i = 0;
          foreach ( $this->data->data as $dto) {	?>
          <div class="row py-1 border-bottom"
            data-role="item"
            data-id="<?= $dto->id ?>"
            data-property_id="<?= $dto->property_id ?>"
            data-address_street=<?= json_encode( $dto->address_street, JSON_UNESCAPED_SLASHES) ?>
            data-person_id="<?= $dto->contact_id ?>"
            data-inspect_id="<?= $dto->inspect_id ?>"
            data-inspections="<?= $dto->inspections ?>"
            data-type="<?= $dto->type ?>">

            <div class="d-none d-md-block col-1 text-center small"><?= ++$i ?></div>
            <div class="col-3">
              <div class="row">
                <div class="col-md-6 pr-1" data-field="date">
                  <?= strings::asShortDate( $dto->date) ?>

                </div>
                <div class="col-md-6 pr-1" data-field="time"><?= strings::AMPM( $dto->time) ?></div>

              </div>

            </div>

            <div class="col-7 col-md-3">
              <div class="row">
                <div class="col">
                  <div class="" data-field="street">
                    <?= $dto->address_street ?>

                  </div>

                </div>

              </div>

              <div class="row d-md-none">
                <div class="col text-muted">
                  <div class="text-truncate" data-field="contact_name">
                    <?php  if ( $dto->type == 'Inspect') print $dto->contact_name; ?>

                  </div>

                </div>

              </div>

            </div>

            <div class="col-2"><!-- type -->
              <div class="row">
                <div class="col text-center" data-field="type">
                  <?php
                    if ( 'OH Inspect' == $dto->type)
                      print 'OH';
                    elseif ( 'Inspect' == $dto->type)
                      print 'Insp';
                    else
                      print $dto->type;

                  ?>
                </div>

                <div class="col text-center"><?= $dto->inspections ?></div>

              </div>

            </div>

            <div class="d-none d-md-block col text-truncate"><?php
              if ( $dto->type == 'Inspect') print $dto->contact_name;

            ?></div>

          </div>
        <?php
          }	// foreach ( $this->data as $dto)	?>

      </div>

    </div>

  </div>

</div>

<div class="row mt-4">
  <div class="offset-md-2 col-md-8">
    <div class="alert alert-warning alert-dismissible fade show">
      <p><strong>caution!</strong> This work is NOT fully compatible with inspect<sup>v1</sup></p>

      <p>Missing data is populated by running the <em>inspect diary</em> report -
      so normally should not be a problem, but if you use the mobile inspect App
      without running the report, data will not appear</p>

      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>

    </div>

  </div>

</div>

<!-- style>
#<?= $_uid ?>RentalDiary div[data-role="item"]:nth-of-type(odd) {
    background-color: rgba(0,0,0,.05)
}
</style -->

<script>
( _ => {
  $('#<?= $_collapse ?>')
  .on( 'hidden.bs.collapse', function(e) {
    let _el = $(e.target);
    let id = _el.attr('id');

    if ( '<?= $_inspection ?>' == id) {
      $('#<?= $_inspection ?>content').html('');
      $('#<?= $_context ?>').addClass( 'd-none').off( 'click');

    }

  });

  $('#<?= $_inspection ?>content')
  .on( 'refresh', function(e) {

    let _me = $(this);
    _me.html('<div class="d-flex"><div class="spinner-border mx-auto my-5" role="status"><span class="sr-only">Loading...</span></div></div>');

    let _data = _me.data();

    _.get( _.url( '<?= $this->route ?>/inspects/' + _data.id))
    .then( html => _me.html( html));

  })
  .on( 'add-inspection', function(e) {
    e.stopPropagation();

    let _me = $(this);
    let _data = _me.data();

    // console.log(_data);
    let url = _.url( '<?= $this->route ?>/inspection/?idid=' + _data.id);
    // console.log( url);

    _.get.modal( url)
    .then( modal => modal.on( 'success', e => _me.trigger( 'refresh')));

  })
  .on( 'view-inspection', function(e, id) {
    e.stopPropagation();

    let _me = $(this);
    // let _data = _me.data();

    let url = _.url( '<?= $this->route ?>/inspection/' + id);
    // console.log( url);

    _.get.modal( url)
    .then( modal => {
      modal.on( 'success', e => _me.trigger( 'refresh'))

      let form = modal.closest( 'form');
      if ( form.length > 0) {
        let fld = $( 'input[name="inspect_diary_id"]', form);
        if ( fld.length > 0) {
          _me.data('id', fld.val());

        }

      }


    });

  });

  $('#<?= $_addInspection ?>').on( 'click', e => {
    e.stopPropagation();
    $('#<?= $_inspection ?>content').trigger('add-inspection');

  });

  $(document)
  .on( 'add-inspection', e => $('#<?= $_inspection ?>content').trigger('add-inspection'))
  .on( 'edit-inspection-by-id', (e, id) => {
    $('#<?= $_report ?>').collapse('show');

    let found = false;
    $('div[data-role="item"]', '#<?= $_uid ?>RentalDiary').each( ( i, row) => {
      let _row = $(row);
      let _data = _row.data();

      if ( id == _data.id) {
        _row.trigger('edit');

        found = true;
        return false;

      }

    });

  })
  .on( 'view-inspection', (e,id) => $('#<?= $_inspection ?>content').trigger('view-inspection', id))
  .on( 'load-inspects', ( e, data) => {

    $('#<?= $_title ?>').html( data.address_street);

    $('#<?= $_inspection ?>content').data( 'id', data.id).trigger( 'refresh');
    $('#<?= $_inspection ?>').collapse('show');

  })
  .on( 'refresh-inspects', ( e) => $('#<?= $_inspection ?>content').trigger( 'refresh'))
  .on( 'set-inspection-context', ( e, context) => {
    $('#<?= $_context ?>')
    .removeClass( 'd-none')
    .off( 'click')
    .on( 'click', context);

  })
  .ready( () => {
    $('div[data-role="item"]', '#<?= $_uid ?>RentalDiary').each( ( i, row) => {
      let _row = $(row);
      // let _data = _row.data();

      let contextMenu = function( e) {
        if ( e.shiftKey)
          return;

        e.stopPropagation();e.preventDefault();

        _brayworth_.hideContexts();

        let _me = $(this);
        let _data = _me.data();
        let _context = _brayworth_.context();

        if ( 'Inspect' == _data.type) {
          if ( _data.inspect_id > 0) {
            _context.append( $('<a class="font-weight-bold" href="#">inspection</a>').on( 'click', function( e) {
              e.stopPropagation();e.preventDefault();

              _context.close();
              $(document).trigger( 'view-inspection', _data.inspect_id);

            }));

          }
          else {
            _context.append( $('<a class="font-weight-bold text-danger" href="#">inspection not found</a>').on( 'click', function( e) {
              e.stopPropagation();e.preventDefault();

              _context.close();

            }));

          }

        }
        else {
          _context.append( $('<a class="font-weight-bold" href="#">inspections</a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _context.close();

            $(document).trigger( 'load-inspects', _data);

          }));

        }

        _context.append( $('<a href="#"><i class="fa fa-pencil"></i>edit</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _context.close();
          _me.trigger('edit');

        }));

        if ( 'Inspect' == _data.type || 0 == Number( _data.inspections)) {
          _context.append( $('<a href="#"><i class="fa fa-trash"></i>delete</a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _context.close();
            _me.trigger( 'delete', _data.inspect_id);

          }));

        }

        _context.append( '<hr>');
        _context.append( $('<a href="#">close</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _context.close();

        }));

        _context.open( e);

      };

      let click = function( e) {
        e.stopPropagation();e.preventDefault();

        let _me = $(this);
        let _data = _me.data();

        if ( _data.inspect_id > 0) {
          $(document).trigger( 'view-inspection', _data.inspect_id);

        }
        else {
          $(document).trigger( 'load-inspects', _data);

        }
        // console.log( _data);

      }

      _row
      .on( 'delete', function(e) {
        let _me = $(this);

				_.ask({
					headClass: 'text-white bg-danger',
					text: 'Are you sure ?',
					title: 'Confirm Delete',
					buttons : {
						yes : function(e) {
							$(this).modal('hide');
							_me.trigger( 'delete-confirmed');

						}

					}

				});

      })
      .on( 'delete-confirmed', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url : _.url('<?= $this->route ?>'),
          data : {
            action : 'inspect-diary-delete',
            id : _data.id
          },

        }).then( d => {
          if ( 'ack' == d.response) {
            window.location.reload();

          }
          else {
            _.growl( d);

          }

        });

      })
      .on( 'edit', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.get.modal( _.url( '<?= $this->route ?>/edit/' + _data.id))
        .then( modal => {
          modal.on( 'success', () => _me.trigger('refresh'));

        });

      })
      .on( 'refresh', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url : _.url('inspectdiary'),
          data : {
            action : 'get-by-id',
            id : _data.id

          },

        }).then( d => {
          if ( 'ack' == d.response) {
            _me.data('property_id', d.data.property_id);
            _me.data('address_street', d.data.address_street);
            _me.data('person_id', d.data.contact_id);
            _me.data('inspect_id', d.data.inspect_id);
            _me.data('type', d.data.type);

            $('[data-field="date"]', _me).html( d.data.shortdate);
            $('[data-field="time"]', _me).html( d.data.shorttime);
            $('[data-field="street"]', _me).html( d.data.address_street);
            $('[data-field="contact_name"]', _me).html( d.data.contact_name);
            if ( 'OH Inspect' == d.data.type) {
              $('[data-field="type"]', _me).html( 'OH');

            }
            else if ( 'Inspect' == d.data.type) {
              $('[data-field="type"]', _me).html( 'Insp');

            }
            else {
              $('[data-field="type"]', _me).html( d.data.type);

            }

            // $('[data-field]', _me).each( (i, el) => console.log( $(el).data('field')));
            // console.log( d.data);
            _me.removeClass( 'bg-warning');

          }
          else {
            _.growl(d);

          }

        });

        _me.addClass( 'bg-warning');

      })
      .addClass('pointer')
      .on( 'click', click)
      .on( 'contextmenu', contextMenu);

    });

    // var someTabTriggerEl = document.querySelector('#someTabTrigger')
    // var tab = new bootstrap.Tab(someTabTriggerEl)

    $('[data-role="content-primary"]')
    .removeClass('pt-3')
    .addClass('pt-0 pt-md-3')

    $('#<?= $_report ?>').collapse('show');

  });

})( _brayworth_);
</script>