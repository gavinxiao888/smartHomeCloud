<!-- create: 2015.10.27  应该就是这里之前 发生的！！！
后台管理主界面
 马学杰 -->
    
@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
    <script src="/plug-in/echarts-2.2.3/build/dist/echarts.js"></script>
 		 <div class="gzhlb_kpejh">
        <div class="gzhlb_mier"><img src="/site/images/admin_6.png">
            当前位置：
            <a href="/admin/index/show">首页</a>>
            <a href="/admin/1/device/show">设备管理</a>>
            <a href="/admin/gateway/list">网关列表</a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--网关列表开始-->
<div class="wglb_mowp">
    <div id="ring" class="wglb_piwe"><img src="/site/images/admin_13.png"></div>
    <div class="wglb_wjioe">
        <ul>
            <li>设备总数：<?php  if (!empty($status)) { ?>
               <?= $status['count']?>
              </li>
            <li style="margin:0 60px;">开机数：<?= $status['open']?></li>
            <li>关机数：<?= $status['close']?></li>
            <?php }?>
        </ul>
        <ul class="wglb_upwyu">
            <li class="wglb_pihw"></li>
            <li>关机比率</li>
            <li class="wglb_pihwe"></li>
            <li>开机比率</li>
        </ul>
    </div>
</div>
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
            var myChart = ec.init(document.getElementById('ring'));
           
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
                        radius :  ['50%', '70%'],
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
                        data:[<?php if (!empty($status)) { ?>
                            {value:<?= $status['open']?>, name:'开机'},
                            {value:<?= $status['close']?>, name:'关机'}
                            <?php } ?>
                        ]
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

<div class="clear"></div>
<div class="gzhlb_pwen">网关列表</div>
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
            <td>运行时间</td>
            <td>当前状态</td>
        </tr>
    </thead>
    <tbody>
         <?php if (!empty($list))  { ?>
            <?php foreach ($list as $gateway) { ?>
                <tr height="39">
                    <td width="310"><?php if (!empty($gateway->remark)) {echo $gateway->remark;} else {echo '网关';}?></td>
                    <td width="100"><?= $gateway->user_id == null ? '未认证用户' : $gateway->user_id?></td>
                    <td width="315"><?php if (!empty($gateway->update_time)) {
                                        echo $gateway->update_time;
                                    }else {
                                        echo $gateway->init_time;
                            }?></td>
                    <td><?php if (!empty($gateway->status)) {
                                if ($gateway->status == 1) {
                                    echo '开';
                                }
                        }else {
                            echo '关';
                            }?></td>
                </tr>
            <?php } ?>
        <?php } else {?>
            <h3>没有找到符合条件的信息。</h3>
        <?php }?>
    </tbody>
</table>
    </tbody>
</table>
<div style="text-align:center;">
    <?php if (!empty($list)){
                if (!empty($sub)) {
                       echo $list->appends(['sub' => $sub])->render();  
                }else {
                     echo $list->render(); 
                }
            }?>
</div>
<!--网关列表结束-->
    @endsection