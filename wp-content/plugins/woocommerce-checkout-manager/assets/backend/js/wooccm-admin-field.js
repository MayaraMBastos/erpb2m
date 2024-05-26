(function ($) {

  var count = 0,
          timer;

  var is_blocked = function ($node) {
    return $node.is('.processing') || $node.parents('.processing').length;
  };
  var block = function () {
    $('#wooccm_modal').addClass('processing');
  };
  var unblock = function () {
    $('#wooccm_modal').removeClass('processing');
  };

  _.mixin({
    sortOptions: function (object) {
      return _.sortBy(object, function (o) {
        return o.order;
      });
    }
  });
  var FieldViewTabs = Backbone.View.extend({
    templates: {},
    initialize: function () {
      this.templates.window = wp.template('wooccm-modal-tabs');
    },
    render: function () {
      this.model.attributes.panel = 'general';
      this.$el.html(this.templates.window(this.model.attributes));
      //this.$el.trigger('wooccm-tab-panels');
    }
  });
  var FieldViewPanels = Backbone.View.extend({
    templates: {},
    initialize: function () {
      this.templates.window = wp.template('wooccm-modal-panels');
    },
    render: function () {
      this.$el.html(this.templates.window(this.model.attributes));
      this.$el.trigger('wooccm-enhanced-options');
      this.$el.trigger('wooccm-enhanced-select');
      this.$el.trigger('init_tooltips');
    }
  });
  var FieldViewInfo = Backbone.View.extend({
    templates: {},
    initialize: function () {
      this.templates.window = wp.template('wooccm-modal-info');
    },
    render: function () {
      this.$el.html(this.templates.window(this.model.attributes));
      this.$el.trigger('wooccm-enhanced-select');
      this.$el.trigger('init_tooltips');
    }
  });
  var FieldView = Backbone.View.extend({
    events: {
      'change input': 'enableSave',
      'change textarea': 'enableSave',
      'change select': 'enableSave',
      'click .media-modal-backdrop': 'close',
      'click .media-modal-close': 'close',
      'click .media-modal-prev': 'edit',
      'click .media-modal-next': 'edit',
      'click .media-modal-tab': 'tab',
      'change .media-modal-parent': 'parent',
      'change .media-modal-render-tabs': 'renderTabs',
      'change .media-modal-render-panels': 'renderPanels',
      'change .media-modal-render-info': 'renderInfo',
      'submit .media-modal-form': 'submit',
    },
    templates: {},
    initialize: function () {
      _.bindAll(this, 'tab', 'open', 'edit', 'parent', 'load', 'render', 'close', 'submit');
      this.init();
      this.open();
    },
    init: function () {
      this.templates.window = wp.template('wooccm-modal-main');
    },
    assign: function (view, selector) {
      view.setElement(this.$(selector)).render();
    },
    render: function () {
      var modal = this;
      modal.$el.html(modal.templates.window(modal.model.attributes));
      this.tabs = new FieldViewTabs({model: modal.model});
      this.panels = new FieldViewPanels({model: modal.model});
      this.info = new FieldViewInfo({model: modal.model});
      this.assign(this.tabs, '#wooccm-modal-tabs');
      this.assign(this.panels, '#wooccm-modal-panels');
      this.assign(this.info, '#wooccm-modal-info');
    },
    load: function () {

      var modal = this;

      block();

      $.ajax({
        url: wooccm_field.ajax_url,
        data: {
          action: 'wooccm_load_field',
          nonce: wooccm_field.nonce,
          field_id: this.model.attributes.id
        },
        dataType: 'json',
        type: 'POST',
        beforeSend: function () {
        },
        complete: function () {
          unblock();
        },
        error: function () {
          alert('Error!');
        },
        success: function (response) {
          if (response.success) {
            modal.model.set(response.data);
            modal.render();
          } else {
            alert(response.data);
          }
        }
      });
    },
    edit: function (e) {
      e.preventDefault();
      var modal = this,
              $button = $(e.target),
              field_count = parseInt($('.wc_gateways tr[data-field_id]').length),
              order = parseInt(modal.model.get('order'));
      count++;
      if (timer) {
        clearTimeout(timer);
      }

      timer = setTimeout(function () {

        if ($button.hasClass('media-modal-next')) {
          order = Math.min(order + count, field_count);
        } else {
          order = Math.max(order - count, 1);
        }

        modal.model.set({
          id: parseInt($('.wc_gateways tr[data-field_order=' + order + ']').data('field_id'))
        });
        count = 0;
        modal.load();
      }, 300);
    },
    open: function (e) {
      $('body').addClass('modal-open').append(this.$el);
      if (this.model.attributes.id == undefined) {
        _.delay(function () {
          unblock();
        }, 100);
        return;
      }
      this.load();
    },
    updateModel: function (e) {
      e.preventDefault();

      var $field = $(e.target),
              name = $field.attr('name'),
              value = $field.val();

      if (e.target.type === 'checkbox') {
        value = $field.prop('checked') === true ? 1 : 0;
      }
      
      this.model.attributes[name] = value;
      this.model.changed[name] = value;
    },
    tab: function (e) {
      e.preventDefault();

      var modal = this,
              $modal = modal.$el.find('#wooccm_modal'),
              $tab = $(e.currentTarget),
              $tabs = $modal.find('ul.wc-tabs'),
              panel = $tab.find('a').attr('href').replace('#', '');

      $tabs.find('.active').removeClass('active');
      $tab.addClass('active');

      this.model.attributes['panel'] = panel;
      this.model.changed['panel'] = panel;

      this.renderPanels(e);
    },
    renderTabs: function (e) {
      this.renderPanels(e);
      this.tabs.render();
    },
    renderPanels: function (e) {
      this.updateModel(e);
      this.panels.render();
    },
    renderInfo: function () {
      this.info.render();
    },
    close: function (e) {
      e.preventDefault();
      this.undelegateEvents();
      $(document).off('focusin');
      $('body').removeClass('modal-open');
      this.remove();
    },
    parent: function (e) {
      e.preventDefault();
      var modal = this,
              $modal = modal.$el.find('#wooccm_modal'),
              $details = $modal.find('.attachment-details');
      this.updateModel(e);
      $.ajax({
        url: wooccm_field.ajax_url,
        data: {
          action: 'wooccm_load_parent',
          nonce: wooccm_field.nonce,
          conditional_parent_key: modal.model.attributes.conditional_parent_key
        },
        dataType: 'json',
        type: 'POST',
        beforeSend: function () {
          modal.disableSave();
          $details.addClass('save-waiting');
        },
        complete: function () {
          $details.addClass('save-complete');
          $details.removeClass('save-waiting');
          modal.enableSave();
        },
        error: function () {
          alert('Error!');
        },
        success: function (response) {
          if (response.success) {
            modal.model.attributes['parent'] = response.data;
            modal.model.changed['parent'] = response.data;
            modal.renderInfo();
          } else {
            alert(response.data);
          }
        }
      });
      return false;
    },
    reload: function (e) {
      if (this.$el.find('#wooccm_modal').hasClass('reload')) {
        location.reload();
        return;
      }
      this.remove();
      return;
    },
    close: function (e) {
      e.preventDefault();
      this.undelegateEvents();
      $(document).off('focusin');
      $('body').removeClass('modal-open');
      this.reload(e);
      return;
    },
    enableSave: function (e) {
      $('.media-modal-submit').removeProp('disabled');
    },
    disableSave: function (e) {
      $('.media-modal-submit').prop('disabled', true);
    },
    submit: function (e) {
      e.preventDefault();
      var modal = this,
              $modal = modal.$el.find('#wooccm_modal'),
              $details = $modal.find('.attachment-details');

      $.ajax({
        url: wooccm_field.ajax_url,
        data: {
          action: 'wooccm_save_field',
          nonce: wooccm_field.nonce,
          field_id: modal.model.attributes.id,
          field_data: $('form', this.$el).serialize()
        },
        dataType: 'json',
        type: 'POST',
        beforeSend: function () {
          $('.media-modal-submit').prop('disabled', true);
          $details.addClass('save-waiting');
          block();
        },
        complete: function () {
          $details.addClass('save-complete');
          $details.removeClass('save-waiting');
          unblock();
        },
        error: function () {
          alert('Error!');
        },
        success: function (response) {
          if (response.success) {

            if (modal.model.attributes.id == undefined) {
              $modal.addClass('reload');
              modal.close(e);
            }

            //re-render dont load select2 saved options
            modal.model.set(response.data);
            //$modal.addClass('reload');

          } else {
            alert(response.data);
          }
        }
      });
      return false;
    }
  });
  var FieldModel = Backbone.Model.extend({
    defaults: wooccm_field.args
  });
  var FieldModal = Backbone.View.extend({
    initialize: function (e) {

      var $button = $(e.target),
              field_id = $button.closest('[data-field_id]').data('field_id');
      var model = new FieldModel();
      model.set({
        id: field_id
      });
      new FieldView({
        model: model
      }).render();
    },
  });
  $('#wooccm_billing_settings_add, #wooccm_shipping_settings_add, #wooccm_additional_settings_add').on('click', function (e) {
    e.preventDefault();
    new FieldModal(e);
  });
  $('#wooccm_billing_settings_reset, #wooccm_shipping_settings_reset, #wooccm_additional_settings_reset').on('click', function (e) {
    e.preventDefault();
    var $button = $(e.target);
    var c = confirm(wooccm_field.message.reset);
    if (!c) {
      return false;
    }

    $.ajax({
      url: wooccm_field.ajax_url,
      data: {
        action: 'wooccm_reset_fields',
        nonce: wooccm_field.nonce
      },
      dataType: 'json',
      type: 'POST',
      beforeSend: function () {
      },
      complete: function () {
      },
      error: function () {
        alert('Error!');
      },
      success: function (response) {
        if (response.success) {

          location.reload();
        } else {
          alert(response.data);
        }
      }
    });
    return false;
  });
  $('.wooccm_billing_settings_edit, .wooccm_shipping_settings_edit, .wooccm_additional_settings_edit').on('click', function (e) {
    e.preventDefault();
    new FieldModal(e);
  });
  $('.wooccm_billing_settings_delete, .wooccm_shipping_settings_delete, .wooccm_additional_settings_delete').on('click', function (e) {
    e.preventDefault();
    var $button = $(e.target),
            $field = $button.closest('[data-field_id]'),
            field_id = $field.data('field_id');
    var c = confirm(wooccm_field.message.remove);
    if (!c) {
      return false;
    }

    $.ajax({
      url: wooccm_field.ajax_url,
      data: {
        action: 'wooccm_delete_field',
        nonce: wooccm_field.nonce,
        field_id: field_id,
      },
      dataType: 'json',
      type: 'POST',
      beforeSend: function () {
      },
      complete: function () {
      },
      error: function () {
        alert('Error!');
      },
      success: function (response) {
        if (response.success) {

          $field.remove();
        } else {
          alert(response.data);
        }
      }
    });
    return false;
  });
  $(document).on('click', '.wooccm-field-toggle-attribute', function (e) {
    e.preventDefault();
    var $link = $(this),
            $tr = $link.closest('tr'),
            $toggle = $link.find('.woocommerce-input-toggle');
    $.ajax({
      url: wooccm_field.ajax_url,
      data: {
        action: 'wooccm_toggle_field_attribute',
        nonce: wooccm_field.nonce,
        field_attr: $(this).data('field_attr'),
        field_id: $tr.data('field_id')
      },
      dataType: 'json',
      type: 'POST',
      beforeSend: function (response) {
        $toggle.addClass('woocommerce-input-toggle--loading');
      },
      success: function (response) {

        if (true === response.data) {
          $toggle.removeClass('woocommerce-input-toggle--enabled, woocommerce-input-toggle--disabled');
          $toggle.addClass('woocommerce-input-toggle--enabled');
          $toggle.removeClass('woocommerce-input-toggle--loading');
        } else if (true !== response.data) {
          $toggle.removeClass('woocommerce-input-toggle--enabled, woocommerce-input-toggle--disabled');
          $toggle.addClass('woocommerce-input-toggle--disabled');
          $toggle.removeClass('woocommerce-input-toggle--loading');
        } //else if ('needs_setup' === response.data) {
        //window.location.href = $link.attr('href');
        //}
      }

    });
    return false;
  });
  $(document).on('change', '.wooccm-field-change-attribute', function (e) {
    e.preventDefault();
    var $change = $(this),
            $tr = $change.closest('tr');
    $.ajax({
      url: wooccm_field.ajax_url,
      data: {
        action: 'wooccm_change_field_attribute',
        nonce: wooccm_field.nonce,
        field_attr: $change.data('field_attr'),
        field_value: $change.val(),
        field_id: $tr.data('field_id'),
      },
      dataType: 'json',
      type: 'POST',
      beforeSend: function (response) {
        $change.prop('disabled', true);
      },
      success: function (response) {
        console.log(response.data);
      },
      complete: function (response) {
        $change.prop('disabled', false);
      },
    });
    return false;
  });
})(jQuery);