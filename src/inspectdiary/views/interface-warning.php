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

use currentUser;
use strings; ?>

<div class="row mt-4">
  <div class="offset-md-2 col-md-8">
    <div class="alert alert-warning alert-dismissible fade show">
      <p><strong>caution!</strong> This work is NOT fully compatible with inspect<sup>v1</sup></p>

      <p>Missing data is populated by running the <em>inspect diary</em> report -
      so normally should not be a problem, but if you use the mobile inspect App
      without running the report, data will not appear</p>

			<div class="form-check">
				<input type="checkbox" class="form-check-input" name="use-this-interfacce"
					<?php if ( !currentUser::option( 'inspect-interface')) print 'checked'; ?>
					id="<?= $_uid = strings::rand()  ?>">

				<label class="form-check-label" for="<?= $_uid ?>">
					Use this Interface
					<div><em class="text-muted">(hide other versions of inspect)</em></div>

				</label>

			</div>

      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>

    </div>

  </div>

</div>
<script>
( _ => $(document).ready( () => {
	$('#<?= $_uid ?>').on('change', function(e) {
		let _me = $(this);

		_.post({
			url : _.url('<?= $this->route ?>'),
			data : {
				action : 'set-inspect-interface',
				value : _me.prop('checked') ? '' : 'legacy'

			},

		}).then( d => {
      _.growl( d);
      _me.closest('.alert').alert('close');

    });

	});

}))( _brayworth_);
</script>