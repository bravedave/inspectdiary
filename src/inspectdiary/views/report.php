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
$_candidates = strings::rand();
$_candidate = strings::rand();
$_propertyContact = strings::rand();

$showInspections = config::$INSPECTDIARY_ENABLE_SINGULAR_INSPECTION;
foreach ($this->data->data as $dto) {
  if (config::inspectdiary_inspection == $dto->type) {
    $showInspections = true;
  }
}

?>
<style>
  .border-1 {
    border-width: .1rem !important;
  }

  .border-2 {
    border-width: .2rem !important;
  }

  .border-3 {
    border-width: .3rem !important;
  }

  .border-4 {
    border-width: .4rem !important;
  }

  @media screen and (max-width: 767px) {
    .navbar-brand {
      max-width: 50%;
      font-size: 1rem;
      padding-top: .5rem;
    }

  }
</style>

<div id="<?= $_collapse = strings::rand() ?>" style="margin-left: -15px; margin-right: -15px;">
  <div class="collapse" id="<?= $_propertyContact ?>" data-parent="#<?= $_collapse ?>">
    <nav class="<?= $this->theme['navbar'] ?> py-1" style="padding-left: 15px; padding-right: 15px;">
      <div class="d-flex flex-fill">
        <div class="navbar-brand mr-auto text-truncate" id="<?= $_title = strings::rand() ?>-property-contact">Property Contact</div>

        <button type="button" class="btn <?= $this->theme['navbutton'] ?>" aria-label="Close" data-toggle="collapse" id="<?= $_propertyContact ?>-goto-list"><?= icon::get(icon::list) ?></button>

      </div>

    </nav>

    <div id="<?= $_propertyContact ?>content" class="container-fluid pt-1"></div>

  </div>

  <div class="collapse" id="<?= $_candidate ?>" data-parent="#<?= $_collapse ?>">
    <nav class="<?= $this->theme['navbar'] ?> border-bottom border-3 py-1" style="padding-left: 15px; padding-right: 15px;">
      <div class="d-flex flex-fill">
        <div class="navbar-brand mr-auto text-truncate" id="<?= $_title ?>-candidate">Candidate</div>

        <button type="button" class="btn <?= $this->theme['navbutton'] ?> d-none" data-toggle="collapse" id="<?= $_docsButton = strings::rand() ?>"><i class="bi bi-files"></i><span class="d-none d-md-inline"> docs</span></button>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?> d-none" aria-label="context menu" id="<?= $_contextCandidate = strings::rand() ?>"><?= icon::get(icon::menu_up) ?></button>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?>" title="add inspection" id="<?= $_addInspection = strings::rand() ?>-candidate"><?= icon::get(icon::person_plus) ?></button>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?>" aria-label="Close" data-toggle="collapse" id="<?= $_candidate ?>-goto-list"><?= icon::get(icon::list) ?></button>

      </div>

    </nav>

    <div id="<?= $_candidate ?>content" class="container-fluid pt-1"></div>

  </div>

  <div class="collapse" id="<?= $_candidates ?>" data-parent="#<?= $_collapse ?>">
    <nav class="<?= $this->theme['navbar'] ?> py-1" style="padding-left: 15px; padding-right: 15px;">
      <div class="d-flex flex-fill">
        <div class="navbar-brand mr-auto text-truncate" id="<?= $_title ?>-candidates">Inspection</div>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?> d-none" aria-label="context menu" id="<?= $_context = strings::rand() ?>"><i class="bi bi-menu-up"></i></button>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?> js-csv d-none" title="download as csv"><i class="bi bi-filetype-csv"></i></button>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?>" title="add inspection" id="<?= $_addInspection ?>-candidates"><i class="bi bi-person-plus"></i></button>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?>" aria-label="Close" data-toggle="collapse" data-target="#<?= $_report ?>"><i class="bi bi-x"></i></button>

      </div>

    </nav>

    <div id="<?= $_candidates ?>content" class="container-fluid"></div>

  </div>

  <div class="collapse" id="<?= $_report ?>" data-parent="#<?= $_collapse ?>">
    <?php
    $_uid = strings::rand();
    ?>

    <div class="container-fluid">
      <div class="row" id="<?= $_uid ?>RentalDiary">
        <div class="col">
          <div class="form-row bg-light border-bottom">
            <div class="col-3">
              <div class="form-row">
                <div class="d-none d-md-block col-1 text-center small">
                  <?= count($this->data->data) ?>
                </div>

                <div class="col">date</div>

              </div>

            </div>
            <div class="col-7 col-md<?= $showInspections ? '-3' : '' ?>">address</div>
            <div class="d-none d-md-block col-md-2 col-xl-1">team</div>
            <div class="col-2">
              <!-- type -->
              <div class="row">
                <div class="col text-center <?= !$showInspections ? 'd-none' : '' ?>">type</div>
                <div class="col d-none d-md-block text-center">no.</div>
                <div class="col d-none d-md-block text-center">inv.</div>

              </div>

            </div>

            <div class="col d-none <?= $showInspections ? 'd-md-block' : '' ?>">person</div>

          </div>

          <?php
          $i = 0;
          foreach ($this->data->data as $dto) {  ?>
            <div class="form-row py-1 border-bottom" data-role="item" data-id="<?= $dto->id ?>" data-property_id="<?= $dto->property_id ?>" data-address_street=<?= json_encode($dto->address_street, JSON_UNESCAPED_SLASHES) ?> data-pretty_street=<?= json_encode(strings::GoodStreetString($dto->address_street), JSON_UNESCAPED_SLASHES) ?> data-short_time="<?= rtrim(strings::AMPM($dto->time), 'm') ?>" data-person_id="<?= $dto->contact_id ?>" data-inspect_id="<?= $dto->inspect_id ?>" data-inspections="<?= $dto->inspections ?>" data-type="<?= $dto->type ?>">

              <div class="col-3">
                <div class="form-row">
                  <div class="d-none d-md-block col-1 text-md-center small"><?= ++$i ?></div>
                  <div class="col-md col-xl-4 text-right text-md-left" data-field="date">
                    <?= strings::asShortDate($dto->date) ?>

                  </div>
                  <div class="col-md text-right text-md-left" data-field="time">
                    <?php
                    $time = strings::AMPM($dto->time);
                    if (preg_match('@[0-9][0-9]:00@', $dto->time)) {
                      print $time;
                    } else {
                      print preg_replace('@(am|pm)$@i', '', $time);
                    }

                    ?></div>

                </div>

              </div>

              <div class="col-7 col-md<?= $showInspections ? '-3' : '' ?>">
                <div class="row">
                  <div class="col">
                    <div class="text-truncate" data-field="street">
                      <?= strings::GoodStreetString($dto->address_street) ?>

                    </div>

                  </div>

                </div>

                <div class="row d-md-none">
                  <div class="col text-muted">
                    <div class="text-truncate" data-field="contact_name">
                      <?php if ($dto->type == config::inspectdiary_inspection) print $dto->contact_name; ?>

                    </div>

                  </div>

                </div>

              </div>

              <div class="d-none d-md-block col-md-2 col-xl-1 text-truncate"><?= $dto->team ?></div>

              <!-- type / inspections / investors -->
              <div class="col-2">
                <div class="form-row">
                  <!-- type -->
                  <div class="col text-center <?php if (!$showInspections) print 'd-none'; ?>" data-field="type">
                    <?php
                    if (config::inspectdiary_openhome == $dto->type) {
                      print 'OH';
                    } elseif (config::inspectdiary_inspection == $dto->type) {
                      print 'Insp';
                    } else {
                      print $dto->type;
                    }

                    ?>
                  </div>

                  <!-- inspections  -->
                  <div class="col text-center" inspections><?= $dto->inspections ?></div>

                  <!-- investors  -->
                  <div class="col text-center" investors><?= $dto->investors ?></div>

                </div>

              </div>

              <div class="d-none <?= $showInspections ? 'd-md-block' : '' ?> col text-truncate">
                <?php
                if ($dto->type == config::inspectdiary_inspection) print $dto->contact_name;
                ?></div>

            </div>
          <?php }  /* foreach ( $this->data as $dto) */ ?>

        </div>

      </div>

    </div>

  </div>

