<template>
  <div class="home-wrap">
    <div class="search-wrap">
      <el-date-picker
      v-model="dataValue"
      type="daterange"
      align="right"
      unlink-panels
      range-separator="至"
      start-placeholder="开始日期"
      end-placeholder="结束日期"
      :default-value="dataValue"
      :clearable="false"
      :picker-options="pickerOptions2"
      @change="test">
    </el-date-picker>
    </div>
    <div class="tendency-wrap">
      <!-- <el-row :gutter="20">
        <el-col :span="12"> -->
          <el-card shadow="always">
            <highcharts :options="pointOptions" class="highchart" ref="pointChar"></highcharts>
          </el-card>
        <!-- </el-col>
        <el-col :span="12"> -->
          <!-- <el-card shadow="always">
            player
          </el-card> -->
        <!-- </el-col>
      </el-row> -->
    </div>
    <div class="ranking-wrap">
      <el-row :gutter="20">
        <el-col :span="12">
          <el-card shadow="always">
            <highcharts :options="pointTenOptions" class="highchart" ref="pointTenChar"></highcharts>
          </el-card>
        </el-col>
        <el-col :span="12">
          <el-card shadow="always">
            <highcharts :options="projectTenOptions" class="highchart" ref="projectTenChar"></highcharts>
          </el-card>
        </el-col>
      </el-row>
    </div>
    <div class="age-gender-wrap">
      <el-row :gutter="20">
        <el-col :span="12">
          <el-card shadow="always">
              <highcharts :options="sexOptions" class="highchart" ref="sexPie"></highcharts>
          </el-card>
        </el-col>
        <el-col :span="12">
          <el-card shadow="always">
            <highcharts :options="ageOptions" class="highchart" ref="agePie" ></highcharts>
          </el-card>
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
import { Tabs, TabPane, Button, Row, Col, Card, DatePicker} from 'element-ui'
import Highcharts from 'highcharts';

