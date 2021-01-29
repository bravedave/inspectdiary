<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/  ?>

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
  <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-secondary text-white py-2">
          <h5 class="modal-title" id="<?= $_modal ?>Label"><?= $this->title ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-1">
          <table class="table table-sm table-striped" id="<?= $_table = strings::rand() ?>">
            <thead>
              <tr>
                <th>address</th>
                <th class="text-center">last week</th>
                <th class="text-center">this week</th>
                <th class="text-center">next week</th>
                <th class="text-center">w/e <?= strings::asShortDate( $this->data->inspectdata['weekafternext']->seed); ?></th>

              </tr>

            </thead>

            <tbody>
            <?php
              $lastDate = function( $property, $dataSet) {
                foreach ($dataSet as $dto) {
                  if ( $property->id == $dto->property_id) {
                    if ( preg_match( '@^OH@', $dto->type)) {
                      return sprintf( '%s %s', $dto->date, strings::AMPM( $dto->time));

                    }

                  }

                }

                return '';

              };

              $sprintV = function( $property, $dataSet, $filler = '') {
                $text = [];
                foreach ($dataSet as $dto) {
                  if ( $property->id == $dto->property_id) {
                    if ( preg_match( '@^OH@', $dto->type)) {
                      $text[] = sprintf(
                        '%s %s',
                        date( 'D', strtotime( $dto->date)),
                        $dto->time

                      );

                    }

                  }

                }

                return $text ? implode( '<br>', $text) : $filler;

              };

              foreach ($this->data->inspectdata['properties'] as $property) {
                $_d = strtotime( $lastDate( $property, $this->data->inspectdata['lastweek']->data));

                printf( '<tr data-date="%s">', date( 'Y-m-d H:i:s', $_d));

                printf( '<td>%s</td>', strings::GoodStreetString( $property->address_street));
                printf( '<td class="text-center">%s</td>', $sprintV( $property, $this->data->inspectdata['lastweek']->data, ''));

                // $_d = strtotime( 'this saturday', $_d);
                printf(
                  '<td class="text-center" data-property_id="%s" data-date="%s" data-slot="thisweek">%s</td>',
                  $property->id,
                  date(
                    'Y-m-d H:i:s',
                    strtotime( '+7 days', $_d)

                  ),
                  $sprintV( $property, $this->data->inspectdata['thisweek']->data)

                );

                printf(
                  '<td class="text-center" data-property_id="%s" data-date="%s" data-slot="nextweek">%s</td>',
                  $property->id,
                  date(
                    'Y-m-d H:i:s',
                    strtotime( '+14 days', $_d)

                  ),
                  $sprintV( $property, $this->data->inspectdata['nextweek']->data)

                );

                printf(
                  '<td class="text-center" data-property_id="%s" data-date="%s" data-slot="weekafternext">%s</td>',
                  $property->id,
                  date(
                    'Y-m-d H:i:s',
                    strtotime( '+21 days', $_d)

                  ),
                  $sprintV( $property, $this->data->inspectdata['weekafternext']->data)

                );

                print '</tr>';

              };

            ?>
            </tbody>

          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary mr-auto" id="<?= $_uidNew = strings::rand() ?>"><i class="bi bi-plus"></i> new</button>
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">close</button>
          <button type="submit" class="btn btn-primary">Save</button>

        </div>

      </div>

    </div>

  </div>

  <script>
  ( _ => $(document).ready( () => {
    _.table.sortOn( '#<?= $_table ?>', 'date', false, 'asc');

    $( 'td[data-slot]', '#<?= $_table ?>').each( (i, td) => {

      let _td = $(td);
      if ( '' == _td.html()) {

        let _data = _td.data();
        let d = _.dayjs( _data.date);
        if ( d.isValid() && d.unix() > _.dayjs().add(-1, 'month').unix()) {
          let ctrl = $('<i class="bi bi-arrow-clockwise pointer" title="rebook"></i>');
          ctrl.on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            $('#<?= $_modal ?>')
            .trigger('rebook', _data)
            .modal('hide');

          });

          _td.append(ctrl);

        }

      }

    });

    $('#<?= $_uidNew ?>').on( 'click', function( e) {
      e.stopPropagation();e.preventDefault();

      $('#<?= $_modal ?>')
      .trigger('new')
      .modal('hide');

    })

    $('#<?= $_form ?>')
    .on( 'submit', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();
      let _modalBody = $('.modal-body', _form);

      // console.table( _data);

      return false;

    });

  }))( _brayworth_);
  </script>

</form>

