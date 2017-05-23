/**
 * Created by zhaojunlike on 2016/11/14.
 */
;(function ($) {
    var _ele;
    var _eleItem;
    $.extend({
        eyCheckAll: function (ele, itemEle) {
            _ele = ele;
            _eleItem = itemEle;
            /**checkALL**/
            $(ele).on('click', function () {
                $(itemEle).prop("checked", this.checked);
            });
            $(itemEle).on('click', function () {
                var option = $(itemEle);
                option.each(function (i) {
                    if (!this.checked) {
                        $(ele).prop("checked", false);
                        return false;
                    } else {
                        $(ele).prop("checked", true);
                    }
                });
            });
        },
        eyGetCheckItemVal: function (attr) {
            var option = $(_eleItem);
            var data = [];
            option.each(function (i) {
                if (!this.checked) {
                    // false
                } else {
                    data.push($(this).attr(attr));
                }
            });
            return data;
        }
    })
})(jQuery);
