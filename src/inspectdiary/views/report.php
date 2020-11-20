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
use strings;	?>

<div class="form-row mb-2">
	<div class="col">
		<div class="form-check form-check-inline">
			<input type="checkbox" class="form-check-input" id="<?= $_uid = strings::rand() ?>chk-open-homes" />
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

<div class="row" id="<?= $_uid ?>RentalDiary">
  <div class="col">
    <div class="row bg-light border-bottom">
      <div class="d-none d-md-block col-1 text-center">
        <i class="fa fa-calendar-plus-o" data-role="new-inspect-diary-control" title="add new"></i>

      </div>
      <div class="col">date</div>
      <div class="col">address</div>
      <div class="col-2">
        <div class="row">
          <div class="col">type</div>
          <div class="col text-center">no.</div>

        </div>

      </div>

      <div class="col d-none d-md-block">person</div>
      <div class="col-1" title="has appointment"><i class="fa fa-calendar-o"></i></div>

    </div>

    <?php
      $i = 0;
      foreach ( $this->data->data as $dto) {	?>
      <div class="row mb-2"
        data-role="item"
        data-id="<?= $dto->id ?>"
        data-property_id="<?= $dto->property_id ?>"
        data-property="<?= $dto->address_street ?>"
        data-person_id="<?= $dto->contact_id ?>"
        data-property_diary_id="<?= $dto->pdid ?>"
        data-date="<?= $dto->date ?>"
        data-time="<?= date( 'c', strtotime( sprintf( '%s %s', $dto->date, strings::AMPM( $dto->time)))) ?>"
        data-type="<?= $dto->type ?>"
        data-hasappointment="<?= $dto->hasappointment ?>">

        <div class="d-none d-md-block col-1 text-center small"><?= ++$i ?></div>
        <div class="col">
          <div class="row">
            <div class="col-md-6">
              <?= strings::asShortDate( $dto->date) ?>

            </div>
            <div class="col-md-6"><?= strings::AMPM( $dto->time) ?></div>

          </div>

        </div>

        <div class="col">
          <div class="text-truncate"><?= $dto->address_street ?></div>

        </div>

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

        <div class="d-none d-md-block col">
          <div class="text-truncate"><?php
            if ( $dto->type == 'Inspect') print $dto->contact_name;

          ?></div>

        </div>

        <div class="col-1"><?php if ( $dto->hasappointment) print config::$HTML_TICK ?></div>

      </div>
    <?php
      }	// foreach ( $this->data as $dto)	?>

  </div>

</div>
<script>
( _ => $(document).ready( () => {
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

}))( _brayworth_);
</script>
