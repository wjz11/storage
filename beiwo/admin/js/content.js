var $parentNode = window.parent.document;

function $childNode(name) {
    return window.frames[name]
}

// tooltips
$('.tooltip-demo').tooltip({
    selector: "[data-toggle=tooltip]",
    container: "body"
});

// 使用animation.css修改Bootstrap Modal
$('.modal').appendTo("body");

$("[data-toggle=popover]").popover();

//折叠ibox
$('.collapse-link').click(function () {
    var ibox = $(this).closest('div.ibox');
    var button = $(this).find('i');
    var content = ibox.find('div.ibox-content');
    content.slideToggle(200);
    button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
    ibox.toggleClass('').toggleClass('border-bottom');
    setTimeout(function () {
        ibox.resize();
        ibox.find('[id^=map-]').resize();
    }, 50);
});

//关闭ibox
$('.close-link').click(function () {
    var content = $(this).closest('div.ibox');
    content.remove();
});

//判断当前页面是否在iframe中
if (top == this) {
    var gohome = '<div class="gohome"><a class="animated bounceInUp" href="../../index.php" title="返回首页"><i class="fa fa-home"></i></a></div>';　
    $('body').append(gohome);
}

//animation.css
function animationHover(element, animation) {
    element = $(element);
    element.hover(
        function () {
            element.addClass('animated ' + animation);
        },
        function () {
            //动画完成之前移除class
            window.setTimeout(function () {
                element.removeClass('animated ' + animation);
            }, 2000);
        });
}

//拖动面板
function WinMove() {
    var element = "[class*=col]";
    var handle = ".ibox-title";
    var connect = "[class*=col]";
    $(element).sortable({
            handle: handle,
            connectWith: connect,
            tolerance: 'pointer',
            forcePlaceholderSize: true,
            opacity: 0.8,
        })
        .disableSelection();
};

$(function(){
    $('.searchplusswitch').click(function(event) {
        if($('.searchplus').is(':visible')){
            $('.searchplus').slideUp();
            $(this).find('span').text('展开筛选').next('i').removeClass('fa-angle-up').addClass('fa-angle-down');
        }else{
            $('.searchplus').slideDown();
            $(this).find('span').text('收起筛选').next('i').removeClass('fa-angle-down').addClass('fa-angle-up');
        }
    });
});
function multifiltersInit(callback){
    $('.multifilters a').click(function(event) {
        $(this).addClass('selected').siblings('a').removeClass('selected');
        $(this).parent().siblings('input').val($(this).data('value'));
        var $row = $(this).parent().parent();
        $('.multifilters .quickselect_result button').each(function(index, el) {
            if($(this).data('index') == $row.index()){
                $(this).remove();
            }
        });
        if($(this).data('value') != ''){
            $('.multifilters .quickselect_result').append('<button type="button" class="btn btn-xs btn-default m-r-sm" data-index="'+$row.index()+'">'+$row.find('label').text()+$(this).text()+' <i class="fa fa-times-circle"></i></button>');
        }
        if($('.multifilters .quickselect_result button').length == 0){
            $('.multifilters .quickselect_result small').show();
        }else{
            $('.multifilters .quickselect_result small').hide();
        }
        callback && callback();
    });
    $('.multifilters .quickselect_result').on('click', 'button', function(event) {
        $('.multifilters > .row:eq('+$(this).data('index')+') a:first-child').click();
    });
};