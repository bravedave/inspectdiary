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

<div class="accordion" id="<?= $_accordion = strings::rand() ?>">
  <div class="accordion-item">
    <div class="accordion-collapse collapse" id="<?= $_inspection = strings::rand() ?>" data-parent="#<?= $_accordion ?>">
      <div style="margin-left: -15px; margin-right: -15px;">
        <nav class="navbar navbar-light bg-light" style="padding-left: 15px; padding-right: 15px;">
          <div class="flex-fill">
            <div class="navbar-brand" id="<?= $_title = strings::rand() ?>">Inspection</div>
            <button type="button" class="close" aria-label="Close" data-toggle="collapse" data-target="#<?= $_report ?>">&times;</button>

          </div>

        </nav>

      </div>

      <div id="<?= $_inspection ?>content"></div>

    </div>

  </div>

  <div class="accordion-item">
    <div class="accordion-collapse collapse" id="<?= $_report ?>" data-parent="#<?= $_accordion ?>">
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
                <div class="col">type</div>
                <div class="col text-center">no.</div>

              </div>

            </div>

            <div class="col d-none d-md-block">person</div>
            <div class="col-1 d-none d-md-block" title="has appointment"><i class="fa fa-calendar-o"></i></div>

          </div>

          <?php
            $i = 0;
            foreach ( $this->data->data as $dto) {	?>
            <div class="row mb-2 border-bottom"
              data-role="item"
              data-id="<?= $dto->id ?>"
              data-property_id="<?= $dto->property_id ?>"
              data-address_street="<?= htmlentities( $dto->address_street) ?>"
              data-person_id="<?= $dto->contact_id ?>"
              data-property_diary_id="<?= $dto->pdid ?>"
              data-date="<?= $dto->date ?>"
              data-time="<?= date( 'c', strtotime( sprintf( '%s %s', $dto->date, strings::AMPM( $dto->time)))) ?>"
              data-type="<?= $dto->type ?>"
              data-hasappointment="<?= $dto->hasappointment ?>">

              <div class="d-none d-md-block col-1 text-center small"><?= ++$i ?></div>
              <div class="col-3">
                <div class="row">
                  <div class="col-md-6">
                    <?= strings::asShortDate( $dto->date) ?>

                  </div>
                  <div class="col-md-6"><?= strings::AMPM( $dto->time) ?></div>

                </div>

              </div>

              <div class="col text-truncate"><?= $dto->address_street ?></div>

              <div class="col-2">
                <div class="row">
                  <div class="col">
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

              <div class="col-1 d-none d-md-block"><?php if ( $dto->hasappointment) print config::$HTML_TICK ?></div>

            </div>
          <?php
            }	// foreach ( $this->data as $dto)	?>

        </div>

      </div>

    </div>

  </div>

</div>

<script>
( _ => {
  $('#<?= $_accordion ?>')
  .on( 'hidden.bs.collapse', function(e) {
    let _el = $(e.target);
    let _data = _el.data();
    let id = _el.attr('id');

    if ( '<?= $_inspection ?>' == id) {
      $('#<?= $_inspection ?>content').html('');

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

  $(document)
  .on( 'add-inspection', e => $('#<?= $_inspection ?>content').trigger('add-inspection'))
  .on( 'view-inspection', (e,id) => $('#<?= $_inspection ?>content').trigger('view-inspection', id))
  .on( 'load-inspects', ( e, data) => {

    $('#<?= $_title ?>').html( data.address_street);

    $('#<?= $_inspection ?>content').data( 'id', data.id).trigger( 'refresh');
    $('#<?= $_inspection ?>').collapse('show');

  })
  .on( 'refresh-inspects', ( e) => $('#<?= $_inspection ?>content').trigger( 'refresh'))
  .ready( () => {
    $('div[data-role="item"]', '#<?= $_uid ?>RentalDiary').each( ( i, row) => {
      let _row = $(row);
      // let _data = _row.data();

      _row
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

        _me.addClass( 'bg-warning');

      })
      .addClass('pointer')
      .on( 'click', function( e) {
        e.stopPropagation();e.preventDefault();

        let _me = $(this);
        let _data = _me.data();

        $(document).trigger( 'load-inspects', _data);
        // console.log( _data);

      })
      .on( 'contextmenu', function( e) {
        if ( e.shiftKey)
          return;

        e.stopPropagation();e.preventDefault();

        _brayworth_.hideContexts();

        let _me = $(this);
        let _data = _me.data();
        let _context = _brayworth_.context();

        _context.append( $('<a class="font-weight-bold" href="#">edit</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _context.close();
          _me.trigger('edit');

        }));

        _context.open( e);

      });

    });

    // var someTabTriggerEl = document.querySelector('#someTabTrigger')
    // var tab = new bootstrap.Tab(someTabTriggerEl)

    $('#<?= $_report ?>').collapse('show');

  });

})( _brayworth_);
</script>
