var thwecmf_settings = (function($, window, document) {
    'use strict';
    var SIDEBAR_HELPER = new Array('configure', 'layouts', 'layout-element', 'settings');
    var BUILDER_ROWS = new Array('one_column', 'two_column');
    var BUILDER_COLS = new Array('one_column_one', 'two_column_one', 'two_column_two');
    var BUILDER_HOOKS = new Array('email_header','email_order_details','before_order_table','after_order_table','order_meta','customer_details','email_footer','downloadable_product');
    var ADDRESS_BLOCKS = new Array('billing_address', 'shipping_address'); 
    var COL_TO_NUM = {'one_column':1, 'two_column':2, 'three_column':3, 'four_column':4};
    var NUM_TO_WORDS = {1:'one',2:'two',3:'three',4:'four'};
    var preview_wrapper;
    var UPLOAD_FILE_TYPES = new Array('image/jpeg', 'image/png', 'image/jpg');
    var BLOCK_DRAGG;
    var TB_DEFAULT = '{"b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"solid","border_color":"#dedede","bg_color":"#ffffff"}';
    var LAYOUT_HELPER={};
    var EMPTY_TD_DATA = '<span class="thwecmf-builder-add-btn thwecmf-btn-add-element">+ Add Element</span>';
    var DEF_COL_PROPS = {"width":"50%","p_t":"10px","p_r":"10px","p_b":"10px","p_l":"10px","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd","bg_color":"none","text_align":"center"};
    var TEST_MAIL_NOTICE = '<span>Please enter a valid email id</span>'; 
    var ELM_CSS_PROP_MAP = thwecmf_admin_var['elm_css_map'];
    var IMG_ELM_DIMENSION = new Array('img_size_width', 'img_size_height');
    var IMG_CONTENT_BLOCKS = new Array('header_details', 'image', 'gif', 'social');

    tbuilder_initialize();
    function tbuilder_initialize(){ 
        preview_wrapper = $('#thwecmf_tbuilder_editor_preview');
        initialize_sidebar_clicks();
        initialize_popups();
        setup_builder_ui();
        resize_scroll_support();
        reload_page_listner();
        template_delete_action();

        $('.thwecmf-template-map-select2').select2();
        $(".thwecmf-template-map-select2.thwecmf-premium-disabled-input").prop("disabled", true);
        $('.thwecmf-tbuilder-elm-wrapper').on('click', '.thwecmf-column-name', function(event) {
            $(this).closest('.thwecmf-columns').find('.thwecmf-element-set').toggle();
        });
        $('.thwecmf-tbuilder-elm-wrapper').on('click', '.thwecmf-row-name', function(event) {
            $(this).closest('.thwecmf-rows').find('.thwecmf-column-set').toggle();
            $(this).closest('.thwecmf-rows').toggleClass('rows-collpase');
        });
        $('.thwecmf-tbuilder-header-panel').on("blur","input",function(){
            if($(this).val() != ""){
                $(this).addClass("has-value");
            }else{
                $(this).removeClass("has-value");
            }
        });
    }

    function validate_temp_name(tname,dependend){
        var validate = [];
        var validation_set = [];
        var valid = true;
        if(tname==''){
            validate['status'] = false;
            validate['validate_flag'] = 'name';
            validate['message'] = dependend+' name is empty';
            validation_set.push(validate); 
        }
        if(dependend == 'Hook'){
            if(/^[a-zA-Z_]*$/.test(tname) == false) {
                validate['status'] = false;
                validate['validate_flag'] = false; 
                validate['message'] = 'Use only letters ([a-z],[A-Z]) and underscores ("_") for template name'; 
                validation_set.push(validate); 
            } 
        }else if(dependend == 'Template'){
            if(/^[a-zA-Z0-9-_ ]*$/.test(tname) == false) {
                validate['status'] = false;
                validate['validate_flag'] = false;
                validate['message'] = 'Use only letters ([a-z],[A-Z]), digits ([0-9]), hyphen ("-") and underscores ("_") for template name'; 
                validation_set.push(validate); 
            }
        }
        
        return validation_set;
    }

    function click_test_mail_button(elm){
        var test_mail_box = $(elm).siblings('.thwecmf-test-mail-wrapper');
        test_mail_box.addClass('thwecmf-test-mail-active');
        test_mail_box.find('.thwecmf-test-mail-validate-notice').html('');
        test_mail_box.find('.thwecmf-test-mail-input').removeClass('has-value');
    }

    function click_test_mail_action(elm){
        var test_mail_ref = $(elm).closest('.thwecmf-test-mail-popup-actions');
        var mail_id = test_mail_ref.find('input.thwecmf-test-mail-input').val();
        var valid_result = validate_test_email(mail_id);
        if(valid_result['valid']){
            $(test_mail_ref).find('.thwecmf-test-mail-validate-notice').html('');
            template_preview_content(false);
            var test_mail_data = $('#thwecmf_tbuilder_editor_preview').html();
            var css = $('#thwecmf_template_css').html();
            css = css+$('#thwecmf_template_css_preview_override').html();
            prepare_ajax_test_email(test_mail_data, css, mail_id);
        }else{
            var error_msgs = '';
            $.each(valid_result['errors'], function(key, value) {
                error_msgs+= '<p class="error-notice"> # '+value+'</p>';
            });
            $(test_mail_ref).find('.thwecmf-test-mail-validate-notice').html(error_msgs);
        }
    }

    function prepare_ajax_test_email(mail_data, mail_css, mail_id){
        var template_ajax_load = $('#thwecmf-ajax-load-modal');
        var test_data = {
            thwecmf_security: thwecmf_admin_var.ajax_nonce,
            action : 'thwecmf_template_actions',
            thwecmf_action_index : 'settings',
            test_mail_data: mail_data,
            test_mail_css: mail_css,
            test_mail_id: mail_id,
        };
        $.ajax({
            type: 'POST',
            url: ajaxurl,                        
            data: test_data,
            beforeSend: function(){
                template_ajax_load.addClass("thwecmf-ajax-loading");
            },
            success:function(data){
                var msg = data ? 'Test email sent' : "Test email was not sent. Try again.";
                var msg_class = data ? 'success' : 'error';
                $('.thwecmf-test-mail-popup-actions').find('.thwecmf-test-mail-validate-notice').html('<p class="'+msg_class+'-notice">'+msg+'</p>')
            },
            complete: function() {
                template_ajax_load.removeClass("thwecmf-ajax-loading");
            },
            error: function(){
                alert('error');
            }
        });
    }

    function validate_test_email(mail_id){
        var validation_set = {};
        var error_msg = [];
        validation_set.valid = true;
        var builder_valid = true;
        var email_valid = true;
        var split_pattern = mail_id.split("@");
        if(split_pattern){
            var validation0 = split_pattern[0];
            var validation1 = split_pattern[1];
            // if no @ in email id then validation1 is null
            //if no string after @ in email id, then validation1 is false
            if(validation1 != '' && validation1 != null){
                var valid_split = validation1.split(".");
                var validation20 =valid_split[0];
                var validation21 =valid_split[1];
                
                if(validation21 != null && validation21 !=''){
                    email_valid = true;
                }else{
                    email_valid = false;
                    error_msg.push(TEST_MAIL_NOTICE);
                }
            }else{
                email_valid = false;
                error_msg.push(TEST_MAIL_NOTICE);
            }
        }
        if($('#tbf_t_builder').find('.thwecmf-builder-block').length == 0){
            error_msg.push('Add contents to the builder');
            builder_valid = false;
        }
        validation_set.valid = email_valid && builder_valid ? true : false;
        validation_set.errors = error_msg;
        return validation_set;
    }

    function close_test_mail_box(elm){
        $(elm).closest('.thwecmf-test-mail-wrapper').removeClass('thwecmf-test-mail-active');
    }

    function reload_page_listner(){
        window.addEventListener("beforeunload", function (event) {
            prepare_page_reload_event();
        });
    }

    function prepare_page_reload_event(){
        var builder_obj = $('#tbf_t_builder');
        var block_length = builder_obj.find('.thwecmf-builder-block').length;
        var data_track = builder_obj.attr('data-track-save');
        var data_global = builder_obj.attr('data-global-id');
        var data_css = builder_obj.attr('data-css-change');
        if(builder_obj.length > 0 && block_length > 0 && data_track != data_global){
                event.returnValue = "\o/";
        }else if(data_css=='false'){
            event.returnValue = "\o/";
        }
        else{
            return ;
        }
    }

    function resize_scroll_support(){
        setup_sidebar_scrolls();
        $(window).on('resize', function(){
            var active_tab1 = $('#thwecmf-sidebar-configure.thwecmf-active-tab');
            var active_tab2 = $('#thwecmf-sidebar-settings.thwecmf-active-tab');
            if(active_tab1.length > 0){
               setup_sidebar_scrolls(active_tab1);
            }else if(active_tab2.length > 0){
               setup_sidebar_panel_scrolls(active_tab2, true, true, true);
            }
        });
    }

    function setup_sidebar_scrolls(active_tab1){
        active_tab1 = active_tab1 ? active_tab1 : $('#thwecmf-sidebar-configure.thwecmf-active-tab');
        var sidebar_wrapper = $('#thwecmf-sidebar-element-wrapper');
        var height1 = sidebar_wrapper.height() - 122;
        active_tab1.find('.thwecmf-layers-outer-wrapper').css({
            'height': height1,
        });
    }
    function setup_sidebar_panel_scrolls(active_tab2, layout_wrapper, edit_wrapper, element_wrapper){
        active_tab2 = active_tab2 ? active_tab2 : $('#thwecmf-sidebar-settings.thwecmf-active-tab');
        var sidebar_wrapper = $('#thwecmf-sidebar-element-wrapper');
        var height2 = sidebar_wrapper.height() - 66;
        var height3 = sidebar_wrapper.height() - 124;
        if(layout_wrapper){
            active_tab2.find('.wecmf-layout-panel-outer-wrapper').css({
               'height': height2,
            });
        }
        if(edit_wrapper){
            active_tab2.find('.thwecmf_field_form_outer_wrapper').css({
                'height': height3,
            });
        }
        if(element_wrapper){
            active_tab2.find('.outer-wrapper').css({
                'height': height2,
            });
        }
    }

    function setup_category_toggle(elm){
        var click_icon = $(elm);
        var click_cat = $(elm).closest('.grid-category');
        if(click_cat.hasClass('category-collapse')){
            click_cat.removeClass('category-collapse');
            $('.thwecmf-tbuilder-elm-grid-layout-element').find('.grid-category').not(click_cat).each(function(index, el) {
                $(this).addClass('category-collapse');
            });
        }else{
            click_cat.addClass('category-collapse');
        }
    }

    /*----------------------------------
    *---- Helper Functions - END --------
    *----------------------------------*/

    function tiptip_tooltips_setup(){
        var tiptip_args = {
            'attribute': 'data-tip',
            'fadeIn': 50,
            'fadeOut': 50,
            'delay': 200
        };

        $('.tips').tipTip( tiptip_args );
    }

    function setup_colorpicker(form){
        form.find('.thpladmin-colorpick').iris({
            change: function( event, ui ) {
                $( this ).parent().find( '.thpladmin-colorpickpreview' ).css({ backgroundColor: ui.color.toString() });
            },
            hide: true,
            border: true
        }).click( function() {
            $('.iris-picker').hide();
            $(this ).closest('td').find('.iris-picker').show();

        });
    
        $('body').click( function() {
            $('.iris-picker').hide();
        });
    
        $('.thpladmin-colorpick').click( function( event ) {
            event.stopPropagation();
        });
    }

    function setup_colorpick_preview(form){
        form.find('.thpladmin-colorpick').each(function(){
            $(this).parent().find('.thpladmin-colorpickpreview').css({ backgroundColor: this.value });
        });
    }

    function get_field_value(form, type, name){
        var value = '';
        switch(type) {
            case 'select':
                value = form.find("select[name=i_"+name+"]").val();
                value = value == null ? '' : value;
                break;
                
            case 'checkbox':
                value = form.find("input[name=i_"+name+"]").prop('checked');
                value = value ? 1 : 0;
                break;
            
            case 'textarea':
                value = form.find("textarea[name=i_"+name+"]").val();
                value = value == null ? '' : value;
                break;
            default:
                value = form.find("input[name=i_"+name+"]").val();
                value = value == null ? '' : value;
        }   
        return value;
    }

    function set_field_value(form, type, name, value, multiple){
        switch(type) {
            case 'select':
                if(multiple == 1 && typeof(value) === 'string'){
                    value = value.split(",");
                    name = name+"[]";
                }
                form.find('select[name="i_'+name+'"]').val(value);
                break;
                
            case 'checkbox':
                value = value == 1 ? true : false;
                form.find("input[name=i_"+name+"]").prop('checked', value);
                break;
                
            case 'textarea':
                form.find("textarea[name=i_"+name+"]").val(value);
                break;
                
            default:
                form.find("input[name=i_"+name+"]").val(value);
        }   
    }

    function thwecmfEscapeHTML(html) {
       var fn = function(tag) {
           var charsToReplace = {
               '"': '&quot;',
               "'": '&#39;',
           };
           return charsToReplace[tag] || tag;
       }
       return html.replace(/[&<>"]/g, fn);
    }

    function decodeHTML(html) {  
       return html.replace(/'/g, "&#39;").replace(/"/g, "&quot;");
    }

    function escapeScripts( html){
        var div = document.createElement('div');
        div.innerHTML = html;
        var scripts = div.getElementsByTagName('script');
        var i = scripts.length;
        while (i--) {
          scripts[i].parentNode.removeChild(scripts[i]);
        }
        return div.innerHTML;
    }

    function thwecmf_sanitize_field( string ){
        return $($.parseHTML(string)).text();
    }

    /*----------------------------------
    *---- Helper Functions - END --------
    *----------------------------------*/

    /*----------------------------------
    *---- Helper Variabes - START -----
    *----------------------------------*/
    
    var BUILDER_CSS_PROPS = {   
        p_t : {name : 'padding-top'},
        p_r : {name : 'padding-right'},
        p_b : {name : 'padding-bottom'},
        p_l : {name : 'padding-left'}, 
        m_t : {name : 'margin-top'},
        m_r : {name : 'margin-right'},
        m_b : {name : 'margin-bottom'},
        m_l : {name : 'margin-left'},
        width : {name : 'width'},
        height : {name : 'height'},
        size_width : {name : 'width'},
        size_height : {name : 'height'},
        b_t : {name : 'border-top'},
        b_r : {name : 'border-right'},
        b_b : {name : 'border-bottom'},
        b_l : {name : 'border-left'},
        border_style : {name : 'border-style'},
        border_color : {name : 'border-color'},
        upload_img_url : {name : 'display'},
        bg_color     : {name : 'background-color'},
        color : {name : 'color'},
        font_size : {name : 'font-size'},
        text_align : {name : 'text-align'},
        content_align : {name : 'text-align'},
        align : {name : 'float'},
        img_width : {name : 'width'},
        img_height : {name : 'height'},
        img_size_width : {name : 'width'},
        img_size_height : {name : 'height'},
        details_color : {name : 'color'},
        details_font_size : {name : 'font-size'},
        details_text_align : {name : 'text-align'},
        divider_height :{name : 'border-top-width'},
        divider_color :{name : 'border-top-color'},
        divider_style :{name : 'border-top-style'},
    }

    var WECMF_FREE_TEMP = new Array('customer_processing_order', 'customer_new_account');
    
    var EDIT_FORM_PROPS = {
        p_t : {name : 'padding_top', type : 'text'},
        p_r : {name : 'padding_right', type : 'text'},
        p_b : {name : 'padding_bottom', type : 'text'},
        p_l : {name : 'padding_left', type : 'text'}, 
        m_t : {name : 'margin_top', type : 'text'},
        m_r : {name : 'margin_right', type : 'text'},
        m_b : {name : 'margin_bottom', type : 'text'},
        m_l : {name : 'margin_left', type : 'text'},
        width : {name : 'width', type : 'text'},
        height : {name : 'height', type : 'text'},
        size_width : {name : 'size_width', type : 'text'},
        size_height : {name : 'size_height', type : 'text'},
        img_size_width : {name : 'img_size_width', type : 'text'},
        img_size_height : {name : 'img_size_height', type : 'text'},
        b_t : {name : 'border_width_top', type : 'text'},
        b_r : {name : 'border_width_right', type : 'text'},
        b_b : {name : 'border_width_bottom', type : 'text'},
        b_l : {name : 'border_width_left', type : 'text'},
        border_style : {name : 'border_style', type : 'select'},
        border_color : {name : 'border_color', type : 'text'},
        bg_color     : {name : 'bg_color', type : 'text'},
        upload_img_url : {name : 'upload_img_url', type : 'text', attribute : 'yes'},
        content : {name : 'content', type : 'text',attribute : 'yes'},
        color : {name : 'color', type : 'text'},
        font_size : {name : 'font_size', type : 'text'},
        text_align : {name : 'text_align', type : 'hidden'},
        content_align : {name : 'content_align', type : 'hidden'},
        url : {name : 'url', type : 'text', attribute : 'yes'},
        title : {name : 'title', type : 'text', attribute : 'yes'},
        align : {name : 'align', type : 'hidden'},
        img_width : {name : 'img_width', type : 'text'},
        img_height : {name : 'img_height', type : 'text'},
        details_color : {name : 'details_color', type : 'text'},
        details_font_size : {name : 'details_font_size', type : 'text'},
        details_text_align : {name : 'details_text_align', type : 'hidden'},
        textarea_content : {name : 'textarea_content', type : 'textarea', attribute : 'yes'},
        divider_height : {name : 'divider_height', type : 'text'},
        divider_color : {name : 'divider_color', type : 'text'},
        divider_style : {name : 'divider_style', type : 'select'},
    }

    var DEFAULT_CSS = {
        'color' : 'transparent',
        'background-color' : 'transparent',
        'border-color': 'transparent',
        'background-color' : 'transparent',
        'padding-top' : '0px',
        'padding-right'  : '0px',
        'padding-bottom' : '0px',
        'padding-left' : '0px',
    }

     /*----------------------------------
     *---- Helper Variabes - END -----
     *----------------------------------*/


    /*----------------------------------
    *---- Helper Fuctions - Start ------
    *---------------------------------*/

    function generate_rand_string(){
        var block_id = $('#tbf_t_builder').attr('data-global-id');
        var new_block_id = parseInt(block_id)+1;
        if($('#tbf_'+new_block_id).length > 0){
            generate_random_id(new_block_id);
        }else{
            $('#tbf_t_builder').attr('data-global-id',new_block_id);
        }
        return new_block_id;
    }

    function generate_random_id(new_id){
      var new_id = parseInt(new_id)+1;
      if($('#tbf_'+new_id).length > 0){
        generate_random_id(new_id);
      }else{
        $('#tbf_t_builder').attr('data-global-id',new_id);
      }
    }

    function clean_block_name(name){ 
        var text = name.replace(/_/g,' ').replace(/\b[a-z]/g, function(string) {
            return string.toUpperCase();
        });
        return text;
    }

    function is_row(name){
        if($.inArray(name, BUILDER_ROWS) !== -1){
            return true;
        }
        return false;
    }

    function is_column(name){
        if($.inArray(name, BUILDER_COLS) !== -1){
            return true;
        }
        return false;
    }

    function is_address(name){
        if($.inArray(name, ADDRESS_BLOCKS) !== -1){
            return true;
        }
        return false;
    }

    function is_hook(name){
        if($.inArray(name, BUILDER_HOOKS) !== -1){
            return true;
        }
        return false;
    }

    function is_content_inlcude_img(name){
        if($.inArray(name, IMG_CONTENT_BLOCKS) !== -1){
            return true;
        }
        return false;
    }

    function clear_tbuilder_sidebar(elm){
        var popup = $('#thwecmf_confirmation_alerts');
        var builder_css = $('#tbf_t_builder').attr('data-css-change');
        popup.find('.thwecmf-messages').html($('#thwecmf_clear_builder_confirm').html());
        var elm_length = $('.thwecmf-builder-elm-layers .thwecmf-panel-builder-block').length;
        if(elm_length >1 || builder_css == 'false'){
            popup.dialog('open');
        }
    }

    function confirmation_tbuilder_clear(){
        var tb_builder =  $('#tbf_t_builder');
        tb_builder.find('.thwecmf-builder-column').empty();
        $('div.thwecmf-builder-elm-layers').empty();
        setup_blank_sidebar_msg();
        tb_builder.attr('data-css-props',TB_DEFAULT);
        tb_builder.attr('data-global-id','1000');
        tb_builder.attr('data-track-save','1000');
        $('#thwecmf_template_css_override').html('');
        $('#thwecmf_template_css_preview_override').html('');
    }

    function reset_row_column_width(row,columns){
        var col_width = 100/parseInt(columns);
        var siblings_props;
        $('#tbf_t_builder').find('#tbf_'+row+' tbody > tr > td').each(function(index, el) {
            var data_props = $(this).attr('data-css-props');
            if($(this).attr('id')){
                var block_id = $(this).attr('id').replace('tbf_','');
                if(data_props){
                    data_props = JSON.parse(data_props);
                    siblings_props = data_props;
                }else{
                    var data_props= DEF_COL_PROPS;
                }
                data_props['width'] = col_width+'%';
                var data_props_json = JSON.stringify(data_props);
                $(this).attr('data-css-props',data_props_json);
                prepare_builder_block_css(data_props, block_id, 'column_clone');
            }
        });
    }

    function highlight_focus_blocks(b_elm, p_elm){
        var tbuilder_ref = $('#tbf_t_builder');
        var panel_ref = $('.thwecmf-sidebar-config-elm-layers');
        tbuilder_ref.find('.thwecmf-builder-highlight').removeClass('thwecmf-builder-highlight');
        panel_ref.find('.thwecmf-panel-highlight').removeClass('thwecmf-panel-highlight');
        p_elm.addClass('thwecmf-panel-highlight');
        b_elm.addClass('thwecmf-builder-highlight');
        $('html, body').animate({scrollTop: track_element_position_b(b_elm)}, 500);
        var panel_container = $('.thwecmf-layers-outer-wrapper');
        panel_container.animate({scrollTop: track_element_position_p(p_elm, panel_container)}, 500);
        setTimeout(
            function() {  
                p_elm.removeClass('thwecmf-panel-highlight');
                b_elm.removeClass('thwecmf-builder-highlight'); 
            },
            3000
        );
    }

    function track_element_position_b(elm){
        return parseInt(elm.offset().top)-200;
    }

    function track_element_position_p(elm,container){
        return parseInt(elm.offset().top - container.offset().top + container.scrollTop())-200;
    }

    function builder_css_generator(props){
        var css = '';
        $.each( props, function( name, css_prop ) {
            if(name in BUILDER_CSS_PROPS){
                var css_pname = BUILDER_CSS_PROPS[name]['name'];
                css_prop =  css_prop ? css_prop : DEFAULT_CSS[css_pname];
                if(css_prop){
                    css += css_pname+':'+css_prop+';';
                }
            }
        });
        return css;
    }    

    function prepare_builder_css_props(props, keys){
        var new_props = {};
        $.each(keys, function(i, name) {
            new_props[name] = props[name];
        });
        return new_props;
    }

    function css_parent_selector(blockId, isPrev){
        var p_selector = "#"+blockId;
        var selector = isPrev ? p_selector : p_selector+' ';
        return selector;
    }

     /*----------------------------------
     *---- Click Fuctions - START -------
     *----------------------------------*/

    function add_row_builder_action(elm){
        reset_sidebar_status(false);
        var CLICK_FLAG = 'columns';
        setup_sidebar_content('layout');
        setup_sidebar_panel_scrolls(false, true, false, false);
    }

    function delete_builder_blocks(elm){
        if($(elm).closest('div.thwecmf-panel-builder-block').hasClass('thwecmf-hooks')){
            var id = $(elm).closest('div.thwecmf-panel-builder-block').attr('id');
            $(elm).closest('div.thwecmf-panel-builder-block').remove();
            var block = $('#tbf_t_builder').find('#tbf_'+id);
            var block_parent = block.closest('.thwecmf-columns');
            block.remove();
            if(block_parent.find('.thwecmf-builder-block').length < 1 && block_parent.find('.thwecmf-hook-code').length < 1){ // Show html to add new content on deleting all elements inside
                block_parent.html(EMPTY_TD_DATA);
            }
        }else{
            var select_block = $(elm).closest('div.thwecmf-panel-builder-block');
            var delete_panel_elm = select_block;
            var delete_id = select_block.attr('id');
            var builder_element = $('#tbf_t_builder').find('#tbf_'+delete_id);
            var panel_element = $('div.thwecmf-builder-elm-layers').find('#'+delete_id);
            if(delete_panel_elm.hasClass('thwecmf-rows') || delete_panel_elm.hasClass('thwecmf-elements')){     //  deleting rows or elements on clicking delete
                var builder_element_parent = builder_element.closest('.thwecmf-columns');
                builder_element.remove();
                panel_element.remove();
                if(builder_element_parent.find('.thwecmf-builder-block').length < 1 && builder_element_parent.find('.thwecmf-hook-code').length < 1){ // Show html to add new content on deleting all elements inside
                    builder_element_parent.html(EMPTY_TD_DATA);
                }

            }else if(delete_panel_elm.hasClass('thwecmf-columns')){ // If deleting columns, count of column updated on rows and width of resulting columns reset 
                var builder_element_parent = builder_element.closest('.thwecmf-row');
                var panel_element_parent = panel_element.closest('.thwecmf-rows');
                var columns = panel_element_parent.attr('data-columns');
                if(columns <= 1){
                    builder_element_parent.remove();
                    panel_element_parent.remove();
                }else{
                    var updated_columns = parseInt(columns)-1;
                    panel_element_parent.attr('data-columns',updated_columns); 
                    builder_element_parent.attr('data-column-count',updated_columns);
                    builder_element.remove();
                    panel_element.remove();
                    var builder_element_parent_id = builder_element_parent.attr('id').replace('tbf_','');
                    reset_row_column_width(builder_element_parent_id,updated_columns); // Resetting width of each column in the parent table
                }    
            }
        }
        setup_blank_sidebar_msg();
    }

    /*-----------------------------------------------
    /*---- New Insertion Function  -  START ---------
    /*---------------------------------------------*/
    
    function render_edit_block_sidebar_form(elm, blockId, blockName){
        setup_sidebar_content('settings');
        var content = $('#thwecmf-sidebar-settings');
        populate_sidebar_content(content, blockId, blockName);
        reset_sidebar_status(false);
        var form = $('#thwecmf_builder_block_form');
        set_field_value(form, 'hidden', 'thwecmf_block_id', blockId, 0);
        set_field_value(form, 'hidden', 'thwecmf_block_name', blockName, 0);
        setup_colorpicker(form);
        setup_colorpick_preview(form);
        tiptip_tooltips_setup();
        additional_sidebar_styles(form);
        // Change scrollbar div height according to window height - MAC compatibility
        setup_sidebar_panel_scrolls(false, false, true, false);
    }

    function additional_sidebar_styles(form){
        form.find('.thwecmf-aligment-icon-wrapper').each(function(index, el) {
            var icon_val = $(this).find('input[type="hidden"]').val();
            $(this).find(".img-wrapper[data-align='"+icon_val+"']").addClass('thwecmf-active-icon');
        });
    }

    function populate_sidebar_content(content, blockId, blockName){
        var form = $('#thwecmf_builder_block_form');
        if(is_row(blockName)){ 
            content.find('.thwecmf_field_form_general td').html($('#thwecmf_field_form_id_row').html());
        }else if(is_column(blockName)){ 
            content.find('.thwecmf_field_form_general td').html($('#thwecmf_field_form_id_col').html());
        }else{
            content.find('.thwecmf_field_form_general td').html($('#thwecmf_field_form_id_'+blockName).html());
        }  
        populate_block_general_form(form, blockId, blockName); // Check for previous values
    }

    function clean_text_data(text){
        var data = text.replace(/&#39;/g,"'").replace(/&quot;/g,'"');
        return data;
    }

    function populate_block_general_form(form, blockId, blockName){
        var ref_elem = $('#tbf_'+blockId);
        var block_props_json = ref_elem.attr('data-css-props');
        var block_props = block_props_json ? JSON.parse(block_props_json) : false;
        var url_props_json = ref_elem.attr('data-text-props');
        var url_props = url_props_json ? JSON.parse(url_props_json): '';
        var default_upload_url =  form.find('.thwecmf-upload-preview').attr('data-default-url');
       
        var def_vals = form_default_values(blockName);
        if(block_props && !$.isEmptyObject(block_props)){
            $.each(block_props, function (key, value) {
                var props = EDIT_FORM_PROPS[key];
                if(EDIT_FORM_PROPS[key]['attribute'] && EDIT_FORM_PROPS[key]['attribute'] == 'yes'){
                    if(key === 'upload_img_url'){ 
                        value = (value == 'inline-block') ? url_props[key] : '';  
                    }else{
                        value = url_props[key];
                    }
                    if(key == 'textarea_content' || key == 'content'){
                        // value = url_props[key];
                        value = clean_text_data(url_props[key]);
                    }
                }
                if(props['type'] == 'fourside'){
                    props['type'] = 'text';
                }
                if(key == 'upload_img_url'){
                    value = value == '' ? default_upload_url : value;
                    form.find('.thwecmf-img-preview-image .thwecmf-upload-preview img').attr('src',value);
                    if(value != default_upload_url){
                        form.find('.thwecmf-img-preview-'+blockName+' .thwecmf-remove-upload-btn').removeClass('thwecmf-remove-upload-inactive');
                    }
                    form.find('.thwecmf-img-preview-'+blockName+' .thwecmf-upload-preview img').attr('src',value);
                }
                if(value == '' && def_vals != '' && key in def_vals){
                    value = def_vals[key];
                }
                set_field_value(form, props['type'], props['name'], value, 0);
            });
        }
    }

    function form_default_values(blockName){
        var default_values = {};
        switch(blockName){
            case 'one_column':
            case 'two_column':
                default_values = {
                    p_t  : '0px',
                    p_r : '0px',
                    p_b : '0px',
                    p_l : '0px',
                    m_t : '0px',
                    m_r : 'auto',
                    m_b : '0px',
                    m_l : 'auto',
                    b_t : '0px',
                    b_r : '0px',
                    b_b : '0px',
                    b_l : '0px',
                    bg_position : 'center',
                };
                break;   
            case 'one_column_one':
                default_values = {
                    width : '100%',
                    b_t : '1px',
                    b_r : '1px',
                    b_b : '1px',
                    b_l : '1px',
                    border_color : '#dddddd',
                    border_style : 'dotted',
                };
                break;  
            case 'two_column_one':
            case 'two_column_two':
                default_values = {
                    width : '50%',
                    b_t : '1px',
                    b_r : '1px',
                    b_b : '1px',
                    b_l : '1px',
                    border_color : '#dddddd',
                    border_style : 'dotted',
                };
                break;                              
            case 'temp_builder' : 
                default_values = {
                    b_t : '1px',
                    b_r : '1px',
                    b_b : '1px',
                    b_l : '1px',
                    border_style : 'solid',
                    border_color : '#dedede',
                    bg_color : '#edf1e4'
                };
                break;                                     
            default :
                default_values ='';
        }
        return default_values;
    }


    function add_builder_elements(blockName, blockId, col_count){
        var new_id = generate_rand_string(); 
        var track_html = prepare_sidebar_row_html(blockName,new_id); 
        var html = prepare_builder_content_html(blockName, col_count);
        sidebar_track_data_blocks(html,track_html, blockId);
        setup_blank_sidebar_msg();
    }

    function prepare_sidebar_row_html(blockName, row_id){
        if(is_row(blockName)){
            var html = '<div id="'+row_id+'" class="thwecmf-rows thwecmf-panel-builder-block" data-columns="'+COL_TO_NUM[blockName]+'">';
            LAYOUT_HELPER['row'] = 'tbf_'+row_id;
            html+= $('#thwecmf_tracking_panel_row_html').html();
            html = html.replace('{bl_id}', row_id);
            html = html.replace('{bl_name}', blockName);
            html = html.replace('data-icon-attr="{bl_name}"', 'data-icon-attr="'+blockName+'"');
            html+= '<div class="thwecmf-column-set">';
            for(var i=1;i<=COL_TO_NUM[blockName];i++){
                var column_id = generate_rand_string();
                var column_name = 'column_'+i;
                LAYOUT_HELPER[column_name] = 'tbf_'+column_id;
                html+= prepare_sidebar_column_html(row_id, column_id, blockName, i);
            }
            html+= '<div class="btn-add-column-wrap">';
            html+= '<div class="panel-add-btn btn-add-column thwecmf-premium-disabled-feature" data-parent="'+row_id+'">';
            html+= '<a>Add Column</a></div>';
            html+= '<span class="th-premium-feature-msg th-premium-feature-msg-add-column">Upgrade</span>';
            html+= '</div></div>';
        }else if(is_hook(blockName)){
            var name = clean_block_name(blockName);
            var html = '<div id="'+row_id+'" class="thwecmf-hooks thwecmf-panel-builder-block">';
            html+= $('#thwecmf_tracking_panel_hook_html').html();
            html = html.replace('{name}',clean_block_name(blockName));
            html+= '</div>';
            LAYOUT_HELPER['hook'] = 'tbf_'+row_id;
        }else{
            var html = '<div id="'+row_id+'" class="thwecmf-elements thwecmf-panel-builder-block">';
            html+= $('#thwecmf_tracking_panel_elm_html').html();
            html = html.replace('{name}',clean_block_name(blockName));
            html = html.replace('{bl_id}', row_id);
            html = html.replace('{bl_name}', blockName);
            html = html.replace('{bl_attr_name}', blockName.toLowerCase());
            html+= '</div>;'
            LAYOUT_HELPER['element'] = 'tbf_'+row_id;
        }
        return html;
    }

    function prepare_sidebar_column_html(row_id, column_id, blockName, i){
        var column_name = blockName+'_'+NUM_TO_WORDS[i];
        if(!is_column(column_name)){
            column_name = 'column_clone'; 
        }
        var t_html = '<div id="'+column_id+'" class="thwecmf-columns thwecmf-panel-builder-block" data-parent="'+row_id+'">';
        t_html+= $('#thwecmf_tracking_panel_col_html').html();
        t_html = t_html.replace('{bl_id}', column_id);
        t_html = t_html.replace('{bl_name}', column_name); 
        t_html = t_html.replace('data-icon-attr="{bl_name}"', 'data-icon-attr="'+column_name+'"');
        t_html+= '<div class="thwecmf-element-set">';
        t_html+= '<div class="thwecmf-hidden-sortable thwecmf-elements"></div><div class="thwecmf-btn-add-element panel-add-btn panel-add-element"><a href="#">Add Element</a></div>';
        t_html+= '</div></div>';
        return t_html;
    }

    function setup_blank_sidebar_msg(){
        if($('div.thwecmf-builder-elm-layers').find('.thwecmf-rows').length < 1){
            $('.thwecmf-empty-layer-msg').removeClass('thwecmf-layers-toggle');
        }else{
            $('.thwecmf-empty-layer-msg').addClass('thwecmf-layers-toggle');
        } 
    }

    function sidebar_track_data_blocks(builder_html,track_html, blockId){
        if(blockId){
            var id = 'tbf_'+blockId;
            $(track_html).insertBefore($('div.thwecmf-builder-elm-layers').find('#'+blockId+' > .thwecmf-element-set > div.panel-add-element'));
            var target = $('#tbf_t_builder').find('#'+id);
            if(target.find('>.thwecmf-btn-add-element').length > 0){
                target.find('>.thwecmf-btn-add-element').remove();
            }
            target.append(builder_html);
        }else{
            $('div.thwecmf-builder-elm-layers').append(track_html);
            $('#tbf_t_builder .thwecmf-builder-column').append(builder_html);
        }
    }


    function prepare_builder_content_html(blockName, col_count){
        var block_elm = '';
        var elm_type = '';
        if(blockName == "one_column"){
            block_elm = $('#thwecmf_template_layout_1_col');
            elm_type = 'layout';

        }else if(blockName == "two_column"){
            block_elm = $('#thwecmf_template_layout_2_col');
            elm_type = 'layout';

        }else if(blockName == "billing_address"){
            block_elm = $('#thwecmf_template_elm_billing_address');
            elm_type = 'block';
            
        }else if(blockName == "shipping_address"){
            block_elm = $('#thwecmf_template_elm_shipping_address');
            elm_type = 'block';
            
        }else if(blockName == "text"){
            block_elm = $('#thwecmf_template_elm_text');
            elm_type = 'block';
            
        }else if(blockName == "image"){
            block_elm = $('#thwecmf_template_elm_image');
            elm_type = 'block';
            
        }else if(blockName == "divider"){
            block_elm = $('#thwecmf_template_elm_divider');
            elm_type = 'block';
            
        }else if(blockName == "gap"){
            block_elm = $('#thwecmf_template_elm_gap');
            elm_type = 'block';
            
        }else if(blockName == "email_header"){
            block_elm = $('#thwecmf_template_hook_email_header');
            elm_type = 'hook';  

        }else if(blockName == "email_order_details"){
            block_elm = $('#thwecmf_template_hook_order_details'); 
            elm_type = 'hook'; 

        }else if(blockName == "before_order_table"){
            block_elm = $('#thwecmf_template_hook_before_order_table');  
            elm_type = 'hook';

        }else if(blockName == "after_order_table"){
            block_elm = $('#thwecmf_template_hook_after_order_table');
            elm_type = 'hook';  

        }else if(blockName == "order_meta"){
            block_elm = $('#thwecmf_template_hook_order_meta');
            elm_type = 'hook';  

        }else if(blockName == "customer_details"){
            block_elm = $('#thwecmf_template_hook_customer_address');
            elm_type = 'hook';  

        }else if(blockName == "email_footer"){
            block_elm = $('#thwecmf_template_hook_email_footer');  
            elm_type = 'hook';
        }
        var block_html = '';
        if(block_elm.length){
            if(elm_type == 'layout'){
                block_html = clean_content_layout_html(block_elm,blockName);            
            }else if(elm_type == 'hook'){
                block_html = clean_content_element_html(block_elm,blockName,'hook',false);
            }else{
                block_html = clean_content_element_html(block_elm,blockName,'element', col_count);
            }
        }
        return block_html;
    }

    function clean_content_layout_html(block_elm,blockName){
        var html = block_elm.html();
        html = html.replace(blockName,LAYOUT_HELPER['row']);
        for(var i=1;i<=COL_TO_NUM[blockName];i++){
            var column_name = 'column_'+i;
            var replace_name = blockName+'_'+i;
            html = html.replace(replace_name,LAYOUT_HELPER[column_name]);
        }
        LAYOUT_HELPER={};
        return html;
    }

    function clean_content_element_html(block_elm,blockName,index, col_count){
        var html = block_elm.html();
        html = html.replace('{'+blockName+'}',LAYOUT_HELPER[index]);
        LAYOUT_HELPER={};
        return html;
    }

    function setup_sidebar_content($active_tab){
       if($active_tab == 'configure'){ 

        }else if($active_tab == 'layout'){ 
            $('#thwecmf-sidebar-settings').html($('#thwecmf_builder_panel_layout').html());
        }else if($active_tab == 'layout-elements'){ 
            $('#thwecmf-sidebar-settings').html($('#thwecmf_template_builder_panel_layout_element').html());
        }else if($active_tab == 'settings'){
            $('#thwecmf-sidebar-settings').html($('#thwecmf_builder_block_edit_form').html());
        }
    }

    function template_sidebar_back_navigation(elm){
        var sidebar_class = $(elm).data('nav');
        reset_sidebar_status(true);
    }

    /*-------------------------------------------------------------------------------------------------------------------
    ------------------------------------------- SideBar Click Functions -------------------------------------------------
    -------------------------------------------------------------------------------------------------------------------*/ 

    function click_block_edit_sidebar_form_save(elm){
        var form = $('#thwecmf_builder_block_form');
        save_sidebar_edit_form(form);
    }

    function form_textarea_manipulate(str){
        var arrayOfLines = str.split(/\r?\n/);
        var formated_content = '';
        $.each(arrayOfLines, function(index, item) {
            var suffix = '<br>';
            formated_content+= '<div class=\'wecmf-txt-wrap\'>'+item+suffix+'</div>';
        });
        return formated_content;
    }

    function save_sidebar_edit_form(form){
        var block_id = get_field_value(form, 'hidden', 'thwecmf_block_id');
        var block_name = get_field_value(form, 'hidden', 'thwecmf_block_name');
        var sidebar_form_valid = [];
        var save_block = $('#tbf_'+block_id);
        var css_props = {};
        var js_props = {};
        var url_props = {};
        var textarea_content = false;
        var data_json_css = save_block.attr('data-css-props');
        var data_json_text = save_block.attr('data-text-props');
        var default_upload_url = $('#thwecmf-sidebar-element-wrapper').find('.thwecmf-upload-preview').attr('data-default-url');
        data_json_css = $.parseJSON(data_json_css);
        data_json_text = $.parseJSON(data_json_text);
        var data_css = $.extend({}, data_json_css, data_json_text);
        $.each( EDIT_FORM_PROPS, function( key, props ) {
            if(key in data_css){
                var value = get_field_value(form, props['type'], props['name']);
                if(typeof props['attribute'] != 'undefined'){
                    if(key == 'textarea_content'){ 
                        js_props[key] = thwecmfEscapeHTML( escapeScripts( value ) );
                        css_props[key] = '';
                        return true;
                    }
                    else{
                        if( key == 'content' ){
                            js_props[key] = thwecmfEscapeHTML(thwecmf_sanitize_field( value ));
                            css_props[key] = '';
                            return true;
                        }
                        js_props[key] = value;
                    }
                }
                if(key === 'upload_img_url'){
                    url_props[key] = value;
                    value = (value !== '') ? 'inline-block' : 'none';
                }
                css_props[key] = value;
            }   
        });

        if(sidebar_form_valid.length === 0){    // Validation only returns on status. If success then no returns. If changing the code, keep an eye here
            $('#thwecmf-sidebar-settings').find('#sb_validation_msg').html('');
            var text_json_props = JSON.stringify(js_props);
            if(css_props){
                var css_json_props = JSON.stringify(css_props);
                save_block.attr('data-css-props',css_json_props);
            }
            if(js_props){
                save_block.attr('data-text-props',text_json_props);
            }
            if(is_address(block_name)){
                var align_float = css_props['align'];
                css_props['align'] = '';
                $('#tbf_'+block_id).find('.thwecmf-address-alignment').attr('align',align_float);
            }else if(block_name == "order_details"){
                var align_float = css_props['align'];
                $('#tbf_'+block_id).find('.order-padding').attr('align',align_float);
            }
            if(block_name == 'text'){
                js_props['textarea_content'] = form_textarea_manipulate(js_props['textarea_content']);
            }
            prepare_builder_block_css(css_props, block_id, block_name);
            set_image_dimension_attributes(block_name, save_block);
            prepare_builder_text_override(js_props, block_id, block_name);
            setup_colorpick_preview(form);
            // block_id == 'temp_builder' ? $('#tbf_'+block_id).attr('data-css-change',false) : '';
            $('#thwecmf_builder_save_messages').html('Settings Saved').addClass('thwecmf-show-save thwecmf-save-success');
            $('#thwecmf_save_settings_button').find('.thwecmf_save_form').attr('disabled',true);
            setTimeout(function(){
                $('#thwecmf_builder_save_messages').removeClass("thwecmf-show-save thwecmf-save-success");
                $('#thwecmf_save_settings_button').find('.thwecmf_save_form').removeAttr('disabled');
            },3000);
        }else{
            var message = '';
            $.each(sidebar_form_valid, function( status, msg ) {
                message+= '<p class="thwecmf-msg-error">'+msg['message']+'</p>';
            });
            $('#thwecmf-sidebar-settings').find('#sb_validation_msg').html(message);
        }
        $('#tbf_t_builder').attr('data-css-change',false);
    }

    function set_image_dimension_attributes(name, obj){
        if(is_content_inlcude_img(name)){
            var img_obj = obj.find('img');
            var i_width  = img_obj.width();
            var i_height = img_obj.height();
            var comp_size = {"c_width":i_width,"c_height":i_height};
            img_obj.attr({
                width: i_width,
                height: i_height
            });
            obj.attr('data-misc',JSON.stringify(comp_size));
        }
    }

    function prepare_builder_block_css(props, block_id, block_name){
        var tb_css_override_elm = $('#thwecmf_template_css_override');
        var tb_css_override = tb_css_override_elm.html();
        tb_css_override += prepare_builder_css_override(props, 'tbf_'+block_id, block_name, true);
        tb_css_override_elm.html(tb_css_override);
        var prev_css_override_elm = $('#thwecmf_template_css_preview_override');
        var prev_css_override = prev_css_override_elm.html();
        prev_css_override += prepare_builder_css_override(props, 'tpf_'+block_id, block_name, true);
        prev_css_override_elm.html(prev_css_override);
    }

    function prepare_builder_css_override(props, blockId, blockName, isPrev){
        var css = '';
        switch(blockName) {
            case 'one_column':
            case 'two_column':
                css = prepare_layout_row_css_override(props, blockId, isPrev);
                break;
            case 'one_column_one':
            case 'two_column_one':
            case 'two_column_two':
                css = prepare_layout_col_css_override(props, blockId, isPrev);
                break;
            case 'billing_address':
            case 'shipping_address':
            case 'text':
            case 'image':
            case 'gap':
            case 'divider':
            case 't_builder':
                css = prepare_block_css_override(props, blockId, blockName, isPrev);
                break; 
            default:
                css = '';
        }
        return css;
    }

    function prepare_builder_text_override(js_props, block_id, block_name){ 
        block_id = 'tbf_'+block_id;
        var text_elm_class = get_text_props(js_props, block_id, block_name);
        $.each(text_elm_class, function( name, props) {

            if(props['class'] == ''){
                var block_ref = $('#'+block_id);
            }else{
                var block_ref = $('#'+block_id).find(props['class']); 
                block_ref = block_ref.length < 1  ? $('#'+block_id) : block_ref;
                // If style not applied correctly the above line is culprit
                // If block_ref cannot find class it means , changes to be made to block_id.
            }
            if(block_ref){
                if(props['attribute'] == 'image' ){
                    block_ref.attr('src', js_props[name]);
                }else if(props['attribute'] == 'html'){
                    if(block_name =='text'){
                        var formated_txt = js_props[name].replace(/\r?\n/g,'<br/>');
                        formated_txt = thwecmfEscapeHTML(formated_txt);
                        block_ref.html(formated_txt);
                    }
                    else{
                        block_ref.html(js_props[name]);
                    }
                }else if(props['attribute'] == 'text'){
                    block_ref.text(js_props[name]);

                }else if(props['attribute'] == 'link'){
                    block_ref.attr('href', js_props[name]);

                }else if(props['attribute'] == 'title'){
                    block_ref.attr('title', js_props[name]);
                } 
            }
        });
    }

    function prepare_layout_row_css_override(props, blockId, isPrev){
        var p_selector = css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwecmf-row"; 

        var row_props = prepare_builder_css_props(props, ['height', 'p_t', 'p_r', 'p_b', 'p_l', 'm_t', 'm_r', 'm_b', 'm_l', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color', 'bg_color']);
        var row_css = w_selector+' {';
        row_css += builder_css_generator(row_props);
        row_css += '}';
        return row_css;
    }

    function prepare_layout_col_css_override(props, blockId, isPrev){
        var p_selector = css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwecmf-column-padding{";

        var col_props = prepare_builder_css_props(props, ['width', 'text_align', 'p_t', 'p_r', 'p_b', 'p_l', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color', 'bg_color']);
        var col_css = w_selector;
        col_css += builder_css_generator(col_props);
        col_css += '}';
        return col_css;
    }

    function prepare_block_css_override(props, blockId, blockName, isPrev){
        var counter = false;
        var block_css = '';
        var t = ELM_CSS_PROP_MAP[blockName];
        var count = Object.keys(ELM_CSS_PROP_MAP[blockName]).length;
        if(!$.isEmptyObject(ELM_CSS_PROP_MAP[blockName]) && count){
            $.each(ELM_CSS_PROP_MAP[blockName], function(class_name, style) {
                if(!counter){
                    var p_selector = css_parent_selector(blockId, isPrev);
                    var w_selector = p_selector+class_name;
                    
                    var table_props = prepare_builder_css_props(props, style);
                    block_css += w_selector+' {';
                    block_css += builder_css_generator(table_props);
                    block_css += '}';
                }else{
                    var table_props = prepare_builder_css_props(props, style);
                    block_css += w_selector+' '+class_name+'{';
                    block_css += builder_css_generator(table_props);
                    block_css += '}';
                }        
               
            });
        } 
        return block_css;
    }

    function get_text_props(js_props, block_id, block_name){
        var text_css = {};
        switch(block_name){
            case 'billing_address' :
                text_css =  {
                    content : {'class' : '.thwecmf-billing-header', 'attribute' : 'html'},
                };
                break;   
            case 'shipping_address' :
                text_css =  {
                    content : {'class' : '.thwecmf-shipping-header', 'attribute' : 'html'},
                };
                break; 
            case 'text' :
                text_css = {
                    textarea_content : {'class' : '.thwecmf-block-text-holder', 'attribute' : 'html'},
                };
                break;
            case 'image' :
                text_css = {
                    upload_img_url : {'class' : 'img', 'attribute' : 'image'},
                };
                break;  
            default    :
                text_css = '';
        }
        return text_css;
    }

    function reset_sidebar_status($configure){
        // set $configure to true if tracking panel is to be displayed, false if settings page is to be displayed
        if($configure){
            var active = 'thwecmf-sidebar-configure';
            var inactive = 'thwecmf-sidebar-settings';
            var add_class = true;
        }else{
            var active = 'thwecmf-sidebar-settings';
            var inactive = 'thwecmf-sidebar-configure';
            var add_class = false;
        }
        var settings_panel = $('#thwecmf-sidebar-element-wrapper');
        var previous_btn = settings_panel.find('.thwecmf-nav-previous');
        add_class ? previous_btn.addClass('thwecmf-configure-page-index') : previous_btn.removeClass('thwecmf-configure-page-index');
        settings_panel.find('#'+active).removeClass('inactive-tab').addClass('thwecmf-active-tab');
        settings_panel.find('#'+inactive).removeClass('thwecmf-active-tab').addClass('inactive-tab');
    }

    function initialize_sidebar_clicks(){
        $('#thwecmf-sidebar-settings').on('click', '.thwecmf-elm-col', function(event) {
            var form = $('#thwecmf_tbuilder_layout_elm_form');
            reset_sidebar_status('thwecmf-sidebar-configure','thwecmf-sidebar-configure');
            var block_identifier = get_field_value(form, 'hidden', 'thwecmf_block_id');
            var col_count = get_field_value(form, 'hidden', 'thwecmf_col_count');
            block_identifier = block_identifier ? block_identifier : ''; 
            var name = $(this).find('div').data('block-name');
            add_builder_elements(name,block_identifier,col_count);
            setup_builder_ui();
            setup_sidebar_scrolls(false);
        });

        // Click on elements or layouts from sidebar panel
        $('.thwecmf-tbuilder-wrapper').on('click', '.thwecmf-btn-add-element', function(event) {
            event.preventDefault();
            setup_sidebar_content('layout-elements');
            var form = $('#thwecmf_tbuilder_layout_elm_form');
            if($(this).hasClass('panel-add-btn')){
                var status = 'panel';
                var click_elm = $(this).closest('div.thwecmf-columns').attr('id');
                var column_count = $(this).closest('.thwecmf-rows.thwecmf-panel-builder-block').attr('data-columns');
            }else if($(this).hasClass('thwecmf-builder-add-btn')){
                var status = 'builder';
                var click_elm = $(this).closest('td').attr('id').replace('tbf_','');
                var column_count = $(this).closest('.thwecmf-row.thwecmf-builder-block').attr('data-column-count');
            }
            set_field_value(form, 'hidden', 'thwecmf_block_id', click_elm, 0);
            set_field_value(form, 'hidden', 'thwecmf_col_count', column_count, 0);
            reset_sidebar_status(false);
            $('#thwecmf-sidebar-settings').find('.thwecmf-tbuilder-elm-grid-layout-element .column-basic-elements .grid-category').removeClass('category-collapse');

            // Change scrollbar div height according to window height - MAC compatibility
            setup_sidebar_panel_scrolls(false, false, false, true);
        });

        $('.thwecmf-tbuilder-wrapper').on('click', '.thwecmf-element-name,.thwecmf-hook-name', function(event) {
            var clicked = $(this);
            var panel_elm = clicked.closest('.thwecmf-panel-builder-block');
            var panel_elm_id = panel_elm.attr('id');
            var builder_elm = $('#tbf_'+panel_elm_id);
            highlight_focus_blocks(builder_elm, panel_elm);
        });

        $('#tbf_t_builder').on('click', '.thwecmf-block,.thwecmf-hook-code', function(event) {
            var builder_elm = $(this);
            var builder_elm_id = builder_elm.attr('id');
            var panel_elm_id = builder_elm_id.replace('tbf_','');
            var panel_elm = $('.thwecmf-builder-elm-layers').find('#'+panel_elm_id);
            highlight_focus_blocks(builder_elm, panel_elm);
        });

         $('.thwecmf-tbuilder-wrapper').on('click', '.thwecmf-seperator-heading', function(event) {
            var clicked = $(this).closest('.thwecmf-edit-form');
            if(clicked.hasClass('thwecmf-toggle-edit-section')){
                clicked.removeClass('thwecmf-toggle-edit-section');
                var parent_table = $(this).closest('.thwecmf_field_form_general');
                parent_table.find('.thwecmf-edit-form').not(clicked).addClass('thwecmf-toggle-edit-section');
            }else{
                clicked.addClass('thwecmf-toggle-edit-section');
            }
        });

        $('.thwecmf-tbuilder-wrapper').on('click', '.thwecmf-aligment-icon-wrapper .img-wrapper', function(event) {
            var icon_elm = $(this);
            var icon_wrapper = $(this).closest('.thwecmf-aligment-icon-wrapper');
            var form = $('#thwecmf_builder_block_form');
            var align_val = icon_elm.attr('data-align');
            icon_wrapper.find('.thwecmf-text-align-input').val(align_val);
            icon_wrapper.find('.thwecmf-active-icon').removeClass('thwecmf-active-icon');
            icon_wrapper.find('.thwecmf-active-icon').removeClass('thwecmf-active-icon');
            icon_elm.addClass('thwecmf-active-icon');
        });
    }

/*------------------------------------------------------------------------------------------------------------------
-------------------------------------- Additional Functions -------------------------------------------------------
------------------------------------------------------------------------------------------------------------------*/ 
    
    function initialize_popups(){
        $("#thwecmf_confirmation_alerts").dialog({
            position: {
                my: 'center',
                at: 'center',
                of: $('#thwecmf-sidebar-element-wrapper')
            },
            draggable: true,
            appendTo: "#thwecmf-sidebar-element-wrapper",
            modal:true,
            width:275,
            resizable: false,
            autoOpen: false,
            dialogClass: 'thwecmf-confirmations',
            buttons: [
                {
                    text:"No",
                    click: function() {
                        $(this).dialog('close');
                    }
                },
                {
                    text:"Yes",
                    click: function() {
                        confirmation_tbuilder_clear();
                        $(this).dialog('close');
                    }
                }
            ]
        }).parent().draggable({
            containment: '#dialogContainer'
        });
    }

    /*------------------------------------------------------------------------------------------------------------------
    -------------------------------------- Additional Functions -------------------------------------------------------
    ------------------------------------------------------------------------------------------------------------------*/ 

    function remove_image_uploaded(elm){
        var upload_pointer = $(elm);
        var upload_preview  = upload_pointer.siblings('.thwecmf-upload-preview');
        var default_url = upload_preview.attr('data-default-url');
        upload_preview.closest('.thwecmf-upload-action-settings').find('.thwecmf-upload-url').val(default_url);
        upload_pointer.closest('.thwecmf-upload-action-settings').find('.thwecmf-upload-preview img').attr('src',default_url);
        upload_pointer.closest('.thwecmf-upload-action-settings').find('.thwecmf-upload-preview img').attr('src',default_url);
        var notice_tag = upload_pointer.closest('.thwecmf-upload-action-settings').siblings('.thwecmf-upload-notices');
        if(notice_tag.find('p').length > 0){
            notice_tag.find('p').remove();
        }
        upload_pointer.addClass('thwecmf-remove-upload-inactive');
    }

    function setup_wp_image_upload(elm,prop){ 
        var frame;
        var file_valid;
        var validation_msg;
        frame = wp.media({
            title: 'Upload Media Of Your Interest',
            button: {
                text: "Choose this" 
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });
        frame.open();
        frame.on( 'select', function() { 
            // Get media attachment details from the frame state
            var thwec_attachment = frame.state().get('selection').first().toJSON();
            if(prop == 'gif'){
                file_valid = thwec_attachment['mime'] == 'image/gif' ? true : false;
                validation_msg = '<p>Invalid file type. Choose a Gif file</p>';
            }else{
                file_valid = thwec_attachment['type'] == 'image' &&  $.inArray(thwec_attachment['mime'], UPLOAD_FILE_TYPES) !== -1 ? true : false;
                validation_msg = '<p>Invalid file type. Choose an image file <br>[ jpg, jpeg, png ]</p>';
            }
            var thwec_attachment_url = thwec_attachment['url'];
            var thwec_attachment_name = thwec_attachment['name'];
            var form_table = $('#thwecmf_builder_block_form');
            var upload_block = form_table.find('.thwecmf-upload-action-settings.thwecmf-img-preview-'+prop);
            if(file_valid){
                if(prop=='bg_image'){
                    set_field_value(form_table, 'text', 'upload_bg_url', thwec_attachment_url, 0);
                }else if(prop == 'image' || prop == 'gif'){
                    set_field_value(form_table, 'text', 'upload_img_url', thwec_attachment_url, 0);
                }
                upload_block.find('.thwecmf-upload-preview img').attr('src',thwec_attachment_url);
                if(upload_block.find('.thwecmf-remove-upload-btn').hasClass('thwecmf-remove-upload-inactive')){
                    upload_block.find('.thwecmf-remove-upload-btn').removeClass('thwecmf-remove-upload-inactive');
                }
                upload_block.closest('td').find('.thwecmf-upload-notices').html('');
            }else{
                upload_block.closest('td').find('.thwecmf-upload-notices').html(validation_msg);
            }
        }); 
    }

    /*----------------------------------------------
     *---- Sortable Content Fuctions - Start ------
     *----------------------------------------------*/

    function setup_builder_ui(){        
        setup_sortable_sidebar_blocks('div.thwecmf-builder-elm-layers', '.thwecmf-sortable-row-handle', '.thwecmf-rows', '.thwecmf-element-set,.thwecmf-builder-elm-layers', sortable_start_handler, sortable_out_handler, sortable_stop_handler, null, sortable_receive_handler, sortable_update_handler);
        setup_sortable_sidebar_blocks('div.thwecmf-column-set', '.sortable-col-handle', '> div.thwecmf-columns', '', sortable_start_handler, sortable_out_handler, sortable_stop_handler, null, null, sortable_update_handler);
        setup_sortable_sidebar_blocks('div.thwecmf-element-set', '.sortable-elm-handle', 'div.thwecmf-hooks,div.thwecmf-elements:not(.panel-add-btn)', '.thwecmf-element-set,div.thwecmf-builder-elm-layers', sortable_start_handler, sortable_out_handler, sortable_stop_handler, null, null, sortable_update_handler);
    }
    
    function setup_sortable_sidebar_blocks(elm, handle, items, connectWith, start_handler, out_handler, stop_handler, over_handler, receive_handler, update_handler){
        $(elm).sortable({
            handle: handle,
            axis:'x,y',
            items: items,
            scroll: true,
            cursor: "move",
            helper:"clone",
            placeholder: "sortable-row-placeholder",
            connectWith: connectWith,
            forcePlaceholderSize: true,
            start: start_handler,
            out: out_handler,
            over: over_handler,
            stop: stop_handler,
            receive: receive_handler,
            update: update_handler
        });
        $(elm).disableSelection();
    }


    function sortable_start_handler(event, ui){
        BLOCK_DRAGG = ui.item.attr('class');
        BLOCK_DRAGG = $.trim(BLOCK_DRAGG.replace('thwecmf-panel-builder-block',''));
            $(ui.helper).addClass('dragg');
            if(BLOCK_DRAGG == 'thwecmf-rows'){
                ui.helper.find('.thwecmf-column-set').css('display','none');
                ui.helper.css('height','unset');
            }else if(BLOCK_DRAGG == 'thwecmf-columns'){  
                ui.helper.find('.thwecmf-element-set').css('display','none');
                ui.helper.css('height','unset');
                ui.placeholder.addClass('ui-sortable-placeholder-columns');
            }else if(BLOCK_DRAGG == 'thwecmf-elements' || BLOCK_DRAGG == 'thwecmf-hooks'){
                ui.placeholder.addClass('ui-sortable-placeholder-element-hooks');
            }
    }

    function sortable_out_handler(event, ui){
        if(BLOCK_DRAGG == 'thwecmf-rows' || BLOCK_DRAGG == 'thwecmf-elements'){
            if(ui.item.closest('.thwecmf-columns').length > 0){
                var prev_parent_id = ui.item.closest('.thwecmf-columns').attr('id');
                ui.item.data('prev-parent',prev_parent_id);
            }
        }
    }

    function sortable_stop_handler(event, ui){ 
        BLOCK_DRAGG == '';
        $(ui.helper).removeClass('dragg');
        if($(this).hasClass('thwecmf-elements') && ui.item.hasClass('thwecmf-elements')){ 
        }
    }
    
    function sortable_over_handler(event, ui){
    }

    function sortable_receive_handler(event, ui){
       if($(this).hasClass('thwecmf-builder-elm-layers') && (BLOCK_DRAGG == 'thwecmf-elements' || BLOCK_DRAGG == 'thwecmf-hooks')){
            ui.placeholder.hide();
            ui.sender.sortable('cancel');  
            alert('Cannot place an element outside a column');
        }
    }
    
    function sortable_update_handler(event, ui){
        var track_id = 'tbf_'+ui.item.attr('id');
        var next_id =  ui.item.next().attr('id');
        var prev_id =  ui.item.prev().attr('id');
        if(prev_id){
            prev_id = 'tbf_'+prev_id;
            $( $('#tbf_t_builder').find('#'+track_id) ).insertAfter($('#'+prev_id));
        }else{;
            next_id = 'tbf_'+next_id;
            $( $('#tbf_t_builder').find('#'+track_id) ).insertBefore($('#'+next_id));
        }
        if(ui.item.closest('.thwecmf-element-set').closest('.thwecmf-columns').length > 0){  
            var prev_parent = ui.item.data('prev-parent'); 
            var current_parent = ui.item.closest('.thwecmf-element-set').closest('.thwecmf-columns').attr('id');
            var track_id = ui.item.attr('id');
            var next_id =  ui.item.next().attr('id');
            var prev_id =  ui.item.prev().attr('id');
            if(ui.item.closest('.thwecmf-columns').attr('id')){
                var column_id = ui.item.closest('.thwecmf-columns').attr('id');
            }
            var data = $('#tbf_t_builder').find('#tbf_'+track_id);
            if(prev_id){
                $(data).insertAfter($('#tbf_'+prev_id));
            }else if(next_id){
                $(data).insertBefore($('#tbf_'+next_id));
            }
            else if(!next_id && !prev_id){
                $('#tbf_t_builder').find('#tbf_'+column_id).html(data);
            }
            var prev_parent_ref = $('#tbf_'+prev_parent);
            if(prev_parent_ref.find('.thwecmf-builder-block').length < 1 && prev_parent_ref.find('.thwecmf-hook-code').length < 1){
               $('#tbf_'+prev_parent).html(EMPTY_TD_DATA);
            }
        }
    }

    function builder_prepare_save_template(elm){
        var json_tree = json_from_sb_layers();
        var template_name_valid = true;
        var blank_builder_valid = true;
        var template_builder = $('#tbf_t_builder');
        var block_length = template_builder.find('.thwecmf-block').length;
        var hook_length = template_builder.find('.thwecmf-hook-code').length;
        var tname = $('#thwecmf_template_save_name').val();    
        var tcontent = $('#thwecmf_drag_n_drop').innerHTML;
        var tcss = $('#thwecmf_template_css').html();
        var input_validation = validate_temp_name(tname,'Template');
        template_name_valid = input_validation.length === 0 ? true : false;
        blank_builder_valid = block_length > 0 || hook_length > 0 ? true : false;
        if(!blank_builder_valid){
            alert('Add elements to save the template');
        }            
        else if(!template_name_valid){
            $.each(input_validation, function( key, value ) {
                if(!value['status']){
                    alert(value['message']);
                }
            });
        }else if(template_name_valid && blank_builder_valid){
            template_builder.attr('data-track-save',template_builder.attr('data-global-id'));
            template_prepare_data(tname, tcontent, tcss, json_tree);
            template_builder.attr('data-css-change','true');
        }
    }

    function json_from_sb_layers(){
        var layer_data = $('.thwecmf-tbuilder-elm-wrapper').find('.thwecmf-sidebar-config-elm-layers .thwecmf-builder-elm-layers');
            var row_count = 0;
            var row_struct={};
            var row_array = [];
        layer_data.find('> .thwecmf-rows.thwecmf-panel-builder-block').each(function(index, el) {
            var row_data = {};
            var col_struct={};
            var col_array = [];
            $(this).find('> .thwecmf-column-set > .thwecmf-columns.thwecmf-panel-builder-block').each(function(index, el) {
                var col_data = {};
                var elm_struct={};
                var elm_array = [];
                $(this).find('> .thwecmf-element-set > .thwecmf-panel-builder-block').each(function(index, el) {
                    var elm_data = {};
                    if($(this).hasClass('thwecmf-elements')){
                        var json_blk_id     = $(this).attr('id');
                        var json_blk_name   =  $(this).find('.thwecmf-block-settings .thwecmf-template-action-edit').attr('data-icon-attr');
                        var json_blk_obj    = $('#tbf_'+json_blk_id);
                        elm_data.data_id    = json_blk_id;
                        elm_data.data_type  = 'element';
                        elm_data.data_name  = json_blk_name;
                        elm_data.data_css   = json_blk_obj.attr('data-css-props');
                        elm_data.data_text  = json_blk_obj.attr('data-text-props');
                        elm_data.data_misc  = is_content_inlcude_img(json_blk_name) ? json_blk_obj.attr('data-misc') : "";
                        elm_data.child      = $(this).find('.thwecmf-element-name').text();
                        elm_array.push(elm_data);
                    }else if($(this).hasClass('thwecmf-hooks')){
                        elm_data.data_id = $(this).attr('id');
                        elm_data.data_type = 'hook';
                        var hook_names = $(this).find('.thwecmf-hook-name').text();
                        var hook_suffix = hook_names == 'Downloadable Product' ? 'table' : 'hook';
                        hook_names = hook_names+'_'+hook_suffix;
                        elm_data.data_name = hook_names.toLowerCase().replace(/\s+/g, "_");
                        elm_data.child = $(this).find('.thwecmf-hook-name').text();
                        elm_array.push(elm_data);
                    }
                });

                col_data.data_id = $(this).attr('id');
                col_data.data_type = 'column';
                col_data.data_name = $(this).find('.thwecmf-block-settings .thwecmf-template-action-edit').attr('data-icon-attr');
                col_data.data_css = $('#tbf_'+$(this).attr('id')).attr('data-css-props');
                col_data.child = elm_array;
                col_array.push(col_data);
            });
            row_data.data_id = $(this).attr('id');
            row_data.data_type = 'row';
            row_data.data_name = $(this).find('.thwecmf-block-settings .thwecmf-template-action-edit').attr('data-icon-attr');
            row_data.data_css = $('#tbf_'+$(this).attr('id')).attr('data-css-props');
            row_data.data_text = $('#tbf_'+$(this).attr('id')).attr('data-text-props');
            row_data.data_count = $('#tbf_'+$(this).attr('id')).attr('data-column-count');
            row_data.child = col_array;
            row_array.push(row_data); 
        });
        row_struct.row = row_array;
        row_struct.data_id = 't_builder';
        row_struct.data_type = 'builder';
        row_struct.track_save = $('#tbf_t_builder').attr('data-global-id');
        row_struct.data_css = $('#tbf_t_builder').attr('data-css-props');
        var json_row = JSON.stringify(row_struct);
        return json_row;
    }


    function template_prepare_data(name, content, css, json_tree){
        $('#thwecmf_template_save_name').attr('value', $('#thwecmf_template_save_name').val());
        var render_hooks = true;
        template_preview_content(render_hooks);
        var template_data = $('#thwecmf_tbuilder_editor_preview').html();
        var css_cleaned = css+$('#thwecmf_template_css_preview_override').html();
        prepare_ajax_save(name, template_data, css_cleaned, json_tree);
    }

    function prepare_ajax_save(name, template_data, css_cleaned, json_tree){
        var template_ajax_load = $('#thwecmf-ajax-load-modal');
        var sample_data = {
            thwecmf_security: thwecmf_admin_var.ajax_nonce,
            action: 'thwecmf_template_actions',
            thwecmf_action_index : 'settings',
            template_name: name,
            template_render_data:  template_data,
            template_render_css: css_cleaned,
            template_json_tree : json_tree
        };
        $.ajax({
            type: 'POST',
            url: ajaxurl,                        
            data: sample_data,
            beforeSend: function(){
                template_ajax_load.addClass("thwecmf-ajax-loading");
            },
            success:function(data){
                var msg = data ? "Template Saved" : "Template or Settings was not Saved. Try again."
                var msg_class = data ? "success" : "error";
                $('#thwecmf_builder_save_messages').html(msg).addClass('thwecmf-show-save thwecmf-save-'+msg_class+'');
                setTimeout(function(){
                    $('#thwecmf_builder_save_messages').removeClass("thwecmf-show-save thwecmf-save-"+msg_class+"");
                },3000);
            },
            complete: function() {
                template_ajax_load.removeClass("thwecmf-ajax-loading");
              
            },
            error: function(){
                $('#thwecmf_builder_save_messages').html('Something went wrong').addClass('thwecmf-show-save thwecmf-save-error');
                setTimeout(function(){
                        $('#thwecmf_builder_save_messages').removeClass("thwecmf-show-save thwecmf-save-error");
                    },3000);
            }
        });
    }

    function template_preview_content(show_hooks){
        var preview_html = $('#tbf_t_builder').clone(true);
        clean_preview_html_content(preview_html);
        // preview_html.find('img').each(function(index, el) {
        //     var i_w = $(this).attr('width');
        //     var i_h = $(this).attr('height');
        //     if(!empty(i_w) && !empty(i_h)){
        //         $(this).attr('width', $(this).width());
        //         $(this).attr('height', $(this).height());
        //     }
        // });
        preview_html.find('.thwecmf-btn-add-element').remove();
        preview_html.find('.thwecmf-builder-block').each(function(index, el) {
            var block_name = $(this).data('block-name');
            if(show_hooks){
                manage_dynamic_address_support($(this), block_name);
            }
            if($(this).attr('id')){
                var id = $(this).attr('id');
                id = id.replace('tbf_','tpf_');
                $(this).attr('id',id);
            }
            if($(this).hasClass('thwecmf-row')){
                $(this).find(' tbody> tr > .thwecmf-columns').each(function(index, el) {
                    var id = $(this).attr('id');
                    id = id.replace('tbf_','tpf_');
                    $(this).attr('id',id);
                });
            }
        });
        preview_html.find('.thwecmf-builder-block').removeClass('thwecmf-builder-block');
        preview_html.find('[data-css-props]').each(function(index, el) {
            $(this).removeAttr('data-css-props');
            if($(this).attr('data-text-props')){
                $(this).removeAttr('data-text-props');
            }
        });
        preview_html.removeAttr('data-css-props');
        preview_html.removeAttr('data-global-id');
        preview_html.removeAttr('data-track-save');
        preview_html.removeAttr('data-css-change');
        $('#thwecmf_tbuilder_editor_preview').html(preview_html);
    }

    function clean_preview_html_content(elm){
        elm.find('p.thwecmf-hook-code').each(function(index, el) {
            $(this).removeAttr('id');
        });
        elm.attr('id',elm.attr('id').replace('tbf_','tpf_'));
        elm.removeClass('thwecmf-dropable sortable ui-sortable ui-droppable');
        elm.find('.thwecmf-icon-panel').remove(); // Removing all icon panels of rows
        elm.find('.thwecmf-columns .dashicons-edit').remove(); // Removing all icon panels inside columns
        elm.find('input[type="hidden"]').remove(); // Removing all hidden fields
        elm.find('.thwecmf-columns').css('min-height','0');
        elm.find('.ui-sortable').removeClass('ui-sortable ui-droppable');
    }

    function manage_dynamic_address_support(block_elm, block_name){
        if(block_name == "thwecmf_billing_address"){
            hook_position_manager(block_elm,'{thwecmf_billing_address}','thwecmf-billing-body',false,false);
        }
        if(block_name == "thwecmf_shipping_address"){
            hook_position_manager(block_elm,'{thwecmf_shipping_address}','thwecmf-shipping-body',false,false);
            extra_address_supports(block_elm,block_name);
        }
    }

    function hook_position_manager($obj,$hook_name,$class_name,$control,$position){
        var elm_blk = $obj.closest('.thwecmf-element-block');
        var elm_col = $obj.closest('.thwecmf-columns');
        var row_col = $obj.closest('.thwecmf_column_layout');
        var insert = '';
        if($control){
            if(elm_blk.siblings().length){
                insert = elm_blk;
            }else if(elm_blk.closest('.thwecmf-column-padding').length){
                insert = elm_col;
            }else{
                insert = row_col;
            }
            if($position){
                $('<span>'+$hook_name+'</span>').insertAfter(insert);          
            }else{
                $('<span>'+$hook_name+'</span>').insertBefore(insert); 
            }
        }
        else{
            $obj.find('.'+$class_name).html('<span>'+$hook_name+'</span>'); 
        }
    }

    function extra_address_supports($elm,$name){
        if($name == 'thwecmf_shipping_address'){
            var shipping_table = $elm.find('> tbody > tr > td');
            shipping_table.prepend('<span>{thwecmf_before_shipping_address}</span>');
            shipping_table.append('<span>{thwecmf_after_shipping_address}</span>');
        }
    }

    function edit_template_event_listner(elm){
        var form = $('#thwecmf_edit_template_form');
        var template_to_edit = get_field_value(form, 'select', 'edit_template');
        if(template_to_edit==''){
            alert('Select a template to edit');
            event.preventDefault();
        }else if($.inArray(template_to_edit, WECMF_FREE_TEMP) == -1){
            alert('Cheating huh?');
            event.preventDefault();
        }else{
            $('input[name="template_to_edit"]').val(template_to_edit);
        }
    }

    function template_delete_action(){
        $('#delete_template').click(function(event) {
            var form = $('#thwecmf_edit_template_form');
            var value = get_field_value(form, 'select', 'edit_template');
            if(value == ''){
                alert('Select template to delete');
                event.preventDefault();
            }else{
                var delete_option = confirm('Delete the selected template ?');
                if(!delete_option){
                    event.preventDefault();
                }
            }
        });
    }

    function template_map_validation(event, elm){
        return false;
    }

	return {
        render_edit_block_sidebar_form : render_edit_block_sidebar_form,
        setup_category_toggle : setup_category_toggle,
        click_block_edit_sidebar_form_save : click_block_edit_sidebar_form_save,
        delete_builder_blocks : delete_builder_blocks,
        clear_tbuilder_sidebar : clear_tbuilder_sidebar,
        setup_wp_image_upload : setup_wp_image_upload,
        add_row_builder_action : add_row_builder_action,
        template_sidebar_back_navigation : template_sidebar_back_navigation,
        remove_image_uploaded : remove_image_uploaded,
        click_test_mail_button : click_test_mail_button,
        close_test_mail_box : close_test_mail_box,
        click_test_mail_action : click_test_mail_action,
        builder_prepare_save_template : builder_prepare_save_template,
        edit_template_event_listner : edit_template_event_listner,
        template_map_validation : template_map_validation
   	};
}(window.jQuery, window, document));	

function thwecmfEditBuilderBlocks(elm, blockId, blockName){
    thwecmf_settings.render_edit_block_sidebar_form(elm, blockId, blockName);    
}

function thwecmfClearTemplateBuilder(elm){
    thwecmf_settings.clear_tbuilder_sidebar(elm);
}

function thwecmfDeleteBuilderBlocks(elm){
    thwecmf_settings.delete_builder_blocks(elm);    
}


function thwecmfUploadImage(elm,prop){
    thwecmf_settings.setup_wp_image_upload(elm,prop);
}

function thwecmfClickAddRow(elm){
    thwecmf_settings.add_row_builder_action(elm);
}

function thwecmfSidebarBackNavigation(elm){
    thwecmf_settings.template_sidebar_back_navigation(elm);
}

function thwecmfCollapseCategory(elm){
    thwecmf_settings.setup_category_toggle(elm);
}

function thwecmfSidebarEditElementForm(elm){
    thwecmf_settings.click_block_edit_sidebar_form_save(elm)
}

function thwecmfRemoveImgUploaded(elm){
    thwecmf_settings.remove_image_uploaded(elm);
}

function thwecmfClickTestMail(elm){
    thwecmf_settings.click_test_mail_button(elm);
}

function thwecClickTestMailAction(elm){
    thwecmf_settings.click_test_mail_action(elm);
}

function thwecmfCloseTestMail(elm){
    thwecmf_settings.close_test_mail_box(elm);
}

function thwecmfSaveTemplate(elm){
    thwecmf_settings.builder_prepare_save_template(elm);    
}

function thwecmfTemplateEditListner(elm){
    thwecmf_settings.edit_template_event_listner(elm);
}

function thwecmfTemplateMapValidation(elm){
   thwecmf_settings.template_map_validation(elm);
}
