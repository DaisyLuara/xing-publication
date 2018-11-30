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
                v-model="filters.project_name"
                placeholder="平台项目"
                clearable/>
            </el-form-item>
            <el-form-item 
              label=""
              prop="name">
              <el-input 
                v-model="filters.name"
                placeholder="分配人"
                clearable/>
            </el-form-item>
            <el-form-item 
              label="" 
              prop="beginDate">
              <el-date-picker
                v-model="filters.beginDate"
                :clearable="false"
                :picker-options="pickerOptions"
                type="daterange"
                start-placeholder="分配开始时间"
                end-placeholder="分配结束时间"
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
              奖金总额:<span class="count">10000.00</span>
              &nbsp;&nbsp;&nbsp;&nbsp;
            </span>
            <span 
              class="label">
              已发奖金:<span class="count">1000.00</span>
            </span>
          </div>
          <div>
            <el-button
              type="success"
              size="small">新建分配</el-button>
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
                  label="平台项目">
                  <span>{{ scope.row.project_name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="分配人">
                  <span>{{ scope.row.applicant_name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="分配时间">
                  <span>{{ scope.row.begin_date }}</span> 
                </el-form-item>
                <el-form-item 
                  label="发放奖金">
                  <span>{{ scope.row.amount }}</span> 
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
            label="平台项目"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="applicant_name"
            label="分配人"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="begin_date"
            label="分配时间"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="amount"
            label="发放奖金"
            min-width="100"/>
          <el-table-column 
            label="操作" 
            min-width="150">
            <template 
              slot-scope="scope">
                <!-- v-if="role.name === 'legal-affairs-manager'"  -->
              <el-button
                size="small" 
                type="warning"
                @click="modifyHandle(scope.row)">修改</el-button>
            </template>
          </el-table-column>
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
    <el-dialog 
      :visible.sync="applyFormVisible"
      title="分配奖金" 
      :show-close="false">
      <el-form 
        :model="applyForm"
        label-width="100px">
        <el-form-item label="分配人">
          <el-input 
            v-model="applyForm.applicant_name"
            :disabled="true"/>
        </el-form-item>
        <el-form-item label="平台项目名称">
          <el-input v-model="applyForm.project_name"/>
        </el-form-item>
        <el-form-item label="分配金额">
          <el-input 
            v-model="applyForm.amount"
            :maxlength="10"/>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="applyFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="applyFormVisible = false">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getProgramList } from 'service'
import { Cookies } from 'utils/cookies'
import {
  Button,
  Input,
  Table,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox,
  DatePicker,
  Dialog
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
    'el-date-picker': DatePicker,
    'el-dialog': Dialog
  },
  data() {
    return {
      applyFormVisible: false,
      applyForm: {
        applicant_name: '',
        project_name: '',
        amount: 0,
        applicant: ''
      },
      filters: {
        name: '',
        project_name: '',
        beginDate: []
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
      tableData: [
        {
          id: 1,
          project_name: '测试',
          applicant_name: '测试',
          begin_date: '2018-09-09',
          amount: '100'
        }
      ]
    }
  },
  created() {
    // this.getProgramList()
    let user_info = JSON.parse(Cookies.get('user_info'))
    this.role = user_info.roles.data[0]
    this.applyForm.applicant_name = user_info.name
    this.applyForm.applicant = user_info.id
  },
  methods: {
    modifyHandle() {
      this.applyFormVisible = true
    },
    getProgramList() {
      this.setting.loading = true
      let args = {
        page: this.pagination.currentPage,
        alias: this.filters.alias,
        status: this.filters.status,
        start_date_online: this.handleDateTransform(this.filters.beginDate[0]),
        end_date_online: this.handleDateTransform(this.filters.beginDate[1])
      }
      if (this.filters.alias === '') {
        delete args.alias
      }
      if (!this.filters.status) {
        delete args.status
      }
      if (JSON.stringify(this.filters.beginDate) === '[]') {
        delete args.start_date_online
        delete args.end_date_online
      }
      getProgramList(this, args)
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
      // this.getProgramList()
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      // this.getProgramList()
    },
    search() {
      this.pagination.currentPage = 1
      // this.getProgramList()
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
