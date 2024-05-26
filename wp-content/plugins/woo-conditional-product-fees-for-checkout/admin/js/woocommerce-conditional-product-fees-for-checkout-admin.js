(function ($) {
    'use strict';
    jQuery(".multiselect2").select2();
    function allowSpeicalCharacter(str){
        return str.replace('&#8211;','–').replace("&gt;",">").replace("&lt;","<").replace("&#197;","Å");
    }
    function productFilter() {
        jQuery('.product_fees_conditions_values_product').each(function() {
            $('.product_fees_conditions_values_product').select2({
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            value: params.term,
                            action: 'wcpfc_pro_product_fees_conditions_values_product_ajax'
                        };
                    },
                    processResults: function (data) {
                        var options = [];
                        if (data) {
                            $.each(data, function (index, text) {
                                options.push({id: text[0], text: allowSpeicalCharacter(text[1])});
                            });

                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        });
    }
    
/* Premium Code Stripped by Freemius */

    function setAllAttributes(element,attributes){
        Object.keys(attributes).forEach(function (key) {
            element.setAttribute(key, attributes[key]);
            // use val
        });
        return element;
    }

    function numberValidateForAdvanceRules() {
        $('.number-field').keypress(function (e) {
            var regex = new RegExp("^[0-9-%.]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        $('.qty-class').keypress(function (e) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        $('.weight-class, .price-class').keypress(function (e) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
    }
    $(window).load(function () {
        jQuery(".multiselect2").select2();

        $('a[href="admin.php?page=wcpfc-pro-list"]').parent().addClass('current');
        $('a[href="admin.php?page=wcpfc-pro-list"]').addClass('current');

        $("#fee_settings_start_date").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: '0',
            onSelect: function (selected) {
                var dt = $(this).datepicker('getDate');
                dt.setDate(dt.getDate() + 1);
                $("#fee_settings_end_date").datepicker("option", "minDate", dt);
            }
        });
        $("#fee_settings_end_date").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: '0',
            onSelect: function (selected) {
                var dt = $(this).datepicker('getDate');
                dt.setDate(dt.getDate() - 1);
                $("#fee_settings_start_date").datepicker("option", "maxDate", dt);
            }
        });
        var ele = $('#total_row').val();
        var count;
        if (ele > 2) {
            count = ele;
        } else {
            count = 2;
        }
        $('body').on('click', '#fee-add-field', function () {
            var fee_add_field=$('#tbl-product-fee tbody').get(0);
            
            var tr = document.createElement("tr"); 
            tr=setAllAttributes(tr,{"id":"row_"+count});
            fee_add_field.appendChild(tr);

            // generate td of condition
            var td = document.createElement("td"); 
            td=setAllAttributes(td,{});
            tr.appendChild(td);
            var conditions = document.createElement("select"); 
            conditions=setAllAttributes(conditions,{
                "rel-id":count,
                "id":"product_fees_conditions_condition_"+count, 
                "name":"fees[product_fees_conditions_condition][]", 
                "class":"product_fees_conditions_condition"
            });
            conditions=insertOptions(conditions,get_all_condition());
            td.appendChild(conditions);
            // td ends

            // generate td for equal or no equal to
            td = document.createElement("td"); 
            td = setAllAttributes(td,{});
            tr.appendChild(td);
            var conditions_is = document.createElement("select"); 
            conditions_is=setAllAttributes(conditions_is,{
                "name":"fees[product_fees_conditions_is][]", 
                "class":"product_fees_conditions_is product_fees_conditions_is_"+count
            });
            conditions_is=insertOptions(conditions_is,condition_types());
            td.appendChild(conditions_is);
            // td ends

            // td for condition values
            td = document.createElement("td"); 
            td = setAllAttributes(td,{"id": "column_"+count});
            tr.appendChild(td);
            condition_values(jQuery('#product_fees_conditions_condition_'+count));

            var condition_key = document.createElement("input");
            condition_key=setAllAttributes(condition_key,{
                "type":"hidden", 
                "name":"condition_key[value_"+count+"][]",
                "value":"",
            });
            td.appendChild(condition_key);
            var conditions_values_index =jQuery(".product_fees_conditions_values_" + count).get(0);
            jQuery(".product_fees_conditions_values_" + count).trigger('change');
            jQuery(".multiselect2").select2();
            // td ends

            // td for delete button
            td = document.createElement("td");
            tr.appendChild(td);
            var delete_button = document.createElement("a");
            delete_button=setAllAttributes(delete_button,{
                "id": "fee-delete-field", 
                "rel-id": count,
                "title":coditional_vars.delete,
                "class":"delete-row", 
                "href": "javascript:;"
            });
            var deleteicon=document.createElement('i');
            deleteicon=setAllAttributes(deleteicon,{
                "class": "fa fa-trash"
            });
            delete_button.appendChild(deleteicon);
            td.appendChild(delete_button);
            // td ends

             count++;
        });

        function insertOptions(parentElement,options){
            for(var i=0;i<options.length;i++){
                if(options[i].type=='optgroup'){
                    var optgroup=document.createElement("optgroup");
                    optgroup=setAllAttributes(optgroup,options[i].attributes);
                    for(var j=0;j<options[i].options.length;j++){
                        var option=document.createElement("option");
                        option=setAllAttributes(option,options[i].options[j].attributes);
                        option.textContent=options[i].options[j].name;
                        optgroup.appendChild(option);
                    }
                    parentElement.appendChild(optgroup);
                } else {
                    var option=document.createElement("option");
                    option=setAllAttributes(option,options[i].attributes);
                    option.textContent=allowSpeicalCharacter(options[i].name);
                    parentElement.appendChild(option);
                }

            }
            return parentElement;
            
        }
        function allowSpeicalCharacter(str){
            return str.replace('&#8211;','–').replace("&gt;",">").replace("&lt;","<").replace("&#197;","Å");    
        }

        function get_all_condition(){
            return [
                { 
                    "type": "optgroup",
                    "attributes" : {"label" : coditional_vars.location_specific},
                    "options" :[
                        {"name": coditional_vars.country,"attributes" : {"value":"country"} },
                        
/* Premium Code Stripped by Freemius */

                    ]
                },
                { 
                    "type": "optgroup",
                    "attributes" : {"label" : coditional_vars.product_specific},
                    "options" :[
                        {"name": coditional_vars.cart_contains_product,"attributes" : {"value":"product"} },
                        
/* Premium Code Stripped by Freemius */

                        {"name": coditional_vars.cart_contains_tag_product,"attributes" : {"value":"tag"} },
                    ]
                },
                {
                    "type": "optgroup",
                    "attributes" : {"label" : coditional_vars.user_specific},
                    "options": [
                        {"name" : coditional_vars.user, "attributes": {"value" : "user"}},
                        
/* Premium Code Stripped by Freemius */

                    ]
                },
                {
                    "type": "optgroup",
                    "attributes" : {"label" : coditional_vars.cart_specific},
                    "options": [
                         {"name" : coditional_vars.cart_subtotal_before_discount, "attributes": {"value" : "cart_total"}},
                        
/* Premium Code Stripped by Freemius */

                         {"name" : coditional_vars.quantity, "attributes": {"value" : "quantity"}},
                        
/* Premium Code Stripped by Freemius */

                    ]
                },
                
/* Premium Code Stripped by Freemius */


            ];
        }
        var default_placeholder=jQuery('#fee_settings_product_cost').attr('placeholder');
        $('#fee_settings_select_fee_type').change(function(){
            if(jQuery(this).val()=='fixed'){
                jQuery('#fee_settings_product_cost').attr('placeholder',default_placeholder);    
            } else if(jQuery(this).val()=='percentage'){
                jQuery('#fee_settings_product_cost').attr('placeholder','%');
            }
            
        });

        $('body').on('change', '.product_fees_conditions_condition',function(){
            condition_values(this);
        });
        function condition_values(element) {
            var posts_per_page = 3; // Post per page
            var page = 0; // What page we are on.
            var condition = $(element).val();
            var count = $(element).attr('rel-id');
            var column=jQuery('#column_' + count).get(0);
            jQuery(column).empty();
            var loader=document.createElement('img');
            loader = setAllAttributes(loader,{'src':coditional_vars.plugin_url+'images/ajax-loader.gif'});
            column.appendChild(loader);

            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'wcpfc_pro_product_fees_conditions_values_ajax',
                    'wcpfc_pro_product_fees_conditions_values_ajax': $('#wcpfc_pro_product_fees_conditions_values_ajax').val(),
                    'condition': condition,
                    'count': count,
                    'posts_per_page': posts_per_page,
                    'offset': (page * posts_per_page),
                },
                contentType: "application/json",
                success: function(response) {
                    page++;
                    var condition_values;
                    jQuery('.product_fees_conditions_is_' + count).empty();
                    var column=jQuery('#column_' + count).get(0);
                    var condition_is=jQuery('.product_fees_conditions_is_' + count).get(0);
                    if (condition == 'cart_total'
                        || condition == 'quantity'
                        
/* Premium Code Stripped by Freemius */

                    ) {
                        condition_is=insertOptions(condition_is,condition_types(true));
                    } else {
                        condition_is=insertOptions(condition_is,condition_types(false));
                    }
                    jQuery('.product_fees_conditions_is_' + count).trigger('change');
                    jQuery(column).empty();

                    var condition_values_id='';
                    var extra_class = '';
                    if(condition == 'product'){
                        condition_values_id='product-filter-' + count;
                        extra_class = 'product_fees_conditions_values_product';
                    }
                    
/* Premium Code Stripped by Freemius */


                    if(isJson(response)){
                        condition_values = document.createElement("select");
                        condition_values=setAllAttributes(condition_values,{
                            "name":  "fees[product_fees_conditions_values][value_"+count+"][]",
                            "class": "wcpfc_select product_fees_conditions_values product_fees_conditions_values_" + count + " multiselect2 " + extra_class,
                            "multiple": "multiple",
                            "id":condition_values_id
                        });
                        column.appendChild(condition_values);
                        var data=JSON.parse(response);
                        condition_values=insertOptions(condition_values,data);
                    } else{
                        var input_extra_class;
                        if (condition == 'quantity') {
                            input_extra_class = ' qty-class'
                        }
                        if (condition == 'weight') {
                            input_extra_class = ' weight-class'
                        }
                        if (condition == 'cart_total' || condition == 'cart_totalafter') {
                            input_extra_class = ' price-class'
                        }

                        condition_values = document.createElement(jQuery.trim(response));
                        condition_values=setAllAttributes(condition_values,{
                            "name":  "fees[product_fees_conditions_values][value_"+count+"]",
                            "class": "product_fees_conditions_values" + input_extra_class,
                            "type": "text",

                        });
                        column.appendChild(condition_values);
                    }
                    column=$('#column_' + count).get(0);
                    var input_node=document.createElement('input');
                    input_node=setAllAttributes(input_node,{
                        'type':'hidden',
                        'name':'condition_key[value_'+count+'][]',
                        'value':''
                    });
                    column.appendChild(input_node);

                    
/* Premium Code Stripped by Freemius */


                    jQuery(".multiselect2").select2();

                        productFilter();

                    
/* Premium Code Stripped by Freemius */

                    numberValidateForAdvanceRules();
                }
            });
        }
        function condition_types(text=false){
            if(text==true){
                return [
                        {"name": coditional_vars.equal_to,"attributes" : {"value":"is_equal_to"} },
                        {"name": coditional_vars.less_or_equal_to,"attributes" : {"value":"less_equal_to"} },
                        {"name": coditional_vars.less_than,"attributes" : {"value":"less_then"} },
                        {"name": coditional_vars.greater_or_equal_to,"attributes" : {"value":"greater_equal_to"} },
                        {"name": coditional_vars.greater_than,"attributes" : {"value":"greater_then"} },
                        {"name": coditional_vars.not_equal_to,"attributes" : {"value":"not_in"} },
                ];
            } else {
                return  [
                        {"name": coditional_vars.equal_to,"attributes" : {"value":"is_equal_to"} },
                        {"name": coditional_vars.not_equal_to,"attributes" : {"value":"not_in"} },
                    ];

            }

        }

        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (err) {
                return false;
            }
            return true;
        }

        productFilter();

        
/* Premium Code Stripped by Freemius */


        $('body').on('click', '.condition-check-all', function () {
            $('input.multiple_delete_fee:checkbox').not(this).prop('checked', this.checked);
        });

        $('body').on('click', '#detete-conditional-fee', function () {
            if ($('.multiple_delete_fee:checkbox:checked').length == 0) {
                alert(coditional_vars.select_atleast_one_checkbox);
                return false;
            }
            if (confirm(coditional_vars.delete_confirmation_msg)) {
                var allVals = [];
                $(".multiple_delete_fee:checked").each(function () {
                    allVals.push($(this).val());
                });
                $.ajax({
                    type: 'GET',
                    url: coditional_vars.ajaxurl,
                    data: {
                        'action': 'wcpfc_pro_wc_multiple_delete_conditional_fee',
                        'nonce': coditional_vars.dsm_ajax_nonce,
                        'allVals': allVals
                    },
                    success: function(response) {
                        alert(response);
                        $(".multiple_delete_fee").prop("checked", false);
                        location.reload();
                    }
                });
            }
        });

        $('.disable-enable-conditional-fee').click(function () {
            if ($('.multiple_delete_fee:checkbox:checked').length == 0) {
                alert(coditional_vars.select_chk);
                return false;
            }
            if (confirm(coditional_vars.change_status)) {
                var allVals = [];
                $(".multiple_delete_fee:checked").each(function () {
                    allVals.push($(this).val());
                });

                $.ajax({
                    type: 'GET',
                    url: coditional_vars.ajaxurl,
                    data: {
                        'action': 'wcpfc_pro_wc_disable_conditional_fee',
                        'nonce': coditional_vars.disable_fees_ajax_nonce,
                        'do_action': $(this).attr('id'),
                        'allVals': allVals
                    },
                    success: function(response) {
                        alert(response);
                        $(".multiple_delete_fee").prop("checked", false);
                        location.reload();
                    }
                });
            }
        });
        /* description toggle */
        $('span.woocommerce_conditional_product_fees_checkout_tab_description').click(function (event) {
            event.preventDefault();
            $(this).next('p.description').toggle();
        });

        if ($('.tablesorter') .length) {
            $(".tablesorter").tablesorter({
                headers: {
                    0: {
                        sorter: false
                    },
                    4: {
                        sorter: false
                    }
                }
            });
            var fixHelperModified = function (e, tr) {
                var $originals = tr.children();
                var $helper = tr.clone();
                $helper.children().each(function (index)
                {
                    $(this).width($originals.eq(index).width());
                });
                return $helper;
            };
            //Make diagnosis table sortable
            $("table#conditional-fee-listing tbody").sortable({
                helper: fixHelperModified,
                stop: function( event, ui ) {
                    var i=0;
                    var listing={};
                    jQuery('.ui-sortable-handle').each(function(){
                        listing[i]=jQuery(this).find('input').val();
                        i++;
                    });
                    $.ajax({
                        type: 'GET',
                        url: coditional_vars.ajaxurl,
                        contentType: "application/json",
                        data: {
                            'action': 'wcpfc_pro_product_fees_conditions_sorting',
                            'sorting_conditional_fee': jQuery('#sorting_conditional_fee').val(),
                            'listing': listing,
                        },
                        success: function(response) {
                        }
                    });

                }
            });
            $("table#conditional-fee-listing tbody").disableSelection();
        }

        
/* Premium Code Stripped by Freemius */


        $('[id^=fee_settings_product_cost]').keypress(validateNumber);

        function validateNumber(event) {
            var key = window.event ? event.keyCode : event.which;
            if (event.keyCode === 8 || event.keyCode === 46) {
                return true;
            } else if ( key < 48 || key > 57 ) {
                return false;
            } else if ( key == 45 ) {
                return true;
            } else if ( key == 37 ) {
                return true;
            }  else {
                return true;
            }
        };
        numberValidateForAdvanceRules();

        $(document).on('click', '#clone_fees', function () {
            var current_fees_id = $(this).attr('data-attr');
            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'wcpfc_pro_clone_fees',
                    'current_fees_id': current_fees_id
                },beforeSend: function() {
                    var div = document.createElement("div");
                    div = setAllAttributes(div,{
                        "class": "loader-overlay",
                    });

                    var img = document.createElement("img");
                    img = setAllAttributes(img,{
                        "id": "before_ajax_id",
                        "src": coditional_vars.ajax_icon
                    });

                    div.appendChild(img);

                    jQuery("#conditional-fee-listing").after(div);
                }, complete: function() {
                    jQuery(".wcpfc-main-table .loader-overlay").remove();
                }, success: function(response) {
                    console.log(response);
                    var response_data = JSON.parse(response);
                    if ("true" === jQuery.trim(response_data['0'])){
                        location.href = response_data['1'];
                    }
                }
            });
        });

        /*Start: Change shipping status form list section*/
        $(document).on('click', '#fees_status_id', function () {
            var current_fees_id = $(this).attr('data-smid');
            var current_value = $(this).prop( "checked" );
            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'wcpfc_pro_change_status_from_list_section',
                    'current_fees_id': current_fees_id,
                    'current_value': current_value
                },beforeSend: function() {
                    var div = document.createElement("div");
                    div = setAllAttributes(div,{
                        "class": "loader-overlay",
                    });

                    var img = document.createElement("img");
                    img = setAllAttributes(img,{
                        "id": "before_ajax_id",
                        "src": coditional_vars.ajax_icon
                    });

                    div.appendChild(img);
                    jQuery("#conditional-fee-listing").after(div);
                }, complete: function() {
                    jQuery(".wcpfc-main-table .loader-overlay").remove();
                }, success: function(response) {
                    alert(jQuery.trim(response));
                }
            });
        });
        /*End: Change shipping status form list section*/

        /*Start: Get last url parameters*/
        function getUrlVars() {
            var vars = [], hash;
            var get_current_url = coditional_vars.current_url;
            var hashes = get_current_url.slice(get_current_url.indexOf('?') + 1).split('&');
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        }
        /*End: Get last url parameters*/

        function setAllAttributes(element,attributes){
            Object.keys(attributes).forEach(function (key) {
                element.setAttribute(key, attributes[key]);
                // use val
            });
            return element;
        }


        //remove tr on delete icon click
        $('body').on('click', '.delete-row', function () {
            $(this).parent().parent().remove();
        });

        //Save Master Settings
        $(document).on('click', '#save_master_settings', function () {
            var chk_enable_logging;
            if($('#chk_enable_logging').prop("checked") == true) {
                chk_enable_logging = 'on';
            } else {
                chk_enable_logging = 'off';
            }
            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'wcpfc_pro_save_master_settings',
                    'chk_enable_logging': chk_enable_logging,
                },
                success: function(response) {
                    var div = document.createElement('div');
                    div = setAllAttributes(div,{
                        "class": "ms-msg"
                    });
                    div.textContent = coditional_vars.success_msg2;
                    $(div).insertBefore(".wcpfc-section-left .wcpfc-main-table");
                    $("html, body").animate({scrollTop: 0}, "slow");
                    setTimeout(function () {
                        $('.ms-msg').remove();
                    }, 2000);
                }
            });
        });

        $(document).on('click', '.fees-order', function () {
            saveAllIdOrderWise('on_click');
        });

        saveAllIdOrderWise('on_load');

        /*Start code for save all method as per sequence in list*/
        function saveAllIdOrderWise(position) {
            var smOrderArray = [];

            $('table#conditional-fee-listing tbody tr').each(function () {
                smOrderArray.push(this.id);
            });
            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'wcpfc_pro_sm_sort_order',
                    'smOrderArray': smOrderArray
                },
                success: function(response) {
                    if ('on_click' === jQuery.trim(position)) {
                        alert(coditional_vars.success_msg1);
                    }
                }
            });
        }

        /*Start: Change shipping status form list section*/
        $(document).on('click', '#shipping_status_id', function () {
            var current_shipping_id = $(this).attr('data-smid');
            var current_value = $(this).prop("checked");
            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'afrsm_pro_change_status_from_list_section',
                    'current_shipping_id': current_shipping_id,
                    'current_value': current_value
                },beforeSend: function() {
                    var div = document.createElement("div");
                    div = setAllAttributes(div,{
                        "class": "loader-overlay",
                    });

                    var img = document.createElement("img");
                    img = setAllAttributes(img,{
                        "id": "before_ajax_id",
                        "src": coditional_vars.ajax_icon
                    });

                    div.appendChild(img);
                    jQuery("#shipping-methods-listing").after(div);
                }, complete: function() {
                    jQuery(".afrsm-main-table .loader-overlay").remove();
                }, success: function(response) {
                    alert(jQuery.trim(response));
                }
            });
        });
        /*End: Change shipping status form list section*/

    });
    jQuery(window).on('load', function() {
        jQuery(".multiselect2").select2();
        function allowSpeicalCharacter(str){
            return str.replace('&#8211;','–').replace("&gt;",">").replace("&lt;","<").replace("&#197;","Å");
        }
        jQuery('.product_fees_conditions_values_product').each(function() {
            jQuery('.product_fees_conditions_values_product').select2({
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            value: params.term,
                            action: 'wcpfc_pro_product_fees_conditions_values_product_ajax'
                        };
                    },
                    processResults: function (data) {
                        var options = [];
                        if (data) {
                            jQuery.each(data, function (index, text) {
                                options.push({id: text[0], text: allowSpeicalCharacter(text[1])});
                            });

                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        });
        
/* Premium Code Stripped by Freemius */

    });
})(jQuery);

jQuery(document).ready(function () {
    if (jQuery(window).width() <= 980) {
        jQuery('.fees-pricing-rules .fees_pricing_rules .tab-content').click(function () {
            var acc_id = jQuery(this).attr('id');
            jQuery(".fees-pricing-rules .fees_pricing_rules .tab-content").removeClass('current');
            jQuery("#" + acc_id).addClass('current');
        });
    }
});

jQuery(window).resize(function () {
    if (jQuery(window).width() <= 980) {
        jQuery('.fees-pricing-rules .fees_pricing_rules .tab-content').click(function () {
            var acc_id = jQuery(this).attr('id');
            jQuery(".fees-pricing-rules .fees_pricing_rules .tab-content").removeClass('current');
            jQuery("#" + acc_id).addClass('current');
        });
    }
});