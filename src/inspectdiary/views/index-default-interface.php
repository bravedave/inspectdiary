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
use strings;	?>

<div class="nav flex-column">
	<div class="nav-item">
		<div class="nav-link">
			<div class="form-check">
				<input type="checkbox" class="form-check-input" name="use-this-interfacce"
					<?php if ( 'yes' == currentUser::option( 'inspect-interface-modern')) print 'checked'; ?>
					id="<?= $_uid = strings::rand()  ?>">

				<label class="form-check-label" for="<?= $_uid ?>">
					Use this Interface (hide other versions of inspect)

				</label>

			</div>

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
				action : 'set-inspect-interface-modern',
				value : _me.prop('checked') ? 'yes' : ''

			},

		}).then( d => _.growl( d));

	});

}))( _brayworth_);
</script>