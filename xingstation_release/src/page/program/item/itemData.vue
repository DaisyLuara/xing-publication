<template>
  <div class="root">
    <div class="item-data-wrap">
      <div class="item-content-wrap">
        <div class="show-item-wrap" >
          <el-row :gutter="20">
            <el-col :span="8">
              <el-card class="item-info-wrap" id="test">
                <div slot="header" class="clearfix">
                  <span class="item-header">节目信息</span>
                </div>
                <div class="item-content">
                  <el-row :gutter="10">
                    <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8"><div class="item-info-name">节目名称</div></el-col>
                    <el-col :xs="16" :sm="16" :md="16" :lg="16" :xl="16"><div class="item-info">苏宁易购418电器购物节</div></el-col>
                  </el-row>
                  <el-row :gutter="10">
                    <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8"><div class="item-info-name">所属客户</div></el-col>
                    <el-col :xs="16" :sm="16" :md="16" :lg="16" :xl="16"><div class="item-info">苏宁易购</div></el-col>
                  </el-row>
                  <el-row :gutter="10">
                    <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8"><div class="item-info-name">投放日期</div></el-col>
                    <el-col :xs="16" :sm="16" :md="16" :lg="16" :xl="16"><div class="item-info">2018/01/01 - 2018/02/04</div></el-col>
                  </el-row>
                  <el-row :gutter="10">
                    <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8"><div class="item-info-name">发布时间</div></el-col>
                    <el-col :xs="16" :sm="16" :md="16" :lg="16" :xl="16"><div class="item-info">10:00 - 20:00</div></el-col>
                  </el-row>
                  <el-row :gutter="10">
                    <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8"><div class="item-info-name">发布位置</div></el-col>
                    <el-col :xs="16" :sm="16" :md="16" :lg="16" :xl="16"><div class="item-info">八佰伴 1F(001),2F(002),3F(003)</div></el-col>
                  </el-row>
                </div>
              </el-card>
            </el-col>
            <el-col :span="16">
              <el-card class="data-wrap">
                <div slot="header" class="clearfix">
                  <span class="data-header">整体情况 <span class="header-example"> (以下数据有近三十分钟的统计延迟)</span></span>
                  <el-date-picker
                    v-model="value"
                    type="daterange"
                    range-separator="至"
                    start-placeholder="开始日期"
                    end-placeholder="结束日期" class="date-seach">
                  </el-date-picker>
                </div>
                <div class="data-content">
                  <el-row :gutter="25">
                    <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8">
                      <div class="data-item-wrap">
                        <el-card>
                          <div slot="header" class="clearfix header">
                            <span>围观数</span>
                          </div>
                          <div class="header-content">
                            200,000 (人)
                          </div>
                        </el-card>
                      </div>
                    </el-col>
                    <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8">
                      <div class="data-item-wrap">
                        <el-card>
                          <div slot="header" class="clearfix header">
                            <span>体验数</span>
                          </div>
                          <div class="header-content">
                            89,000 (人)
                          </div>
                        </el-card>
                      </div>
                    </el-col>
                    <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8">
                      <div class="data-item-wrap">
                        <el-card>
                          <div slot="header" class="clearfix header">
                            <span>扫码率</span>
                          </div>
                          <div class="header-content">
                            58.56 %
                          </div>
                        </el-card>
                      </div>
                    </el-col>
                  </el-row>
                  <el-row :gutter="10">
                    <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12">
                      <div class="age-wrap">
                        <highcharts :options="ageOptions"></highcharts>
                      </div>
                    </el-col>
                    <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12">
                      <div class="sex-wrap">
                        <highcharts :options="sexOptions"></highcharts>
                      </div>
                    </el-col>
                  </el-row>
                </div>
              </el-card>
            </el-col>
          </el-row>
        </div>
        <div class="line-data-wrap">
          <el-card class="line-wrap">
            <div class="search-wrap">
              <div>
                <label>时间范围：</label>
                <el-date-picker
                  v-model="dataValue"
                  type="daterange"
                  range-separator="至"
                  start-placeholder="开始日期"
                  end-placeholder="结束日期">
                </el-date-picker>
              </div>
              <div>
                <el-select v-model="value" placeholder="请选择">
                  <el-option
                    v-for="item in options"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                  </el-option>
                </el-select>
                <el-button type="primary">下载报表</el-button>
              </div>
            </div>
            <div>
              <highcharts :options="lineOptions"></highcharts>
            </div>
          </el-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Button, Input, Row, Col, Card, DatePicker,Select, Option} from 'element-ui'

