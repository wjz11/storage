$(function () {
    $.fn.initInputGroup = function (options) {
        var c = $.extend({
            widget: '',
            add: "<span class=\"glyphicon glyphicon-plus\"></span>",
            del: "<span class=\"glyphicon glyphicon-minus\"></span>",
            field: 'data',
            data: ['field0', 'field1', 'field2', 'field3', 'field4'],
        }, options);
        var _this = $(this);
        addInputGroup(0);
        function addInputGroup(order) {
            //创建行
            var inputGroup = $("<tr class='addtr' style='margin: 2px 0'></tr>");
            //创建第1个td
            var td1 = $("<td></td>");
            //插入隐藏域
            var widget0 = $("<input type='hidden' name='data_array[" + order + "][field0]'>");
            var widget1 = $("<input class='form-control' name='data_array[" + order + "][field1]'>");
            widget0.appendTo(td1);
            widget1.appendTo(td1);
            //创建第2个td
            var td2 = $("<td></td>");
            var widget2 = $("<input class='form-control' name='data_array[" + order + "][field2]'>");
            widget2.appendTo(td2);
            //创建第3个td
            var td3 = $("<td></td>");
            var td3_child = $("<div class='input-group'></div>");
            td3_child.appendTo(td3);
            var widget3 = $("<input type='number' min='1' name='data_array[" + order + "][field3]' class='form-control'>");
            var td3_child_span = $("<span class='input-group-addon'>元</span>");
            widget3.appendTo(td3_child);
            td3_child_span.appendTo(td3_child);
            //创建第4个td
            var td4 = $("<td></td>");
            var td4_child = $("<div class='input-group'></div>");
            td4_child.appendTo(td4);
            var widget4 = $("<input type='number' min='1' name='data_array[" + order + "][field4]' class='form-control'>");
            var td4_child_span = $("<span class='input-group-addon'>元</span>");
            widget4.appendTo(td4_child);
            td4_child_span.appendTo(td4_child);
            //赋值
            if (order < c.data.length) {
                widget0.val(c.data[order].field0);
                widget1.val(c.data[order].field1);
                widget2.val(c.data[order].field2);
                widget3.val(c.data[order].field3);
                widget4.val(c.data[order].field4);
            }
            //创建第5个td
            var inputGroupAddon2 = $("<td style='width:52px;'><span class='input-group-btn'></span></td>");
            var addBtn = $("<button class='btn btn-sm btn-primary' style='border-radius:4px;' type='button'>" + c.add + "</button>");
            addBtn.appendTo(inputGroupAddon2).on('click', function () {
                if ($(this).html() == c.del) {
                    $(this).parents('.addtr').remove();
                } else if ($(this).html() == c.add) {
                    $(this).html(c.del).addClass('btn-danger');
                    addInputGroup(order + 1);
                }
            });
            //将同一行每个td依次装进tr中
            inputGroup.append(td1, td2, td3, td4).append(inputGroupAddon2);
            _this.append(inputGroup);
            $('.time_begin, .time_end').datetimepicker({
                language: 'zh-CN',
                format: 'hh:ii',
                weekStart: 1,
                startView: 1,
                minView: 0,
                autoclose: true,
                minuteStep: 5
            });
            if (order >= c.data.length - 1) {
                return;
            }
            addBtn.trigger('click');
        }
    }
});