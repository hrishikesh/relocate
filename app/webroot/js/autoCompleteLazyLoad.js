$(document).ready(function(){

    function isScrollbarBottom(container) {
        var height = container.outerHeight();
        var scrollHeight = container[0].scrollHeight;
        var scrollTop = container.scrollTop();
        return (scrollTop >= scrollHeight - height);
    }

    $.extend($.ui.autocomplete.prototype, {
        _renderMenu: function (ul, items) {
            var self = this;
            //remove scroll event to prevent attaching multiple scroll events to one container element

            $(ul).unbind("scroll");


            self._scrollMenu(ul, items);
        },

        _scrollMenu: function (ul, items) {
            var self = this;
            var maxShow = 10;
            var results = [];
            var pages = Math.ceil(items.length / maxShow);
            results = items.slice(0, maxShow);

            if (pages > 1) {
                $(ul).scroll(function () {
                    if (isScrollbarBottom($(ul))) {
                        ++window.pageIndex;
                        if (window.pageIndex >= pages) return;

                        results = items.slice(window.pageIndex * maxShow, window.pageIndex * maxShow + maxShow);

                        //append item to ul
                        $.each(results, function (index, item) {
                            self._renderItem(ul, item);
                        });
                        //refresh menu
                        //self.menu.deactivate();
                        self.menu.refresh();
                        // size and position menu
                        ul.show();
                        self._resizeMenu();
                        ul.position($.extend({
                            of: self.element
                        }, self.options.position));
                        if (self.options.autoFocus) {
                            self.menu.next(new $.Event("mouseover"));
                        }
                    }
                });
            }

            $.each(results, function (index, item) {
                self._renderItem(ul, item);
            });
        }
    });
});