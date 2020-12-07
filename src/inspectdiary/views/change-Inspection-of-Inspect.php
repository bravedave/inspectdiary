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

$dto = $this->data->dto;  ?>

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
  <input type="hidden" name="id" value="<?= $dto->id ?>">
  <input type="hidden" name="action" value="change-inspection-of-inspect">

  <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-secondary text-white py-2">
          <h5 class="modal-title" id="<?= $_modal ?>Label"><?= $this->title ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>

        </div>

        <div class="modal-body">
        <?php
        foreach ($this->data->inspects as $inspect) { ?>
          <div class="form-row row mb-2">
            <div class="col-7">
              <div class="form-check">
                <input type="radio" class="form-check-input"
                  name="inspect_diary_id"
                  value="<?= $inspect->id ?>"
                  <?php if ( $dto->inspect_diary_id == $inspect->id) print 'checked'; ?>
                  id="<?= $uid = strings::rand() ?>">

                <label class="form-check-label" for="<?= $uid ?>">
                  <?= $inspect->address_street ?>

                </label>

              </div>

            </div>

            <div class="col">
              <?= strings::asShortDate( $inspect->date) ?> -
              <?= strings::AMPM( $inspect->time) ?></div>

          </div>

        <?php
        }
        ?>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>

        </div>

      </div>

    </div>

  </div>
  <script>
  ( _ => $(document).ready( () => {
    $('#<?= $_form ?>')
    .on( 'submit', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();

      _.post({
        url : _.url('<?= $this->route ?>'),
        data : _data,

      }).then( d => {
        if ( 'ack' == d.response) {
          $('#<?= $_modal ?>').trigger( 'success');

        }
        else {
          _.growl( d);

        }
        $('#<?= $_modal ?>').modal( 'hide');

      });

      return false;

    });
  }))( _brayworth_);

  </script>

</form>
