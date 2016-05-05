//dom加载完成执行
$(function(){
var l_1 = $("#news_ul .l_1");
var l_2 = $("#news_ul .l_2");
var l_3 = $("#news_ul .l_3");
//鼠标放在了li上
//l_1的事件
li_wd(l_1,l_2,l_3);
li_wd(l_2,l_1,l_3);
li_wd(l_3,l_2,l_3);

});
//@param smaller是要变小的ID，larger是要变大的ID
function mover(smaller, larger)
{	
	// if (smaller.width != 839)
	// {
		// smaller.animate({width:"190px"},300);
	// }	
	// else

	smaller.animate({width:"190px"},600);
	larger.animate({width:"839px"},600);
	
	// window.setInterval(function(){ 
	// wd_smaller(smaller);
	// }, 1); 
	// window.setInterval(function(){ 
	// wd_larger(larger); 
	// }, 1); 
}
//@power 适应一个DIV宽度增加的方法
function wd_larger(larger)
{
	larger.css('width',Number(larger.css('width').substring(0,3))+1 +'px');
	if (larger.css('width') == '839px')
	{
		window.clearInterval(wd_larger); 
	}	
}
//@power 适应一个DIV宽度减少的方法
function wd_smaller(smaller)
{
	smaller.css('width',Number(smaller.css('width').substring(0,3))-1+'px');
	if (smaller.width() == 190)
	{
		window.clearInterval(wd_smaller); 
	}

}
//@power监听l_1的鼠标事件,判断l_2, l_3的信息
function li_wd(l_1, l_2, l_3)
{
$(l_1).hoverDelay({
    hoverEvent: function(){	
		// alert(typeof(l_1.css('width')));
		if (l_1.css('width') == '190px')//l_1的宽度是不是190
		{		
			if (l_2.css('width') == '839px')//当l_1的宽度是190的时候，l_2的宽度是190的时候
			{
				mover(l_3, l_1);
			}
			else//当l_1的宽度是190的时候，l_2的宽度是839的时候
			{
				mover(l_2, l_1);
			}
		}
    }
});
}