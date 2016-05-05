
$(function() {
      $(".gzhlb_pjno").hide();
      $(".gzhlb_fjew").hover(function(){
            $(".gzhlb_pjno").stop().slideDown(400)
            });
      $(".index_nav").click(function(event) {
            $(".index_nav").removeClass("click_open_div");
            $(this).addClass("click_open_div");
      });
            // ,function(){$(".gzhlb_pjno").stop().slideUp(400)});
       $(document).ready(function(){ 
            var color="#f5f5f5"
            $(".data_list tr:odd td").css("background-color",color);  //改变偶数行背景色
            /* 把背景色保存到属性中 */
            $(".data_list tr:odd").attr("bg",color);
            $(".data_list tr:even").attr("bg","#eceaea");
      })
})
	