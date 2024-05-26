(function ($) {

  $('.wooccm-premium-field').closest('tr').addClass('wooccm-premium');

  function date_picker_select(datepicker) {
    var option = $(datepicker).next().is('.hasDatepicker') ? 'minDate' : 'maxDate',
            otherDateField = 'minDate' === option ? $(datepicker).next() : $(datepicker).prev(),
            date = $(datepicker).datepicker('getDate');

    $(otherDateField).datepicker('option', option, date);
    $(datepicker).change();
  }

  function getEnhancedSelectFormatString() {
    return {
      'language': {
        errorLoading: function () {
          // Workaround for https://github.com/select2/select2/issues/4355 instead of i18n_ajax_error.
          return wc_enhanced_select_params.i18n_searching;
        },
        inputTooLong: function (args) {
          var overChars = args.input.length - args.maximum;

          if (1 === overChars) {
            return wc_enhanced_select_params.i18n_input_too_long_1;
          }

          return wc_enhanced_select_params.i18n_input_too_long_n.replace('%qty%', overChars);
        },
        inputTooShort: function (args) {
          var remainingChars = args.minimum - args.input.length;

          if (1 === remainingChars) {
            return wc_enhanced_select_params.i18n_input_too_short_1;
          }

          return wc_enhanced_select_params.i18n_input_too_short_n.replace('%qty%', remainingChars);
        },
        loadingMore: function () {
          return wc_enhanced_select_params.i18n_load_more;
        },
        maximumSelected: function (args) {
          if (args.maximum === 1) {
            return wc_enhanced_select_params.i18n_selection_too_long_1;
          }

          return wc_enhanced_select_params.i18n_selection_too_long_n.replace('%qty%', args.maximum);
        },
        noResults: function () {
          return wc_enhanced_select_params.i18n_no_matches;
        },
        searching: function () {
          return wc_enhanced_select_params.i18n_searching;
        }
      }
    };
  }

  $(document).on('wooccm-enhanced-between-dates', function (e) {

    $('.wooccm-enhanced-between-dates').filter(':not(.enhanced)').each(function () {

      $(this).find('input').datepicker({
        defaultDate: '',
        dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        showButtonPanel: true,
        onSelect: function () {
          date_picker_select($(this));
        }
      });

      $(this).find('input').each(function () {
        date_picker_select($(this));
      });

    });

  });

  $(document).on('wooccm-enhanced-options', function (e) {

    $('table.wc_gateways tbody').sortable({
      items: 'tr',
      cursor: 'move',
      axis: 'y',
      handle: 'td.sort',
      scrollSensitivity: 40,
      helper: function (event, ui) {
        ui.children().each(function () {
          $(this).width($(this).width());
        });
        ui.css('left', '0');
        return ui;
      },
      start: function (event, ui) {
        ui.item.css('background-color', '#f6f6f6');
      },
      stop: function (event, ui) {
        ui.item.removeAttr('style');
        ui.item.trigger('updateMoveButtons');
      },
      update: function (event, ui) {
        $(this).find('tr').each(function (i, tr) {
          $(tr).find('input.add-order').val(i).trigger('change');
        });
      }
    });

    $('.wooccm-enhanced-options').each(function () {

      var $table = $(this),
              $add = $table.find('.add-option'),
              $remove = $table.find('.remove-options');

      $add.on('click', function (e) {

        var $tr = $table.find('tbody > tr'),
                id = $tr.length,
                tr = $tr.first().clone().html().replace(/options\[([0-9]+)\]/g, 'options[' + id + ']').replace('disabled="disabled"', '').replace('checked="checked"', '').replace('<input value="0"', '<input value="' + id + '"').replace('<input value="0"', '<input value="' + id + '"');

        $tr.last().after($('<tr>' + tr + '</tr>')).find('input').trigger('change');

        $remove.removeProp('disabled');

      });

      $remove.on('click', function (e) {

        $table.find('tr > td.check-column input:checked').closest('tr').remove();

        var $tr = $table.find('tbody > tr');

        if ($tr.length < 2) {
          $(this).prop('disabled', true);
        }
      });

    });

  });

  $(document).on('wooccm-enhanced-select', function (e) {

    $('.wooccm-enhanced-select').filter(':not(.enhanced)').each(function () {
      var select2_args = $.extend({
        minimumResultsForSearch: 10,
        allowClear: $(this).data('allow_clear') ? true : false,
        placeholder: $(this).data('placeholder')
      }, getEnhancedSelectFormatString());

      $(this).selectWoo(select2_args).addClass('enhanced');
    });

    $('.wooccm-product-search').filter(':not(.enhanced)').each(function () {

      var select2_args = {
        allowClear: $(this).data('allow_clear') ? true : false,
        placeholder: $(this).data('placeholder'),
        minimumInputLength: $(this).data('minimum_input_length') ? $(this).data('minimum_input_length') : '3',
        escapeMarkup: function (m) {
          return m;
        },
        ajax: {
          url: wc_enhanced_select_params.ajax_url,
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              term: params.term,
              action: $(this).data('action') || 'wooccm_select_search_products',
              //nonce: wooccm_admin.nonce,              
              security: wc_enhanced_select_params.search_products_nonce,
              selected: $(this).select2('val') || 0,
              exclude: $(this).data('exclude'),
              include: $(this).data('include'),
              limit: $(this).data('limit'),
              display_stock: $(this).data('display_stock')
            };
          },
          processResults: function (data) {
            var terms = [];
            if (data) {
              $.each(data, function (id, text) {
                terms.push({id: id, text: text});
              });
            }
            return {
              results: terms
            };
          },
          cache: true
        }
      };

      select2_args = $.extend(select2_args, getEnhancedSelectFormatString());

      $(this).selectWoo(select2_args).addClass('enhanced');

      if ($(this).data('sortable')) {
        var $select = $(this);
        var $list = $(this).next('.select2-container').find('ul.select2-selection__rendered');

        $list.sortable({
          placeholder: 'ui-state-highlight select2-selection__choice',
          forcePlaceholderSize: true,
          items: 'li:not(.select2-search__field)',
          tolerance: 'pointer',
          stop: function () {
            $($list.find('.select2-selection__choice').get().reverse()).each(function () {
              var id = $(this).data('data').id;
              var option = $select.find('option[value="' + id + '"]')[0];
              $select.prepend(option);
            });
          }
        });
        // Keep multiselects ordered alphabetically if they are not sortable.
      } else if ($(this).prop('multiple')) {
        $(this).on('change', function () {
          var $children = $(this).children();
          $children.sort(function (a, b) {
            var atext = a.text.toLowerCase();
            var btext = b.text.toLowerCase();

            if (atext > btext) {
              return 1;
            }
            if (atext < btext) {
              return -1;
            }
            return 0;
          });
          $(this).html($children);
        });
      }
    });

  });

  $('.wooccm-enhanced-search').filter(':not(.enhanced)').each(function () {

    var select2_args = {
      allowClear: $(this).data('allow_clear') ? true : false,
      placeholder: $(this).data('placeholder'),
      minimumInputLength: $(this).data('minimum_input_length') || '3',
      escapeMarkup: function (m) {
        return m;
      },
      ajax: {
        url: wooccm_admin.ajax_url,
        dataType: 'json',
        cache: true,
        delay: 250,
        data: function (params) {
          return {
            term: params.term,
            key: $(this).data('key'),
            action: 'wooccm_search_field',
            nonce: wooccm_admin.nonce,
          };
        },
        processResults: function (data, params) {
          var terms = [];
          if (data) {
            $.each(data, function (id, text) {
              terms.push({id: id, text: text});
            });
          }
          return {results: terms};
        }
      }
    };

    select2_args = $.extend(select2_args, getEnhancedSelectFormatString());

    $(this).select2(select2_args).addClass('enhanced');
  });

})(jQuery);