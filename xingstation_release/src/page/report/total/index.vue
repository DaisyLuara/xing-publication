<template>
  <div class="root">
    <el-row :gutter="20">
      <el-col :span="12">
        <el-card shadow="always">
          <highcharts :options="pieOptions" class="highchart" ref="piechar"></highcharts>
        </el-card>
      </el-col>
      <el-col :span="12">
        <el-card shadow="always">
          <highcharts :options="funnelOptions" class="highchart" ref="funnelchar"></highcharts>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
// import echarts from 'echarts/lib/echarts';
// import echartsGl from 'echarts-gl';
import { Tabs, TabPane, Button, Row, Col, Card, DatePicker} from 'element-ui'

import Highcharts from 'highcharts';
import loadExporting from 'highcharts/modules/exporting';
import loadFunnel from 'highcharts/modules/funnel';
loadExporting(Highcharts);
loadFunnel(Highcharts);

export default {
  data () {
    return {
    //   option : {
    //     title: {
    //         // text: '漏斗图',
    //         // subtext: '纯属虚构'
    //     },
    //     tooltip: {
    //         trigger: 'item',
    //         formatter: "{b} : {c}"
    //     },
    //     toolbox: {
    //       feature: {
    //         dataView: {readOnly: false},
    //         restore: {},
    //         saveAsImage: {}
    //       }
    //     },
    //     legend: {
    //       data: ['围观','互动','扫码拉新']
    //     },
    //     color:['#e6861c','#38449a', '#c0002b'],
    //     calculable: true,
    //     series: [
    //     {
    //       type:'funnel',
    //       left: '10%',
    //       top: 60,
    //       bottom: 60,
    //       width: '80%',
    //       min: 0,
    //       max: 100,
    //       minSize: '0%',
    //       maxSize: '100%',
    //       sort: 'descending',
    //       gap: 2,
    //       label: {
    //         fontSize: 16,
    //         fontWeight: '700',
    //         normal: {
    //           position: 'inside',
    //           // formatter: "{b} {c} {d}%",
    //           formatter: function(params) {
    //             console.log(params.name)
    //             return params.name + ' ' + params.value
    //           },
    //           textStyle: {
    //             fontSize: 16
    //           }
    //         },
    //         emphasis: {
    //           textStyle: {
    //             fontSize: 18
    //           }
    //         }
    //       },
    //       labelLine: {
    //         normal: {
    //           length: 10,
    //           lineStyle: {
    //             width: 1,
    //             type: 'solid'
    //           }
    //         }
    //       },
    //       itemStyle: {
    //         normal: {
    //           borderColor: '#fff',
    //           borderWidth: 1
    //         }
    //       },
    //       data: [
    //         {value: 2011386, name: '围观总数'},
    //         {value: 1090426, name: '互动总数'},
    //         {value: 263733, name: '扫码拉新总数'},
    //       ]
    //     }
    //   ],
    // },
    //   myChart: null,
  pieOptions: {
    title: {
      text: '屏幕总数 381 台'
    },
    xAxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    plotOptions: {
      pie: {
        // innerSize: 100,
        innerSize: '20%',
        allowPointSelect: true,
        cursor: 'pointer',
        dataLabels: {
          enabled: true,
          format: '{point.name} {point.y} '
        },
        showInLegend: true
      }
    },
    colors: ['#e6861c', '#38449a','#c0002b'],
    legend: {
      enabled: false
    },
    credits: {
      enabled: false
    },
    series: [{
      type: 'pie',
      allowPointSelect: true,
      keys: ['name', 'y', 'selected', 'sliced'],
      data: [
        // ['屏幕总数', 381, true, true],
        ['总人数', 2011386, , true, true],
        ['人脸总张数', 4778197, false],
        ['曝光人数', 20215890,false],
      ],
      showInLegend: true
    }]
  },
  funnelOptions: {
    chart: {
      type: 'funnel'
    },
    title: {
      text: '总互动时间 4453 分钟'
    },
    colors: ['#e6861c', '#38449a','#c0002b'],
    plotOptions: {
      series: {
        dataLabels: {
          enabled: true,
          format: '<b>{point.name} {point.y:,.0f}</b> ',
          color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
          softConnector: true
        },
        center: ['40%', '50%'],
        neckWidth: '0%',
        neckHeight: '0%',
        width: '80%'
      }
    },
    legend: {
      enabled: false
    },
    credits: {
      enabled: false
    },
    series: [{
      name: 'Unique users',
      data: [
        ['围观总数', 9139],
        ['互动总数', 5463],
        ['扫码拉新总数', 2434],
      ]
    }]
    }
    }
  },
  mounted() {
  },
  components: {
    ElRow: Row,
    ElCol: Col,
    ElCard: Card,
    ElDatePicker: DatePicker
  },
}
</script>

<style lang="less" scoped>
  .root {
    padding: 10px;
    font-size: 14px;
    color: #5E6D82;
    background-color: #fff;
   .bottom {
      min-height: 800px;
      width: 100%;
      background-color: #fff;
    }
  }
</style>
