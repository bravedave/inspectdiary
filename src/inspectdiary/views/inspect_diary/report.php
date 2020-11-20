<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<div class="container-fluid">
	<div class="row noscreen">
		<div class="col text-center">
			<h1>

				<a href="<?php url::write('inspect_diary/?filter=lastweek&seed=' . urlencode( $this->data->seed)) ?>">
					<i class="fa fa-chevron-left" style="cursor: pointer"></i></a>

				<?php print $this->title ?>

				<a href="<?php url::write('inspect_diary/?filter=nextweek&seed=' . urlencode( $this->data->seed)) ?>">
					<i class="fa fa-chevron-right"></i></a>

			</h1>

		</div><!-- div class="col-md-6" -->

	</div><!-- div class="row" -->

	<div class="row">
		<div class="col">
			<form class="form" method="POST" action="<?php url::write( 'inspect_diary'); ?>" data-submit="false">
				<input type="hidden" name="property_id" id="property_id" />
				<input type="hidden" name="contact_id" id="contact_id" />
				<input type="hidden" name="contact_mobile" id="contact_mobile" />
				<input type="hidden" name="contact_email" id="contact_email" />

				<input type="hidden" name="action" id="action" value="Save/Update" />
				<table class="table table-striped">
					<colgroup>
						<col style="width: 24px;" /><!-- caret -->
						<col style="width: 24px;" /><!-- # -->
						<col style="width: 80px" /><!-- date -->
						<col style="width: 80px" /><!-- time -->
						<col style="width: 200px" /><!-- address -->
						<col style="width: 60px" /><!-- type -->
						<col /><!-- person -->
						<!-- col style="width: 120px" / --><!-- mobile number -->
						<col style="width: 20px;" /><!-- has appointment -->
					</colgroup>
					<thead>
						<tr>
							<td class="text-center" colspan="2"><i class="fa fa-fw fa-calendar-plus-o" accesskey="N" tabindex="10" data-role="new-inspect-diary-control" title="add new - &lt;alt&gt;+&lt;N&gt;"></i></td>
							<td>date</td>
							<td>time</td>
							<td>address</td>
							<td>type</td>
							<td>person</td>
							<!-- td>mobile</td -->
							<td title="has appointment"><i class="fa fa-calendar-o"></i></td>

						</tr>

					</thead>

					<tbody>
<?php	$i = 0;
		foreach ( $this->data->data as $dto) {	?>
						<tr data-role="item"
							data-id="<?php print $dto->id ?>"
							data-property_id="<?php print $dto->property_id ?>"
							data-property="<?php print $dto->address_street ?>"
							data-person_id="<?php print $dto->contact_id ?>"
							data-property_diary_id="<?php print $dto->pdid ?>"
							data-date="<?php print $dto->date ?>"
							data-time="<?php print date( 'c', strtotime( sprintf( '%s %s', $dto->date, strings::AMPM( $dto->time)))) ?>"
							data-type="<?php print $dto->type ?>"
							data-hasappointment="<?php print $dto->hasappointment ?>"
							>

							<td><i class="fa fa-fw fa-caret-right" data-role="inspect-viewer"></i></td>
							<td class="text-center"><?php print ++$i ?></td>
							<td data-role="date"><?php print strings::asShortDate( $dto->date) ?></td>
							<td data-role="time"><?php print strings::AMPM( $dto->time) ?></td>
							<td data-role="address_street"><div class="truncate"><?php print $dto->address_street ?></div></td>
							<td data-role="type"><?php
								if ( 'OH Inspect' == $dto->type)
									print 'OH';
								elseif ( 'Inspect' == $dto->type)
									print 'Insp';
								else
									print $dto->type ?></td>
							<td data-role="contact_name"><div class="truncate"><?php if ( $dto->type == 'Inspect') print $dto->contact_name ?></div></td>
							<!-- td data-role="contact_mobile"><?php if ( $dto->type == 'Inspect') print $dto->contact_mobile ?></td -->
							<td data-role="hasappointment"><?php if ( $dto->hasappointment) print config::$HTML_TICK ?></td>

						</tr>
<?php	}	// foreach ( $this->data as $dto)	?>

					</tbody>

					<tfoot>
						<tr>
							<td colspan="8">
								<a href="#" class="button button-raised" data-role="insect-report-view-all">View All</a>

							</td>

						</tr>

					</tfoot>

				</table>

			</form>

		</div><!-- div class="col-md-6" -->

	</div><!-- div class="row" -->

