$(function(){
    var property = [];
    //模版
    var propertyChooseItemTemp = template.compile(
        '<div class="row property" data-name="{{name}}">'+
            '<div class="col-md-2">'+
                '<p class="form-control-static {{if isdelete}}text-danger{{/if}}">{{name}}</p>'+
                '{{if isdelete}}'+
                '<small class="text-danger">(该规格已被删除，不建议继续使用)</small>'+
                '{{/if}}'+
                '<input type="hidden" name="prop[]" value="{{name}}">'+
            '</div>'+
            '<div class="col-md-8">'+
                '<div class="row">'+
                    '{{each attributevalue as p}}'+
                    '<div class="col-xs-2">'+
                        '<label class="checkbox-inline i-checks"><input type="checkbox" name="propitem[{{name}}][]" value="{{p}}">{{p}}</label>'+
                    '</div>'+
                    '{{/each}}'+
                '</div>'+
            '</div>'+
            '<div class="col-md-2">'+
                '{{if !isdelete}}'+
                '<div class="input-group">'+
                    '<input type="text" class="form-control" placeholder="新增属性">'+
                    '<span class="input-group-btn"><button type="button" class="btn btn-default add-property-btn"><i class="glyphicon glyphicon-plus"></i></button></span>'+
                '</div>'+
                '{{/if}}'+
            '</div>'+
        '</div>'+
        '<div class="hr-line-dashed"></div>'
    );
    var propertyHeadTemp = template.compile(
        '<tr>'+
            '{{each names as name}}'+
                '{{if name.items.length != 0}}'+
                '<th class="text-center">{{name.name}}</th>'+
                '{{/if}}'+
            '{{/each}}'+
            '<th class="text-center">积分</th>'+
            '<th class="text-center">原价</th>'+
            // '<th class="text-center">现价</th>'+
            '<th class="text-center">库存</th>'+
        '</tr>'
    );
    var propertyBodyTemp = template.compile(
        '{{if combination.length != 0}}'+
            '{{each combination as combination}}'+
            '<tr>'+
                '<input type="hidden" name="property[]" value="{{combination.text}}">'+
                '{{each combination.item as value}}'+
                    '{{if value != "-"}}'+
                    '<td class="text-center">{{value}}</td>'+
                    '{{/if}}'+
                '{{/each}}'+
                '<td class="text-center form-group"><input type="text" class="form-control text-center property-integral" name="integral[{{combination.text}}]" data-type="integral" datatype="n" nullmsg="请输入积分" errormsg="请输入正确的积分"><span class="help-block m-b-none"></span></td>'+
                '<td class="text-center form-group"><input type="text" class="form-control text-center property-originalprice" name="originalprice[{{combination.text}}]" data-type="originalprice" datatype="num" nullmsg="请输入原价" errormsg="请输入正确的原价"><span class="help-block m-b-none"></span></td>'+
                // '<td class="text-center form-group"><input type="text" class="form-control text-center property-price" name="price[{{combination.text}}]" data-type="price" datatype="num" nullmsg="请输入现价" errormsg="请输入正确的现价"><span class="help-block m-b-none"></span></td>'+
                '<td class="text-center form-group"><input type="text" class="form-control text-center property-stock" name="stock[{{combination.text}}]" data-type="stock" datatype="n" nullmsg="请输入库存" errormsg="请输入正确的库存数量"><span class="help-block m-b-none"></span></td>'+
            '</tr>'+
            '{{/each}}'+
        '{{else}}'+
            '<tr>'+
                '<input type="hidden" name="property[]" value="">'+
                '<td class="text-center form-group"><input type="text" class="form-control text-center property-integral" name="integral" data-type="integral" datatype="n" nullmsg="请输入积分" errormsg="请输入正确的积分"><span class="help-block m-b-none"></span></td>'+
                '<td class="text-center form-group"><input type="text" class="form-control text-center property-originalprice" name="originalprice" data-type="originalprice" datatype="num" nullmsg="请输入原价" errormsg="请输入正确的原价"><span class="help-block m-b-none"></span></td>'+
                '<td class="text-center form-group"><input type="text" class="form-control text-center property-price" name="price" data-type="price" datatype="num" nullmsg="请输入现价" errormsg="请输入正确的现价"><span class="help-block m-b-none"></span></td>'+
                '<td class="text-center form-group"><input type="text" class="form-control text-center property-stock" name="stock" data-type="stock" datatype="n" nullmsg="请输入库存" errormsg="请输入正确的库存数量"><span class="help-block m-b-none"></span></td>'+
            '</tr>'+
        '{{/if}}'
    );
    var propertyFootTemp = template.compile(
        '{{if combination.length > 3}}'+
        '<tr>'+
            '<td colspan="{{itemlength + 4}}">'+
                '<div class="row">'+
                    '<div class="col-sm-3">'+
                        '<div class="input-group">'+
                            '<span class="input-group-addon">积分：</span>'+
                            '<input type="text" class="form-control">'+
                            '<span class="input-group-btn"><button type="button" class="btn btn-white batch-set-btn" data-type="integral" style="margin-bottom:0">批量设置</button></span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-sm-3">'+
                        '<div class="input-group">'+
                            '<span class="input-group-addon">原价：</span>'+
                            '<input type="text" class="form-control">'+
                            '<span class="input-group-btn"><button type="button" class="btn btn-white batch-set-btn" data-type="originalprice" style="margin-bottom:0">批量设置</button></span>'+
                        '</div>'+
                    '</div>'+
                    // '<div class="col-sm-3">'+
                    //     '<div class="input-group">'+
                    //         '<span class="input-group-addon">现价：</span>'+
                    //         '<input type="text" class="form-control">'+
                    //         '<span class="input-group-btn"><button type="button" class="btn btn-white batch-set-btn" data-type="price" style="margin-bottom:0">批量设置</button></span>'+
                    //     '</div>'+
                    // '</div>'+
                    '<div class="col-sm-3">'+
                        '<div class="input-group">'+
                            '<span class="input-group-addon">库存：</span>'+
                            '<input type="text" class="form-control">'+
                            '<span class="input-group-btn"><button type="button" class="btn btn-white batch-set-btn" data-type="stock" style="margin-bottom:0">批量设置</button></span>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</td>'+
        '</tr>'+
        '{{/if}}'
    );
    var propertyItemTemp = template.compile(
        '<div class="col-xs-2">'+
            '<label class="checkbox-inline i-checks"><input type="checkbox" name="propitem[{{propertyname}}][]" value="{{itemname}}" checked>{{itemname}}</label>'+
        '</div>'
    );
    //DOM渲染
    var propertyDom = function(){
        var temp = [];
        var usePropertyItemLen = 0;
        $('body .property').each(function(index, el) {
            var items = [];
            $(this).find('.row input').each(function(index, el) {
                if($(this).prop('checked')){
                    items.push($(this).val());
                }
            });
            temp.push({
                'name' : $(this).data().name,
                'items' : items
            });
            if(items.length != 0){
                usePropertyItemLen += 1;
            }
        });
        property['itemlength'] = usePropertyItemLen;
        property['names'] = temp;
        property['combination'] = propertyCombination();
        $('#propertyTable thead').html(propertyHeadTemp(property));
        $('#propertyTable tbody').html(propertyBodyTemp(property));
        $('#propertyTable tfoot').html(propertyFootTemp(property));
    };
    //排列组合
    var propertyCombination = function(){
        //初始化需要排列组合的数据
        var items = [];
        for(var i = 0; i < property['names'].length; i++){
            if(property['names'][i].items.length != 0){
                items.push(property['names'][i].items);
            }
        }
        items = combination(items);
        if(items.length == 0){
            items[0] = [];
        }
        return items[0];
    };
    //排列组合算法
    var combination = function(items){
        if(items.length > 1){
            var item1 = [];
            for(var i = 0; i < items[0].length; i++){
                for(var j = 0; j < items[1].length; j++){
                    if(typeof items[0][i] == 'object'){
                        var item2 = [];
                        var temp = objClone(items[0][i]['item']);
                        temp.push(items[1][j]);
                        item2['text'] = temp.join('_');
                        item2['item'] = temp;
                    }else{
                        var item2 = [];
                        var temp = [];
                        temp.push(items[0][i]);
                        temp.push(items[1][j]);
                        item2['text'] = temp.join('_');
                        item2['item'] = temp;
                    }
                    item1.push(item2);
                }
            }
            items.splice(0, 2, item1);
            if(items.length > 1){
                items = combination(items);
            }
        }else if(items.length == 1){
            var item1 = [];
            for(var i = 0; i < items[0].length; i++){
                var item2 = [];
                var temp = [];
                temp.push(items[0][i]);
                item2['text'] = items[0][i];
                item2['item'] = temp;
                item1.push(item2);
            }
            items.splice(0, 1, item1);
        }
        return items;
    };
    //对象克隆（深拷贝）
    var objClone = function(obj){
        var o;
        switch(typeof obj){
        case 'undefined': break;
        case 'string'   : o = obj + '';break;
        case 'number'   : o = obj - 0;break;
        case 'boolean'  : o = obj;break;
        case 'object'   :
            if(obj === null){
                o = null;
            }else{
                if(obj instanceof Array){
                    o = [];
                    for(var i = 0, len = obj.length; i < len; i++){
                        o.push(objClone(obj[i]));
                    }
                }else{
                    o = {};
                    for(var k in obj){
                        o[k] = objClone(obj[k]);
                    }
                }
            }
            break;
        default:
            o = obj;break;
        }
        return o;
    }
    //更新input数据
    var updatePropertyData = function(type, text, value){
        var flag = true;
        if(typeof property['data'] != 'undefined'){
            for(var i = 0; i < property['data'].length; i++){
                if(property['data'][i]['text'] == text){
                    flag = false;
                    switch(type){
                        case 'originalprice': property['data'][i]['originalprice'] = value; break;
                        case 'price': property['data'][i]['price'] = value; break;
                        case 'stock': property['data'][i]['stock'] = value; break;
                        case 'integral': property['data'][i]['integral'] = value; break;
                    }
                }
            }
        }
        if(flag){
            //新增数据
            var temp = [];
            temp['text'] = text;
            temp['originalprice'] = type == 'originalprice' ? value : '';
            temp['price'] = type == 'price' ? value : '';
            temp['stock'] = type == 'stock' ? value : '';
            temp['integral'] = type == 'integral' ? value : '';
            if(typeof property['data'] == 'undefined'){
                property['data'] = [];
            }
            property['data'].push(temp);
        }
    };
    //恢复input数据
    var recoveryPropertyData = function(){
        if(typeof property['data'] != 'undefined'){
            $('#propertyTable tbody tr').each(function(index, el) {
                var tr = $(this);
                var text = tr.children('input').val();
                for(var i = 0; i < property['data'].length; i++){
                    if(property['data'][i]['text'] == text){
                        tr.find('.property-originalprice').val(property['data'][i]['originalprice']);
                        tr.find('.property-price').val(property['data'][i]['price']);
                        tr.find('.property-stock').val(property['data'][i]['stock']);
                        tr.find('.property-integral').val(property['data'][i]['integral']);
                    }
                }
            });
        }
    };
    //操作事件
    $('#propertyChooseArea').on('ifChanged', '.property .i-checks input', function(event) {
        event.preventDefault();
        propertyDom();
        recoveryPropertyData();
    });
    $('#propertyTable').on('change', '.property-originalprice, .property-price, .property-stock', function(event) {
        event.preventDefault();
        var tr = $(this).parents('tr');
        var propertyText = $(tr[0]).children('input').val();
        updatePropertyData($(this).data().type, propertyText, $(this).val());
    });
    $('#propertyChooseArea').on('click', '.property .add-property-btn', function(event) {
        var inputDom = $($(this).parents('.input-group')[0]).children('input');
        var itemsDom = $($(this).parents('.input-group')[0]).parent().prev().find('input[type="checkbox"]');
        var property_text = inputDom.val();
        var add_property = property_text.replace(/\s/g,"");//过滤空格
        if(add_property != ''){
            var flag = true;
            $(itemsDom).each(function(index, el) {
                if($(this).val() == add_property){
                    flag = false;
                }
            });
            if(flag){
                var item = propertyItemTemp({
                    propertyname : inputDom.parents('.property').data().name,
                    itemname : add_property
                });
                $($(this).parents('.input-group')[0]).parent().prev().children('.row').append(item);
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
                inputDom.val('');
                propertyDom();
                recoveryPropertyData();
            }else{
                swal('已有相同属性，请勿重复添加', '', 'error');
            }
        }
    });
    $('#propertyTable').on('click', '.batch-set-btn', function(event) {
        event.preventDefault();
        var batchset = $(this).parent().siblings('input').val();
        if(batchset != ''){
            switch($(this).data().type){
                case 'originalprice':
                    $('#propertyTable tbody input[name^="originalprice"]').val(batchset);
                    break;
                case 'price':
                    $('#propertyTable tbody input[name^="price"]').val(batchset);
                    break;
                case 'stock':
                    $('#propertyTable tbody input[name^="stock"]').val(batchset);
                    break;
                case 'integral':
                    $('#propertyTable tbody input[name^="integral"]').val(batchset);
                    break;
            }
        }
    });
    //初始化规格选择列表
    $.ajax({
        url: 'goods.ajax.php',
        type: 'GET',
        dataType: 'json',
        data: {
            ac: 'getAllProperty',
            id: $('#editForm input[name="top_category_id"]').val()
        }
    }).done(function(cb) {
        $(cb).each(function(index, el) {
            var item = propertyChooseItemTemp(cb[index]);
            $('#propertyChooseArea').append(item);
        });
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
        //初始化SKU表格
        if($('#editForm input[name="id"]').val() != ''){
            $.ajax({
                url: 'goods.ajax.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    ac: 'getProperty',
                    id: $('#editForm input[name="id"]').val()
                }
            }).done(function(cb) {
                if(cb.names){
                    //检查是否有规格已变动，如果有变动则增加
                    for(var i = 0; i < cb.names.length; i++){
                        var isNew = false;
                        $('#propertyChooseArea .property').each(function(index, el) {
                            if(cb.names[i].name == $(this).data('name')){
                                isNew = true;
                            }
                        });
                        if(!isNew){
                            var temp = objClone(cb.names[i]);
                            temp.isdelete = true;
                            var item = propertyChooseItemTemp(temp);
                            $('#propertyChooseArea').append(item);
                        }
                    }
                    //根据返回数据复原DOM
                    for(var i = 0; i < cb.names.length; i++){
                        for(var j = 0; j < cb.names[i].item.length; j++){
                            $('#propertyChooseArea .property').each(function(index, el) {
                                if(cb.names[i].name == $(this).data('name')){
                                    if($('#propertyChooseArea .property:eq('+index+') .row input[value="'+cb.names[i].item[j]+'"]').length != 0){
                                        $('#propertyChooseArea .property:eq('+index+') .row input[value="'+cb.names[i].item[j]+'"]').iCheck('check');
                                    }else{
                                        //自定义属性
                                        var item = propertyItemTemp({
                                            propertyname : cb.names[i].name,
                                            itemname : cb.names[i].item[j]
                                        });
                                        $('#propertyChooseArea .property:eq('+index+') .row').append(item);
                                        $('.i-checks').iCheck({
                                            checkboxClass: 'icheckbox_square-green',
                                            radioClass: 'iradio_square-green',
                                        });
                                    }
                                }
                            });
                        }
                    }
                }
                propertyDom();
                //填充数据
                property['data'] = cb.data;
                recoveryPropertyData();
            });
        }else{
            propertyDom();
        }
    });
});
