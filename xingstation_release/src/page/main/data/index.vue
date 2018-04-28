<template>
  <div class="root">
    <div class="main-data-wrap">
      <div class="scope-wrap">
        <label class="search-text">时间范围：</label>
        <el-date-picker
          v-model="value"
          type="daterange"
          range-separator="至"
          start-placeholder="开始日期"
          end-placeholder="结束日期">
        </el-date-picker>
        <label class="warning"> <i class="el-icon-warning"></i> 由于数量较大，昨日数据正式生成，请稍后再获取</label>
      </div>
      <el-row :gutter="15">
        <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8">
          <div class="data-wrap">
            <div class="data-title">围观数 <span class="ranking">(TOP1)</span></div>
            <div class="data-content">
              <highcharts :options="options"></highcharts>
            </div>
          </div>
        </el-col>
        <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8">
          <div class="data-wrap">
            <div class="data-title">体验数 <span class="ranking">(TOP2)</span></div>
            <div class="data-content">
              <highcharts :options="options"></highcharts>
            </div>
          </div>
        </el-col>
        <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8">
          <div class="data-wrap">
            <div class="data-title">扫码率 <span class="ranking">(TOP3)</span></div>
            <div class="data-content">
              <highcharts :options="options"></highcharts>
            </div>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="15">
        <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8">
          <div class="data-wrap">
            <div class="data-title">变现率 <span class="ranking">(TOP4)</span></div>
            <div class="data-content">
              <highcharts :options="options"></highcharts>
            </div>
          </div>
        </el-col>
        <el-col :xs="8" :sm="8" :md="8" :lg="8" :xl="8">
          <div class="data-wrap">
            <div class="data-title">总花费(元) <span class="ranking">(TOP5)</span></div>
            <div class="data-content">
              <highcharts :options="options"></highcharts>
            </div>
          </div>
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
import { DatePicker, Row, Col} from 'element-ui'

export default {
  data () {
    return {
      value: '',
      options : {
        title: {
          text:''
        },
        xAxis: {
          categories: ['11月30日', '12月1日', '12月3日', '12月4日', '12月5日', '12月6日',
            '12月7日', '12月8日', '12月9日', '12月10日', '12月11日', '12月12日'
          ]
        },
        yAxis: [{
          title: {
            text: '成交订单数',
            rotation: 0,
            align: 'high',
            y: 5
          }
        }],
        legend: {
          enabled: true
        },
        credits: {
          enabled: false
        },
        series: [{
          data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6],
          dashStyle: 'shortDash',
          color: "#3493bd",
          name:"成交订单数"
        }]
      },
    }
  },
  components: {
    "el-date-picker": DatePicker,
    "el-row": Row,
    "el-col": Col
  }
}
</script>

<style lang="less" scoped>
  .root {
    padding: 10px;
    font-size: 14px;
    color: #5E6D82;
    .main-data-wrap{
      .scope-wrap{
        padding: 10px 30px;
        background: #fff;
        .search-text{
          font-size: 14px;
        }
        .warning{
          background: #ebf1fd;
          padding: 8px;
          margin-left: 10px;
          color: #444;
          i{
            color: #4a8cf3;
            margin-right: 5px;
          }
        }
      }
      .data-wrap{
        margin-top: 10px;
        background: #fff;
        .data-title{
          padding: 15px;
          color: #444;
          font-size: 18px;
          font-weight: 600;
          .ranking{
            color: #ccc;
          }
        }
        .data-content{
          text-align: center;
          padding-bottom: 10px;
        }
      }
    }
  }
</style>
