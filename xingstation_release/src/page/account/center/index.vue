<template>
  <div 
    class="root">
    <div 
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText" 
      class="program-list-wrap">
      <div 
        class="program-content-wrap">
        <div 
          class="search-wrap">
          <el-form 
            ref="filters"
            :model="filters" 
            :inline="true">
            <el-form-item 
              label=""
              prop="name">
              <el-input 
                v-model="filters.name"
                placeholder="名称"
                clearable
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="" 
              prop="beginDate">
              <el-date-picker
                v-model="filters.beginDate"
                :clearable="false"
                :picker-options="pickerOptions"
                type="daterange"
                start-placeholder="开始时间"
                end-placeholder="结束时间"
                align="right">
              </el-date-picker>
            </el-form-item>
            <el-form-item 
              label="" 
              prop="">
              <el-button 
                type="primary" 
                size="small"
                @click="search()">搜索</el-button>
              <el-button 
                size="small"
                @click="resetForm('filters')">重置</el-button>
            </el-form-item>
          </el-form>
        </div>
        <div 
          class="total-wrap">
          <div>
            <span 
              class="label">
              累计奖金(¥):<span class="count">{{ moneyTotal }}</span>
            </span>
          </div>
        </div>
        <el-table 
          :data="tableData" 
          style="width: 100%" >
          <el-table-column 
            type="expand">
            <template 
              slot-scope="scope">
              <el-form 
                label-position="left" 
                inline 
                class="demo-table-expand">
                <el-form-item 
                  label="ID">
                  <span>{{ scope.row.id }}</span> 
                </el-form-item>
                <el-form-item 
                  label="名称">
                  <span>{{ scope.row.project_name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="获取时间">
                  <span>{{ scope.row.date }}</span> 
                </el-form-item>
                <el-form-item 
                  label="体验绩效">
                  <span>{{ scope.row.experience_money }}</span> 
                </el-form-item>
                <el-form-item 
                  label="平台绩效">
                  <span>{{ scope.row.system_money }}</span> 
                </el-form-item>
                <el-form-item 
                  label="小偶绩效">
                  <span>{{ scope.row.xo_money }}</span> 
                </el-form-item>
                <el-form-item 
                  label="联动绩效">
                  <span>{{ scope.row.link_money }}</span> 
                </el-form-item>
                <el-form-item 
                  label="总计">
                  <span>{{ scope.row.total }}</span> 
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="id"
            label="ID"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="project_name"
            label="名称"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="date"
            label="获取时间"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="experience_money"
            label="体验绩效"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="system_money"
            label="平台绩效"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="xo_money"
            label="小偶绩效"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="link_money"
            label="联动绩效"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="total"
            label="总计"
            min-width="100"/>
        </el-table>
        <div 
          class="pagination-wrap">
          <el-pagination
            :total="pagination.total"
            :page-size="pagination.pageSize"
            :current-page="pagination.currentPage"
            layout="prev, pager, next, jumper, total"
            @current-change="changePage"
          />
        </div>
      </div>  
    </div>
  </div>
</template>

<script>
import { getPersonRewardList, getPersonRewardTotal } from 'service'
import {
  Button,
  Input,
  Table,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox,
  DatePicker
} from 'element-ui'

export default {
  components: {
    'el-table': Table,
    'el-table-column': TableColumn,
    'el-button': Button,
    'el-input': Input,
    'el-pagination': Pagination,
    'el-form': Form,
    'el-form-item': FormItem,
    'el-date-picker': DatePicker
  },
  data() {
    return {
      filters: {
        name: '',
        beginDate: [
          new Date().getTime() - 3600 * 1000 * 24 * 6,
          new Date().getTime()
        ]
      },
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      role: '',
      pickerOptions: {
        shortcuts: [
          {
            text: '今天',
            onClick(picker) {
              const end = new Date()
              const start = new Date()
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '昨天',
            onClick(picker) {
              const end = new Date()
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24)
              end.setTime(end.getTime() - 3600 * 1000 * 24)
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '最近一周',
            onClick(picker) {
              const end = new Date().getTime() - 3600 * 1000 * 24
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '最近一个月',
            onClick(picker) {
              const end = new Date() - 3600 * 1000 * 24
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '最近三个月',
            onClick(picker) {
              const end = new Date() - 3600 * 1000 * 24
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90)
              picker.$emit('pick', [start, end])
            }
          }
        ]
      },
      moneyTotal: 0,
      tableData: []
    }
  },
  created() {
    this.getPersonRewardList()
    this.getPersonRewardTotal()
  },
  methods: {
    getPersonRewardTotal() {
      let args = {
        start_date: this.handleDateTransform(this.filters.beginDate[0]),
        end_date: this.handleDateTransform(this.filters.beginDate[1])
      }
      getPersonRewardTotal(this)
        .then(res => {
          this.moneyTotal = res.total_reward
        })
        .catch(err => {
          this.$message({
            type: 'warning',
            message: err.response.data.message
          })
        })
    },
    getPersonRewardList() {
      this.setting.loading = true
      let args = {
        page: this.pagination.currentPage,
        name: this.filters.name,
        start_date: this.handleDateTransform(this.filters.beginDate[0]),
        end_date: this.handleDateTransform(this.filters.beginDate[1])
      }
      if (this.filters.name === '') {
        delete args.name
      }
      if (!this.filters.status) {
        delete args.status
      }
      if (JSON.stringify(this.filters.beginDate) === '[]') {
        delete args.start_date
        delete args.end_date
      }
      getPersonRewardList(this, args)
        .then(res => {
          this.tableData = res.data
          this.pagination.total = res.meta.pagination.total
          this.setting.loading = false
        })
        .catch(err => {
          this.$message({
            type: 'warning',
            message: err.response.data.message
          })
          this.setting.loading = false
        })
    },
    resetForm(formName) {
      this.$refs[formName].resetFields()
      this.pagination.currentPage = 1
      this.getPersonRewardList()
      this.getPersonRewardTotal()
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getPersonRewardList()
    },
    search() {
      this.pagination.currentPage = 1
      this.getPersonRewardList()
      this.getPersonRewardTotal()
    },
    handleDateTransform(valueDate) {
      let date = new Date(valueDate)
      let year = date.getFullYear() + '-'
      let mouth =
        (date.getMonth() + 1 < 10
          ? '0' + (date.getMonth() + 1)
          : date.getMonth() + 1) + '-'
      let day =
        (date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + ''
      return year + mouth + day
    }
  }
}
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;

  .program-list-wrap {
    background: #fff;
    padding: 30px;

    .el-form-item {
      margin-bottom: 15px;
    }
    .program-content-wrap {
      .demo-table-expand {
        font-size: 0;
      }
      .demo-table-expand label {
        width: 90px;
        color: #99a9bf;
      }
      .demo-table-expand .el-form-item {
        margin-right: 0;
        margin-bottom: 0;
        width: 50%;
      }
      .icon-item {
        padding: 10px;
        width: 50%;
      }
      .search-wrap {
        margin-top: 5px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
        margin-bottom: 10px;
        .el-form-item {
          margin-bottom: 10px;
        }
        .el-select {
          width: 200px;
        }
        .item-input {
          width: 230px;
        }
        .warning {
          background: #ebf1fd;
          padding: 8px;
          margin-left: 10px;
          color: #444;
          font-size: 12px;
          i {
            color: #4a8cf3;
            margin-right: 5px;
          }
        }
      }
      .total-wrap {
        margin-top: 5px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
        margin-bottom: 10px;
        .label {
          font-size: 18px;
          margin: 5px 0;
          .count {
            color: #03a9f4;
          }
        }
      }
      .pagination-wrap {
        margin: 10px auto;
        text-align: right;
      }
    }
  }
}
</style>
