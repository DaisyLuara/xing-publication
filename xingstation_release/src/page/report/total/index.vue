<template>
  <div class="root">
    <div class="bottom" id="echart" ref="mychart" style="height: 900px; width: 100%"></div>
  </div>
</template>

<script>
import echarts from 'echarts/lib/echarts';
import echartsGl from 'echarts-gl';
export default {
  data () {
    return {
      option : {
        title: {
            // text: '漏斗图',
            // subtext: '纯属虚构'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{b} : {c}"
        },
        toolbox: {
          feature: {
            dataView: {readOnly: false},
            restore: {},
            saveAsImage: {}
          }
        },
        legend: {
          data: ['围观','互动','扫码拉新']
        },
        color:['#e6861c','#38449a', '#c0002b'],
        calculable: true,
        series: [
        {
          type:'funnel',
          left: '10%',
          top: 60,
          bottom: 60,
          width: '80%',
          min: 0,
          max: 100,
          minSize: '0%',
          maxSize: '100%',
          sort: 'descending',
          gap: 2,
          label: {
            fontSize: 16,
            fontWeight: '700',
            normal: {
              position: 'inside',
              formatter: "{b} {c}",
              textStyle: {
                fontSize: 16
              }
            },
            emphasis: {
              textStyle: {
                fontSize: 18
              }
            }
          },
          labelLine: {
            normal: {
              length: 10,
              lineStyle: {
                width: 1,
                type: 'solid'
              }
            }
          },
          itemStyle: {
            normal: {
              borderColor: '#fff',
              borderWidth: 1
            }
          },
          data: [
            {value: 60, name: '围观'},
            {value: 40, name: '互动'},
            {value: 20, name: '扫码拉新'},
          ]
        }
      ],
    },
      myChart: null,
    }
  },
  mounted() {
    var dom = document.getElementById("echart");
    var myChart = echarts.init(dom);
    if (this.option && typeof this.option === "object") {
      myChart.setOption(this.option, true);
    }
  },
  components: {
    // "el-date-picker": DatePicker,
    // "el-row": Row,
    // "el-col": Col
  }
}
</script>

<style lang="less" scoped>
  .root {
    padding: 10px;
    font-size: 14px;
    color: #5E6D82;
   .bottom {
      min-height: 800px;
      width: 100%;
      background-color: #fff;
    }
  }
</style>