export default {
  components: {
    ElRow: Row,
    ElCol: Col,
    ElCard: Card,
    ElDatePicker: DatePicker
  },
  computed: {
  
  },
  data() {
    return {
      dataValue: [new Date().getTime(), new Date().getTime()],
      pickerOptions2: {
        shortcuts: [{
          text: '今天',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              picker.$emit('pick', [start, end]);
            }
          }, {
          text: '昨天',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24);
              picker.$emit('pick', [start, end]);
            }
          },{
          text: '过去7天',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '过去30天',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          }
        },]
      },
      pointOptions : {
        title: {
          text: '点位趋势',
          style: {'fontSize': '20px'},
          align: 'left'
        },
        xAxis: {
          type: 'datetime'
        },
        yAxis: [{
          title: {
            text: null,
          },
          // floor: 0,
          // tickAmount: 5
        }],
        legend: {
          enabled: false
        },
        credits: {
          enabled: false
        },
        tooltip: {
          shared: true,
          formatter: function () {
            var s = ''
            let percent = 0
            if(this.points[1]) {
              percent = new Number(((this.points[1].y - this.points[0].y) /  this.points[0].y) * 100).toFixed(2)
              s = '<b>' + percent +'%' + '</b>';
            }
            for(let i=0;i<this.points.length; i++) {
               s += '<br/>' + this.points[i].series.name + ': ' +
                    this.y;
            }
            return s;
        },
        },
        plotOptions: {
          area: {
            marker: {
              enabled: false,
              symbol: 'circle',
              radius: 2,
              states: {
                hover: {
                  enabled: true
                }
              }
            }
          }
        },
        series: [{
          name: '昨天',
          data: [
            [1246406400000, 31.5],
            [1246492800000, 22.1],
            [1246579200000, 23],
            [1246665600000, 23.8],
            [1246752000000, 21.4],
            [1246838400000, 21.3],
            [1246924800000, 18.3],
            [1247011200000, 15.4],
            [1247097600000, 16.4],
            [1247184000000, 17.7],
            [1247270400000, 43.5],
            [1247356800000, 17.6],
            [1247443200000, 17.7],
            [1247529600000, 16.8],
            [1247616000000, 17.7],
            [1247702400000, 16.3],
            [1247788800000, 17.8],
            [1247875200000, 18.1],
            [1247961600000, 17.2],
            [1248048000000, 14.4],
            [1248134400000, 13.7],
            [1248220800000, 15.7],
            [1248307200000, 14.6],
            [1248393600000, 15.3],
            [1248480000000, 15.3],
            [1248566400000, 15.8],
            [1248652800000, 15.2],
            [1248739200000, 14.8],
            [1248825600000, 14.4],
            [1248912000000, 15],
            [1248998400000, 13.6]
          ],
          zIndex: 3,
          color: '#cccc',
          marker: {
            fillColor: 'white',
            lineWidth: 1,
            enabled: false,
            // lineColor: '#cccccc'
          }
        }, {
          name: '今天',
          data: [
            [1246406400000,  27.7],
            [1246492800000,  27.8],
            [1246579200000,  29.6],
            [1246665600000,  30.7],
            [1246752000000,  25.0],
            [1246838400000,  25.7],
            [1246924800000,  24.8],
            [1247011200000,  21.4],
            [1247097600000,  23.8],
            [1247184000000,  21.8],
            [1247270400000,  23.7],
            [1247356800000,  23.3],
            [1247443200000,  23.7],
            [1247529600000,  20.7],
            [1247616000000,  22.4],
            [1247702400000,  19.6],
            [1247788800000,  22.6],
            [1247875200000,  25.0],
            [1247961600000,  21.6],
            [1248048000000,  17.1],
            [1248134400000,  15.5],
          ],
          type: 'area',
          lineWidth: 1,
          color: '#e09f91',
          fillOpacity: 0.5,
          zIndex: 0,
          marker: {
            enabled: false
          }
        }]
        // series: [{
        //   color: "#8bbc21",
        //   name:"数量"
        // }]
      },
      pointTenOptions: {
        chart: {
          type: 'bar'
        },
        title: {
          text: '点位前10名',
          align: 'left',
          style: {'fontSize': '20px'}
        },
        subtitle: {
          text: '2018/5/16'
        },
        xAxis: {
          categories: ['Africa', 'America', 'Asia', 'Europe', 'Oceania','Africa', 'America', 'Asia', 'Europe', 'Oceania'],
          title: {
            text: null
          }
        },
        yAxis: {
          min: 0,
          title: null,
          labels: {
            overflow: 'justify'
          }
        },
        tooltip: {
          // valueSuffix: ' millions'
        },
        plotOptions: {
          bar: {
            dataLabels: {
              enabled: false
            }
          }
        },
        legend: false,
        credits: {
          enabled: false
        },
        series: [{
          color: '#abce5b',
          name: 'Year 1800',
          data: [107, 31, 635, 203, 2,107, 31, 635, 203, 2]
        }]
      },
      projectTenOptions: {
        chart: {
          type: 'bar'
        },
        title: {
          text: '节目前10名',
          align: 'left',
          style: {'fontSize': '20px'}
        },
        subtitle: {
          text: '2018/5/16'
        },
        xAxis: {
          categories: ['Africa', 'America', 'Asia', 'Europe', 'Oceania','Africa', 'America', 'Asia', 'Europe', 'Oceania'],
          title: {
            text: null
          }
        },
        yAxis: {
          min: 0,
          title: null,
          labels: {
            overflow: 'justify'
          }
        },
        tooltip: {
          // valueSuffix: ' millions'
        },
        plotOptions: {
          bar: {
            dataLabels: {
              enabled: false
            }
          }
        },
        legend: false,
        credits: {
          enabled: false
        },
        series: [{
          color: '#abce5b',
          name: 'Year 1800',
          data: [107, 31, 635, 203, 2,107, 31, 635, 203, 2]
        }]
      },
      sexOptions: {
        chart:{
          animation: {
            duration: 1000
          },
          type: 'pie',
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
        },
        tooltip: {
          headerFormat: '{性别访问数}<br>',
          pointFormat: '{point.name}: <b>{point.y} 占比{point.percentage:.1f}%</b>'
        },
        plotOptions: {
          pie: {
            innerSize: 100,
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: true,
              format: '{point.name} {point.y} 占比{point.percentage:.1f}% '
            },
            showInLegend: true
          }
        },
        title: {
          text: '性别趋势',
          style: {'fontSize': '20px'},
          align: 'left'
        },
        legend: {
          align: 'right',
          verticalAlign: 'middle',
          layout: 'vertical',
          symbolHeight: 12,
          symbolWidth: 20,
          symbolRadius: 2,
          squareSymbol: false
        },
        credits: {
          enabled: false
        },
        series: [{
          type: 'pie',
          name: '性别访问数',
          data:[{
            color: '#ffd259',
            name: '男',
            y: 61,
            sliced: true,
            selected: true
          }, {
            color: '#5eb6c8',
            name: '女',
            y: 15
          },]
        }]
      },
      ageOptions: {
        chart:{
          type: 'column',
          animation: {
            duration: 1000
          },
        },
        plotOptions: {
          series: {
            animation: {
              duration: 2000,
            }
          }
        },
        title: {
          text: '年龄趋势',
          style: {'fontSize': '20px'},
          align: 'left'
        },
        xAxis: {
          type: 'category'
        },
        yAxis: [{
          title: {
            text: '年龄统计',
          },
          tickAmount: 5
        }],
        legend: {
          enabled: false
        },
        credits: {
          enabled: false
        },
        series: [{
          // dashStyle: 'shortDash',
          color: "#7cb5ec",
          name:"年龄统计",
          data: [39.9, 41.5, 106.4, 529.2, 154.0, 106.0, 195.6, 108.5, 216.4, 194.1, 95.6, 54.4],
        }]
      }
    }
  },
  mounted() {
    
  },
  methods: {
    test() {
      console.log(this.dataValue)
    let ageChart = this.$refs.agePie.chart;
    let sexPie = this.$refs.sexPie.chart;
    console.log(ageChart.series[0])
    // ageChart.series[0].data.update(399, 415, 1064, 5292, 154, 1060, 1956, 1085, 2164, 1941, 956, 544);
    ageChart.update({
      chart: {
        inverted: false,
        polar: false,
        animation: {
            duration: 1000
          },
      },
      series: [{
          data: [399, 415, 1064, 5292, 154, 1060, 1956, 1085, 2164, 1941, 956, 544],
        }],
    });
    ageChart.redraw();
    sexPie.update({
      chart: {
        inverted: false,
        polar: false,
        animation: {
            duration: 1000
          },
      },
    series: [{
      data: [378, 789],
      }],
    });
    }
  },
}
</script>
<style lang="less" scoped>
  .home-wrap {
    background-color: #fff;
    padding: 15px;
    .search-wrap{
      text-align: right;
      margin-top: 5px;
      font-size: 16px;
      align-items: center;
      margin-bottom: 10px;
    }
    .tendency-wrap, .ranking-wrap, .age-gender-wrap{
      margin-bottom: 15px;
    }
    .age-gender-wrap{

    }
  }
</style>
