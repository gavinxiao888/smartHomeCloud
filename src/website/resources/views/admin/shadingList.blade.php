<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->

@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    <!-- @ sect ion ('content')min.pjax':'admin.default')-->
    @section('content')
    <style>
    .shading{cursor: pointer;}
    </style>
    <script src="/plug-in/echarts-2.2.3/build/dist/echarts.js"></script>
 	  <div class="gzhlb_kpejh">
        <div class="gzhlb_mier"><img src="/site/images/admin_6.png">
            当前位置：
            <a href="/admin/index/show">首页</a>>
            <a href="/admin/1/device/show">设备管理</a>>
            <a href="/admin/shading/list"><?php echo env('ADMIN_NAV_DEVICE_NAME');?></a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--网关列表开始-->
<div class="wglb_mowp">
    <div id="shading" class="wglb_piwe"></div>
    <div class="wglb_wjioe">
        <ul>
            <li>设备总数：<?php if (!empty($count)) {echo $count['all'];}?></li>
            <li style="margin:0 60px;">开机数：<?php if (!empty($count)) {echo $count['open'];}?></li>
            <li>关机数：<?php if (!empty($count)) {echo $count['close'];}?></li>
        </ul>
        <ul class="wglb_upwyu">
            <li class="wglb_pihw"></li>
            <li>开机比率</li>
            <li class="wglb_pihwe"></li>
            <li>关机比率</li>
        </ul>
    </div>
</div>

<div class="clear"></div>
<div class="gzhlb_pwen"><?php echo env('ADMIN_NAV_DEVICE_NAME');?>列表</div>
<div class="gzhlb_kpwq">
    <input style="width:216px;height:35px;border:none;float:left;">
    <a href=""><div class="gzhlb_bmoe"><img src="/site/images/admin_9.png"></div></a>
</div>
<div class="clear"></div>
<table class="data_list" width="960" cellspacing="0" style="font-size:16px;color:#616161;text-align:center;">
    <thead class="gzhlb_ejrp">
        <tr>
            <td>设备名称</td>
            <td>用户</td>
            <td>网关</td>
            <td>当前开窗比例</td>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($list))  { ?>
            <?php for($i = 0, $y = count($list); $i < $y; $i++) { ?>
                <tr height="39">
                    <td width="310"><span class="shading" data-id="<?= $list[$i]['id']?>"><?= $list[$i]['nickname']?></span></td>
                    <td width="100"><?= $list[$i]['user']?></td>
                    <td width="315"><?= $list[$i]['gateway']?></td>
                    <td><?= $list[$i]['status']?>%</td>
                </tr>
            <?php } ?>
        <?php } else {?>
            <h3>没有找到符合条件的遮阳机。</h3>
        <?php }?>
    </tbody>
</table>
<script type="text/javascript">

        $('.shading').click(function(event) {
            // console.log('点击了该按钮');
            var id = $(this).attr('data-id');
            console.log(id);
               layer.open({
                    type: 2,
                    title: '控制面板',
                    shade: [0.8, '#393D49'],
                    area: ['440px', '350px'],
                    shift: 2,
                    content: ['/admin/check/show?id='+id, 'no'], //iframe的url，no代表不显示滚动条
                    end: function(){ //此处用于演示

                    }
               })
        });
</script>
<script type="text/javascript">
    //这里是加载的动画spin
        var loadingTicket;
        var effectIndex = -1;
        var effect = ['spin' , 'bar' , 'ring' , 'whirling' , 'dynamicLine' , 'bubble'];
        function refresh(isBtnRefresh){
            //(new function (editor.doc.getValue()))();
            if (myChart && myChart.dispose) {
                myChart.dispose();
            }
            myChart = echarts.init(domMain, curTheme);
            (new Function (editor.doc.getValue()))();
            domMessage.innerHTML = '';
        }
    // var require = ;
    require.config({
        paths: {
            echarts: '/plug-in/echarts-2.2.3/build/dist'
        }
    });

    // 使用
    require(
        [
            'echarts',
            'echarts/chart/pie',
            'echarts/chart/funnel'
        ],
        function (ec) {
            // var register = Number($('#register').val());
            // var online = Number($('#online').val());
            // var perRegister = register/(register+online);
            // var perOnline = online/(register+online);
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('shading'));
           
            effectIndex = ++effectIndex % effect.length;
            myChart.showLoading({
                text : effect[effectIndex],
                effect : effect[effectIndex],
                textStyle : {
                    fontSize : 20
                }
            });
            option = {
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                // legend: {
                //     orient : 'vertical',
                //     x : 'left',
                //     data:['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
                // },
                toolbox: {
                    show : false,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {
                            show: true, 
                            type: ['pie', 'funnel'],
                            option: {
                                funnel: {
                                    x: '25%',
                                    width: '50%',
                                    funnelAlign: 'center',
                                    max: 1548
                                }
                            }
                        },
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                calculable : true,
                series : [
                    {
                        name:'开关机比率',
                        type:'pie',
                        radius : ['50%', '70%'],
                        itemStyle : {
                            normal : {
                                label : {
                                    show : false
                                },
                                labelLine : {
                                    show : false
                                }
                            },
                            emphasis : {
                                label : {
                                    show : true,
                                    position : 'center',
                                    textStyle : {
                                        fontSize : '30',
                                        fontWeight : 'bold'
                                    }
                                }
                            }
                        },
                        data:
                            <?php if (!empty($count)) {
                                echo "[{value:".$count['open'].", name:'开机'},{value:".$count['close'].", name:'关机'}]";
                            } else {
                                echo "[{value:0, name:'开机'},{value:0, name:'关机'}]";
                            }?>
                    }
                ]
            };
               clearTimeout(loadingTicket);
            loadingTicket = setTimeout(function (){
                myChart.hideLoading();
                myChart.setOption(option);
            },0);
        })
                    
</script>
<!--网关列表结束-->
    @stop