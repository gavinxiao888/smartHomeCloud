(function(){
	//console.log("2");
	$.fn.hoverDelay = function(options){
		var defaults = {
			hoverDuring: 200,
			outDuring: 200,
			hoverEvent: function(){
				$.noop();
			},
			outEvent: function(){
				$.noop();
			}
		};
		var sets = $.extend(defaults,options || {});
		var hoverTimer, outTimer;
		return $(this).each(function(){
			$(this).hover(function(){
				clearTimeout(outTimer);
				hoverTimer = setTimeout(sets.hoverEvent, sets.hoverDuring);
			},function(){
				clearTimeout(hoverTimer);
				outTimer = setTimeout(sets.outEvent, sets.outDuring);
			});
		});
	}
	//console.log("26");
})(jQuery);

//dom加载完成执行
(function(){
var l_1 = $("#news_ul .l_1");
var l_2 = $("#news_ul .l_2");
var l_3 = $("#news_ul .l_3");

//鼠标放在了li上
//l_1的事件
$(l_1).hoverDelay({
    hoverEvent: function(){	
		if (l_1.css('width') == '160px')//l_1的宽度是不是190
		{		
			mover(l_2, l_3, l_1);
		}
    }
});
$(l_2).hoverDelay({
    hoverEvent: function(){	
		if (l_2.css('width') == '160px')//l_1的宽度是不是190
		{		
			mover(l_1, l_3, l_2);
		}
    }
});
$(l_3).hoverDelay({
    hoverEvent: function(){	
		if (l_3.css('width') == '160px')//l_1的宽度是不是190
		{		
			mover(l_2, l_1, l_3);
		}
    }
});

//以下是banner滚动效果
//console.log('62');
})(jQuery);
//@param smaller是要变小的ID，larger是要变大的ID
function mover(smaller, smaller1, larger)
{	
	smaller.animate({width:"160px"},500);
	smaller1.animate({width:"160px"},500);
	larger.animate({width:"680px"},500);
}	

