<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->

@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
    <script src="/echarts/build/dist/echarts.js"></script>
 		<div id="usermap"  style="width:900px; height:500px; float:left;"></div>
<script type="text/javascript">
  	 // 路径配置
        require.config({
            paths: {
                echarts: '/echarts/build/dist'
            }
        });
        // 使用
        require(
            [
                'echarts',
                'echarts/chart/map' // 使用柱状图就加载bar模块，按需加载
            ],
            function (ec) {
                // 基于准备好的dom，初始化echarts图表
                var myChart = ec.init(document.getElementById('usermap')); 
                
                option = {
				    tooltip : {
				        trigger: 'item',
				        formatter: '{b}'
				    },
				    series : [
				        {
				            name: '中国',
				            type: 'map',
				            mapType: 'china',
				            selectedMode : 'multiple',
				            itemStyle:{
				                normal:{label:{show:true}},
				                emphasis:{label:{show:true}}
				            },
				            data:[
				                {name:'广东',selected:true}
				            ]
				        }
				    ]
				};
				var ecConfig = require('echarts/config');
				myChart.on(ecConfig.EVENT.MAP_SELECTED, function (param){
				    var selected = param.selected;
				    var str = '当前选择： ';
				    for (var p in selected) {
				        if (selected[p]) {
				            str += p + ' ';
				        }
				    }
				    document.getElementById('wrong-message').innerHTML = str;
				})
                    
        
                // 为echarts对象加载数据 
                myChart.setOption(option); 
            }
        );
</script>
 	@stop