export default {
  data () {
    return {
      value: '',
      dataValue: '',
      options: [{
        value: '选项1',
        label: '围观数'
      }, {
        value: '选项2',
        label: '体验数'
      }, {
        value: '选项3',
        label: '扫码率'
      }],
      ageOptions : {
        chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false
        },
        title: {
          text: '年龄分布'
        },
        tooltip: {
          headerFormat: '{series.name}<br>',
          pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: false
            },
            showInLegend: true
          }
        },
        credits: {
          enabled: false
        },
        series: [{
          type: 'pie',
          name: '年龄占比',
          data: [
            {name: '24-30岁', y: 45.0, color: '#8085e9'},
            {name:'31-40', y: 26.8, color: '#2b908f'},
            {
              name: '41-50',
              y: 12.8,
              sliced: true,
              selected: true,
              color:'#f7a35c'
            }
          ]
        }]
      },
      sexOptions: {
        chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false
        },
        title: {
          text: '性别分布'
        },
        tooltip: {
          headerFormat: '{series.name}<br>',
          pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: false
            },
            showInLegend: true
          }
        },
        credits: {
          enabled: false
        },
        series: [{
          type: 'pie',
          name: '性别占比',
          data: [
            {name:'女',y: 45.0, color: '#7cb5ec'},
            {name:'未知',y: 32.0, color: '#434348'},
            {
              name: '男',
              y: 18.8,
              sliced: true,
              selected: true,
              color: '#90ed7d'
            }
          ]
        }]
      },
      lineOptions: {
        chart: {
          type: 'line'
        },
        title: {
          text: ''
        },
        xAxis: {
          categories: [
            '11月30日', '12月1日', '12月3日'
          ]
        },
        yAxis: [{
          min: 0,
          title: {
            text: ''
          }
        }, {
          title: {
            text: '点击率(%)'
          },
          opposite: true
        }],
        credits: {
          enabled: false
        },
        legend: {
          enabled: true
        },
        tooltip: {
          shared: true
        },
        series: [{
          name: '曝光量',
          color: 'rgba(165,170,217,1)',
          data: [150, 73, 20],
        }, {
          name: '点击率',
          color: 'rgba(248,161,63,1)',
          data: [25, 90, 40],
          yAxis: 1
        }]
      }
    }
  },
  mounted() {
    // let height = document.getElementById('test').offsetHeight
    // document.getElementsByClassName('data-wrap')[0].style.height = height + 'px'
  },
  methods: {
  },
  components: {
    "el-select": Select,
    "el-option": Option,
    "el-row": Row,
    "el-button": Button,
    "el-col": Col,
    "el-input": Input,
    "el-card": Card,
    "el-date-picker": DatePicker
  }
}
</script>

<style lang="less" scoped>
  .root {
    font-size: 14px;
    color: #5E6D82;
    .item-data-wrap{
      background: #fff;
      padding: 30px;
      .el-form-item{
        margin-bottom: 0;
      }
      .item-content-wrap{
        .show-item-wrap{
          margin-bottom: 15px; 
          .item-info-wrap{
            .item-header{
              font-weight: 600;
              font-size: 16px;
              color: #444;
            }
            .item-content{
              padding: 10px;
              .item-info-name{
                color: #7d7a7a;
                font-size: 16px;
              }
              .item-info{
                margin-bottom: 10px;
                font-size: 14px;
                color: #444;
                word-wrap: break-word;
                img{
                  width: 50%;
                }
              }
            }
          }
          .data-wrap{
            position: relative;
            .data-header{
              font-weight: 600;
              font-size: 16px;
              color: #444;
              .header-example{
                color: #ccc;
                font-size: 14px;
              }
            }
            .date-seach{
              position: absolute;
              right: 30px;
              top: 10px;
            }
            .data-content{
              .data-item-wrap{
                .header{
                  color: #444;
                  font-weight: 600;
                }
                .header-content{
                  font-size: 18px;
                  color: #6f6969;
                }
              }
              .age-wrap{
                .age-title{
                  color: #444;
                  font-size: 14px;
                  font-weight: 600;
                }
              }
            }
          }
        }
        .actions-wrap{
          margin-top: 5px;
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          font-size: 16px;
          align-items: center;
          margin-bottom: 10px;
          .label {
            font-size: 14px;
          }
        }
      }
      .line-data-wrap{
        .search-wrap{
          margin-top: 5px;
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          font-size: 16px;
          align-items: center;
          margin-bottom: 10px;
          .warning{
            background: #ebf1fd;
            padding: 8px;
            margin-left: 10px;
            color: #444;
            font-size: 12px;
            i{
              color: #4a8cf3;
              margin-right: 5px;
            }
          }
        }
      }
    }
  }
</style>