</div><!-- div class="container" -->
<script>
$(document).ready( function() {
	_cms_.inspect.report.inspectViewers();

	$('tr[data-role="item"]').each( function( i, el) {
		var tr = $(el);
		var editURL = _cms_.urlwrite('inspect_diary/edit/' + tr.data( 'id'));

		tr.css( 'cursor', 'pointer')
		.on( 'click', function( e) {
			//~ window.location.href = editURL;
			$('i[data-role="inspect-viewer"]', tr).trigger( 'click', e);

		})
		.on( 'contextmenu', function( e) {
			if ( e.shiftKey)
				return;

			e.stopPropagation(); e.preventDefault();

			_brayworth_.hideContexts();

			var _context = _brayworth_.context();

			_context.append( $('<a href="#"><i class="fa fa-fw fa-table"></i><strong>Inspect Matrix</strong></a>').on( 'click', function( e) {
				_context.close();
				$('i[data-role="inspect-viewer"]', tr).trigger( 'click');

			}));

			_context.append( '<hr />');

			_context.append( $('<a><i class="fa fa-fw fa-pencil"></i>Open Record</a>').attr( 'href', editURL));

			_context.append( $('<a href="#"><i class="fa fa-fw fa-clone"></i>clone record</a>').on('click', function(evt) {
				_context.close();
				e.stopPropagation(); e.preventDefault();

				popNewDiaryEntry({ url: _cms_.urlwrite( 'inspect_diary/edit/0/frame?clone=' + tr.data( 'id'))});

			}));

			if ( tr.data('hasappointment') == 1 ) {
				_context.append( $('<a href="#"><i class="fa fa-fw fa-calendar"></i>edit appointment</a>').on('click', function(e) {
					_context.close();
					e.stopPropagation(); e.preventDefault();

					_cms_.appointment.edit( tr.data('property_diary_id'));

				}));

			}
			else {
				_context.append( $('<a href="#"><i class="fa fa-fw fa-calendar-plus-o"></i>create appointment</a>').on('click', function(evt) {
					_context.close();
					e.stopPropagation(); e.preventDefault();

					var m = _cms_.moment( tr.data('time'));

					var data = {
						inspect_diary_id : tr.data('id'),
						isEntry : true,
						subject : ( tr.data('type') == 'Inspect' ? 'Buy Insp' : 'OH Insp'),
						person : $('td[data-role="contact_name"]', tr).text(),
						person_id : tr.data( 'person_id'),
						location : $('td[data-role="address_street"]', tr).text(),
						property_id : tr.data( 'property_id'),
						startUTC : m.format(),
						endUTC : m.add(30,'minutes').format(),
						use_ajax : 'yes',
						onPost : function() {
							_cms_.inspect.report._id_refresh( tr);

						}

					}
					//~ console.log( data);
					_cms_.appointment.POP.call( data);

				}));

			}

			_context.open( e);

		})

		//~ var date = $('>td[data-role="date"]', tr).html();
		//~ if ( dates.indexOf(date) < 0)
			//~ dates.push( date);

		//~ console.log( 'date', date);

	})

	//~ var d = Cookies.get('inspect-diary');	//, { expires: 30 });
	//~ if ( d != undefined)
		//~ $('#date-selector').val( d).trigger('change');
	//~ else if ( dates.length > 0)
		//~ $('#date-selector').val( dates[0]).trigger('change');

	//~ $('#date-selector')
		//~ .autocomplete({ source : dates })
		//~ .on( 'focus', function() { this.select() });

	/*--- ---[ new entry controls ]--- ---*/
	function popNewDiaryEntry( params) {
		var modal;
		var options = {
			url: _cms_.urlwrite( 'inspect_diary/edit/0/frame'),
			width: 680,
			height: 460,
			onLoad : function() {
				this.closeFrame = function() {
					modal.modal('close');

				}

			},

			onClose : function() {
				window.location.reload();

			}

		}

		$.extend( options, params);
		modal = _cms_.modal.browser(options);

	};

	$('i[data-role="new-inspect-diary-control"]').css({'cursor':'pointer'})
	.on('click', function( e) { popNewDiaryEntry(); })
	.on( 'keydown', function( e) {
		if ( e.keyCode == _cms_.keyCode.space) {
			popNewDiaryEntry();
			return ( false);

		}

	});

	/*--- ---[ end : new entry controls ]--- ---*/

})
</script>