</div>

<script>
  (_ => {
    $('#<?= $_collapse ?>')
      .on('hidden.bs.collapse', function(e) {
        let _el = $(e.target);
        let id = _el.attr('id');

        if ('<?= $_candidates ?>' == id) {
          setTimeout(() => {
            $('#<?= $_candidates ?>content').html('');
            $('#<?= $_context ?>').addClass('d-none').off('click');
            $('.js-csv').addClass('d-none');
            // console.log( 'remove people content');

          }, 50);

        } else if ('<?= $_candidate ?>' == id) {
          setTimeout(() => {
            $('#<?= $_candidate ?>content').html('');
            $('#<?= $_contextCandidate ?>').addClass('d-none').off('click');
            // console.log( 'remove candidate content');

          }, 50);

        } else if ('<?= $_propertyContact ?>' == id) {
          setTimeout(() => {
            $('#<?= $_propertyContact ?>content').html('');
            // console.log( 'remove propertyContact content');

          }, 50);

        }

      });

    let resetSaveState = () => {
      return new Promise(resolve => {
        let el = $('#<?= $_candidate ?> .navbar');
        el.removeClass('border-primary border-warning border-danger border-success');
        resolve(el);

      });

    };

    window.documentsButton = () => $('#<?= $_docsButton ?>');

    window.gotoPeopleList = () => {
      $('#<?= $_candidates ?>content').trigger('refresh');
      $('#<?= $_candidates ?>').collapse('show');


    };

    $('#<?= $_candidate ?>-goto-list, #<?= $_propertyContact ?>-goto-list').on('click', function(e) {
      e.stopPropagation();
      _.hideContexts();
      gotoPeopleList();

    });

    $('#<?= $_propertyContact ?>content')
      .on('refresh', function(e) {
        e.stopPropagation();

        let _me = $(this);
        let _data = _me.data();

        _me.html('<div class="d-flex"><div class="spinner-border mx-auto my-5" role="status"><span class="sr-only">Loading...</span></div></div>');

        // console.log( ( _.url( '<?= $this->route ?>/propertycontact/' + _data.id)));

        _.get(_.url('<?= $this->route ?>/propertycontact/' + _data.id))
          .then(html => _me.html(html));

      });

    $('#<?= $_propertyContact ?>')
      .on('view-contact', function(e, id) {
        e.stopPropagation();

        let _me = $(this);
        _me.collapse('show');
        $('#<?= $_propertyContact ?>content').data('id', id);
        $('#<?= $_propertyContact ?>content').trigger('refresh');

      });

    $('#<?= $_candidate ?>')
      .on('add-inspection', function(e) {
        e.stopPropagation();

        let type = $('#<?= $_candidates ?>content').data('type');
        if ('<?= config::inspectdiary_inspection ?>' == String(type)) {
          _.get.modal(_.url('<?= $this->route ?>/noinspectoninspect'));

        } else {

          let inspectionID = $('#<?= $_candidates ?>content').data('id');

          $(this).collapse('show');

          $('#<?= $_candidate ?>content')
            .data('inspect_diary_id', inspectionID)
            .trigger('add-inspection');

        }

      })
      .on('load-candidate', function(e) {
        e.stopPropagation();

        let _me = $(this);
        let _data = _me.data();

        resetSaveState();
        _me.collapse('show');

        $('#<?= $_candidate ?>content')
          .data('id', _data.id)
          .trigger('refresh');

        // console.log('trigger load-candidate ...', _data.id);
      });

    $('#<?= $_candidate ?>content')
      .on('add-inspection', function(e) {
        e.stopPropagation();

        let _me = $(this);
        let _data = _me.data();

        _me.append('<div class="bg-white d-flex position-absolute w-100" style="top: 56px;left: 0;z-index: 1000;height: calc(100%);opacity: .5;"><div class="spinner-border mx-auto my-5" role="status"><span class="sr-only">Loading...</span></div></div>');

        _.get(_.url('<?= $this->route ?>/inspection/?idid=' + _data.inspect_diary_id))
          .then(html => _me.html(html));

      })
      .on('refresh', function(e) {
        e.stopPropagation();

        let _me = $(this);
        let _data = _me.data();

        _me.html('<div class="d-flex"><div class="spinner-border mx-auto my-5" role="status"><span class="sr-only">Loading...</span></div></div>');

        _.get(_.url('<?= $this->route ?>/inspection/' + _data.id))
          .then(html => _me.html(html));

      });

    $('.js-csv').on( 'click', function( e) {
      e.stopPropagation();e.preventDefault();

      $('#<?= $_candidates ?>content').trigger('csv');
    })


    $('#<?= $_candidates ?>content')
      .on('csv', function(e) {

        let _me = $(this);
        let _data = _me.data();

        window.location.href = _.url(`<?= $this->route ?>/inspects/${_data.id}/csv`);
      })
      .on('refresh', function(e) {

        let _me = $(this);
        _me.html('<div class="d-flex"><div class="spinner-border mx-auto my-5" role="status"><span class="sr-only">Loading...</span></div></div>');

        let _data = _me.data();

        _.get(_.url('<?= $this->route ?>/inspects/' + _data.id))
          .then(html => _me.html(html));

      })
      .on('view-inspection', function(e, id) {
        e.stopPropagation();

        // console.log('trigger load-candidate', id);
        $('#<?= $_candidate ?>')
          .data('id', id)
          .trigger('load-candidate');
        // console.log('triggered load-candidate');

      });

    $('#<?= $_addInspection ?>-candidate, #<?= $_addInspection ?>-candidates')
      .on('click', e => {
        e.stopPropagation();
        _.hideContexts();

        $('#<?= $_candidate ?>').trigger('add-inspection');

      });

    window.confirmDeleteAction = () => {
      return new Promise(resolve => {
        _.ask({
          headClass: 'text-white bg-danger',
          text: 'Are you sure ?',
          title: 'Confirm Delete',
          buttons: {
            yes: function(e) {
              $(this).modal('hide');
              resolve();

            }

          }

        });

      });

    };

    window.deleteInspection = id => {
      return new Promise(resolve => {
        _.post({
          url: _.url('<?= $this->route ?>'),
          data: {
            action: 'inspection-delete',
            id: id
          },

        }).then(d => {
          if ('ack' == d.response) {
            resolve();

          } else {
            _.growl(d);

          }

        });

      });

    };

    window.refreshPeople = () => $('#<?= $_candidates ?>content').trigger('refresh');

    window.setPeopleContext = context => {
      $('#<?= $_context ?>')
        .removeClass('d-none')
        .off('click')
        .on('click', context);

      if ( !_.browser.isMobileDevice) $('.js-csv').removeClass('d-none');
    };

    window.setPersonContext = context => {
      $('#<?= $_contextCandidate ?>')
        .removeClass('d-none')
        .off('click')
        .on('click', context);

    };

    window.viewPropertyContact = id => $('#<?= $_propertyContact ?>content').trigger('view-contact', id);

    window.viewInspection = id => $('#<?= $_candidates ?>content').trigger('view-inspection', id);

    $(document)
      .on('candidate-saved', e => {
        resetSaveState().then(el => el.addClass('border-success'));
        $(document).trigger('invalidate-investor-counts');
      })
      .on('candidate-saving', e => resetSaveState().then(el => el.addClass('border-primary')))
      .on('candidate-saving-error', e => resetSaveState().then(el => el.addClass('border-danger')))
      .on('candidate-unsaved', e => resetSaveState().then(el => el.addClass('border-warning')))
      .on('add-inspection', e => $('#<?= $_candidate ?>').trigger('add-inspection'))
      .on('change-inspection-of-inspect', function(e, id) {
        e.stopPropagation();

        _.get.modal(_.url('<?= $this->route ?>/changeInspectionofInspect/' + id))
          .then(modal => modal.on('success', e => {
            $('#<?= $_candidates ?>content').trigger('refresh');
            $(document).trigger('invalidate-counts');

          }));

      })
      .on('delete-inspection-by-id', (e, id) => {
        $('#<?= $_report ?>').collapse('show');

        $('div[data-role="item"]', '#<?= $_uid ?>RentalDiary').each((i, row) => {
          let _row = $(row);
          let _data = _row.data();

          if (id == _data.id) {
            _row.trigger('delete');

            return false;

          }

        });

      })
      .on('edit-inspection-by-id', (e, id) => {
        $('#<?= $_report ?>').collapse('show');

        $('div[data-role="item"]', '#<?= $_uid ?>RentalDiary').each((i, row) => {
          let _row = $(row);
          let _data = _row.data();

          if (id == _data.id) {
            _row.trigger('edit');

            return false;

          }

        });

      })
      .on('invalidate-counts', (e) => {
        $('div[data-role="item"]', '#<?= $_uid ?>RentalDiary')
          .each((i, row) => $('[inspections], [investors]', row).addClass('text-warning'));

      })
      .on('invalidate-investor-counts', (e) => {
        $('div[data-role="item"]', '#<?= $_uid ?>RentalDiary')
          .each((i, row) => $('[investors]', row).addClass('text-warning'));

      })
      .on('load-inspect-add', (e, data) => {

        // console.log( data);
        $('#<?= $_title ?>-candidates, #<?= $_title ?>-candidate, #<?= $_title ?>-property-contact').html(data.pretty_street + ' ' + data.short_time);

        $('#<?= $_candidates ?>content')
          .data('id', data.id)
          .data('type', data.type)
          .trigger('add-inspection');

        $('#<?= $_candidates ?>').collapse('show');

      })
      .on('load-inspects', (e, data) => {

        // console.log( data);
        $('#<?= $_title ?>-candidates, #<?= $_title ?>-candidate, #<?= $_title ?>-property-contact').html(data.pretty_street + ' ' + data.short_time);

        $('#<?= $_candidates ?>content')
          .data('id', data.id)
          .data('type', data.type)
          .trigger('refresh');

        $('#<?= $_candidates ?>').collapse('show');

      })
      .on('refresh-inspects', (e) => $('#<?= $_candidates ?>content').trigger('refresh'))
      .ready(() => {
        $('div[data-role="item"]', '#<?= $_uid ?>RentalDiary').each((i, row) => {

          $(row)
            .addClass('pointer')
            .on('click', function(e) {
              e.stopPropagation();
              e.preventDefault();

              let _me = $(this);
              let _data = _me.data();

              if (_data.inspect_id > 0) {
                _me.trigger('view-inspection');

              } else {
                $(document).trigger('load-inspects', _data);

              }
              // console.log( _data);

            })
            .on('contextmenu', function(e) {
              if (e.shiftKey)
                return;

              e.stopPropagation();
              e.preventDefault();

              _.hideContexts();

              let _me = $(this);
              let _data = _me.data();
              let _context = _.context();

              if ('<?= config::inspectdiary_inspection ?>' == _data.type) {
                if (_data.inspect_id > 0) {
                  _context.append(
                    $('<a class="font-weight-bold" href="#">inspection</a>')
                    .on('click', e => {
                      e.stopPropagation();
                      e.preventDefault();
                      _context.close();

                      _me.trigger('view-inspection');

                    })

                  );

                } else {
                  _context.append(
                    $('<a class="font-weight-bold text-danger" href="#">inspection not found</a>')
                    .on('click', e => {
                      e.stopPropagation();
                      e.preventDefault();
                      _context.close();

                    })

                  );

                }

              } else {
                _context.append($('<a class="font-weight-bold" href="#">inspections</a>').on('click', function(e) {
                  e.stopPropagation();
                  e.preventDefault();

                  _context.close();

                  $(document).trigger('load-inspects', _data);

                }));

              }

              _context.append($('<a href="#"><i class="bi bi-pencil"></i>edit</a>').on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();

                _context.close();
                _me.trigger('edit');

              }));

              if ('<?= config::inspectdiary_inspection ?>' == _data.type || 0 == Number(_data.inspections)) {
                _context.append($('<a href="#"><i class="bi bi-trash"></i>delete</a>').on('click', function(e) {
                  e.stopPropagation();
                  e.preventDefault();

                  _context.close();
                  _me.trigger('delete', _data.inspect_id);

                }));

              }

              _context.append('<hr>');
              _context.append($('<a href="#">view property</a>').on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();

                _context.close();
                _.get.modal(_.url('properties/edit/' + _data.property_id));

              }));

              _context.append($('<a href="#">close</a>').on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();

                _context.close();

              }));

              _context.open(e);

            })
            .on('delete', function(e) {
              let _me = $(this);

              _.ask.alert({
                text: 'Are you sure ?',
                title: 'Confirm Delete',
                buttons: {
                  yes: function(e) {
                    $(this).modal('hide');
                    _me.trigger('delete-confirmed');

                  }

                }

              });

            })
            .on('delete-confirmed', function(e) {
              let _me = $(this);
              let _data = _me.data();

              _.post({
                url: _.url('<?= $this->route ?>'),
                data: {
                  action: 'inspect-diary-delete',
                  id: _data.id
                },

              }).then(d => {
                if ('ack' == d.response) {
                  window.location.reload();

                } else {
                  _.growl(d);

                }

              });

            })
            .on('edit', function(e) {
              let _me = $(this);
              let _data = _me.data();

              _.get.modal(_.url('<?= $this->route ?>/edit/' + _data.id))
                .then(modal => {
                  modal.on('success', () => _me.trigger('refresh'));

                });

            })
            .on('refresh', function(e) {
              let _me = $(this);
              let _data = _me.data();

              _.post({
                url: _.url('inspectdiary'),
                data: {
                  action: 'get-by-id',
                  id: _data.id

                },

              }).then(d => {
                if ('ack' == d.response) {
                  _me.data('property_id', d.data.property_id);
                  _me.data('address_street', d.data.address_street);
                  _me.data('pretty_street', d.data.pretty_street);
                  _me.data('short_time', d.data.shorttime);
                  _me.data('person_id', d.data.contact_id);
                  _me.data('inspect_id', d.data.inspect_id);
                  _me.data('type', d.data.type);

                  $('[data-field="date"]', _me).html(d.data.shortdate);
                  $('[data-field="time"]', _me).html(d.data.shorttime);
                  $('[data-field="street"]', _me).html(d.data.address_street);
                  $('[data-field="contact_name"]', _me).html(d.data.contact_name);
                  if ('<?= config::inspectdiary_openhome ?>' == d.data.type) {
                    $('[data-field="type"]', _me).html('OH');

                  } else if ('<?= config::inspectdiary_inspection ?>' == d.data.type) {
                    $('[data-field="type"]', _me).html('Insp');

                  } else {
                    $('[data-field="type"]', _me).html(d.data.type);

                  }

                  _me.removeClass('bg-warning');

                } else {
                  _.growl(d);

                }

              });

              _me.addClass('bg-warning');

            })
            .on('view-inspection', function(e) {
              e.stopPropagation();

              let _me = $(this);
              let _data = _me.data();

              $('#<?= $_title ?>-candidates, #<?= $_title ?>-candidate, #<?= $_title ?>-property-contact').html(_data.pretty_street + ' ' + _data.short_time);

              $('#<?= $_candidates ?>content')
                .data('id', _data.id)
                .data('type', _data.type);

              $('#<?= $_candidates ?>content').trigger('view-inspection', _data.inspect_id);

            });

        });

        // var someTabTriggerEl = document.querySelector('#someTabTrigger')
        // var tab = new bootstrap.Tab(someTabTriggerEl)

        $('[data-role="content-primary"]')
          .removeClass('pt-3')
          .addClass('pt-0 pt-md-3');

        <?php if ($act = (int)$this->getParam('activate')) { ?>
            (row => {
              if (1 == row.length) {
                let _data = row.data();

                if (_data.inspect_id > 0) {
                  row.trigger('view-inspection');

                } else {

                  <?php if ('yes' == $this->getParam('add')) { ?>
                    $(document).trigger('load-inspect-add', _data);
                    // http://localhost:1900/inspectdiary?filter=thisweek&seed=2021-04-30&activate=20&add=yes
                    history.replaceState({
                      view: 'Inspect Diary'
                    }, 'Inspect Diary', '<?= $this->route ?>')

                  <?php } else { ?>

                    $(document).trigger('load-inspects', _data);
                    console.log('load-inspects');

                  <?php } ?>

                }

              } else {
                $('#<?= $_report ?>').collapse('show');

              }

            })($('div[data-role="item"][data-id="<?= $act ?>"]', '#<?= $_uid ?>RentalDiary'));

        <?php } else { ?>

          $('#<?= $_report ?>').collapse('show');

        <?php } ?>

      });

  })(_brayworth_);
</script>