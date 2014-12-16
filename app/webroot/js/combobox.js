/**
 * Created by webonise on 21/9/14.
 */
(function( $ ) {
    $.widget( "ui.combobox", {
        //default option
        options:{
            value:false,
            showArrow:true
        },
        _create: function() {
            var self = this,
                access = this.options.value,
                select = this.element.hide(),
                selected = select.children( ":selected" ),
                value = selected.val() ? selected.text() : "";
            var input = this.input = $( "<input>")
                .insertBefore(select) // Changed insertAfter(select)
                .val( value )
                .autocomplete({
                    delay: 0,
                    position: {
                        my: "left top",
                        at: "left bottom",
                        collision: "fit flip"
                    },
                    minLength: 0,
                    source: function( request, response ) {
                        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                        response( select.children( "option" ).map(function() {
                            var text = $( this ).text();
                            if ( this.value && ( !request.term || matcher.test(text) ) )
                                return {
                                    label: text.replace(
                                        new RegExp(
                                            "(?![^&;]+;)(?!<[^<>]*)(" +
                                                $.ui.autocomplete.escapeRegex(request.term) +
                                                ")(?![^<>]*>)(?![^&;]+;)", "gi"
                                        ), "$1" /*"<strong>$1</strong>"*/  ),
                                    value: text,
                                    option: this
                                };
                        }) );
                    },
                    select: function( event, ui ) {
                        ui.item.option.selected = true;
                        self._trigger( "selected", event, {
                            item: ui.item.option
                        });
                        select.change();
                        select.focus();
                    },
                    change: function( event, ui ) {
                        if ( !ui.item ) {
                            var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
                                valid = false;

                            select.children( "option" ).each(function() {
                                if ( $( this ).text().match( matcher ) ) {
                                    this.selected = valid = true;
                                    return false;
                                }
                            });
                            if ( !valid ) {
                                // remove invalid value, as it didn't match anything
                                $( this ).val( "" );
                                select.val( "" );
                                input.data( "autocomplete" ).term = "";
                                return false;
                            }
                        }
                    }
                }).focus(function () {
                    //reset result list's pageindex when focus on
                    window.pageIndex = 0;
                    // $(this).autocomplete("search");
                })
                .addClass( "ui-widget ui-widget-content ui-corner-left countryDropDown").attr("disabled",access)
                .click(function(){
                    $(this).parent().find('span.ui-button').click();
                })
                /*.keydown(function(){
                    if(self.onclickVal != 'undefined' && $(this).val() == self.onclickVal) {
                        self.onclickVal = '';
                        $(this).val('');
                    }
                })*/;

            input.wrap("<div class='autoCompleteWrapper selectBoxWrapper'>");
            /****************************************************/
            input.data( "autocomplete" )._renderItem = function( ul, item ) {
                $(ul).addClass($(select).attr("id"));
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.label + "</a>" )
                    .appendTo( ul );
            };
            if(this.options.showArrow){
                this.button = $( "<span>&nbsp;</span>" )
                    .attr( "tabIndex", -1 )
                    .attr( "title", "Show All Items" )
                    .insertAfter( input )
                    .button({
                        icons: {
                            primary: "ui-icon-triangle-1-s"
                        },
                        text: false
                    })
                    .removeClass( "ui-corner-all" )
                    .addClass( "ui-corner-right ui-button-icon" )
                    .click(function() {
                        // close if already visible
                        if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
                            input.autocomplete( "close" );
                            return;
                        }
                        self.onclickVal = $(input).val();
                        // pass empty string as value to search for, displaying all results
                        input.autocomplete( "search", "" );
                        input.focus();
                    });
            }
        },

        destroy: function() {
            this.input.remove();
            this.button.remove();
            this.element.show();
            $.Widget.prototype.destroy.call( this );
        }
    });


})( jQuery );