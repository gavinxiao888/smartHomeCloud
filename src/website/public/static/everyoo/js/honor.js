//荣誉的效果
$(function(){
    $(".solution>ul>li:not('.more')").click(function()
    {
        $('.rongyu_1').css('display', 'none');
        $('.honor_info').css('display', 'none');
        $('.selected').removeClass('selected');
        $('.rongyu_1').eq($(this).attr('name')).css('display', 'block');
        $('.honor_info').eq($(this).attr('name')).css('display', 'block');
        $(this).addClass('selected');
    })
});

