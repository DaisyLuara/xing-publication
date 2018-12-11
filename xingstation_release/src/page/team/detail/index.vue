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
                placeholder="平台项目"
                clearable/>
            </el-form-item>
            <el-form-item 
              label=""
              prop="user_name">
              <el-input 
                v-model="filters.user_name"
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
          <div v-if="role.name === 'legal-affairs-manager'">
            <span 
              class="label">
              奖金总额:<span class="count">{{ moneyTotal }}</span>
              &nbsp;&nbsp;&nbsp;&nbsp;
            </span>
            <span 
              class="label">
              已发奖金:<span class="count">{{ distributionTotal }}</span>
            </span>
          </div>
          <div>
            <el-button
              v-if="role.name === 'legal-affairs-manager'" 
              type="success"
              size="small"
              @click="applyFormVisible = true">新建分配</el-button>
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
                  <span>{{ scope.row.user_name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="分配时间">
                  <span>{{ scope.row.date }}</span> 
                </el-form-item>
                <el-form-item 
                  label="发放奖金">
                  <span>{{ scope.row.system_money }}</span>
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
            prop="user_name"
            label="分配人"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="date"
            label="分配时间"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="system_money"
            label="发放奖金"
            min-width="100"/>
          <el-table-column 
            v-if="role.name === 'legal-affairs-manager'" 
            label="操作" 
            min-width="150">
            <template 
              slot-scope="scope">
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
        v-loading="loading" 
        ref="applyForm"
        :model="applyForm"
        label-width="100px">
        <el-form-item
          :rules="[{ required: true, message: '请输入平台项目', trigger: 'submit' }]" 
          prop="user_id"
          label="分配人">
          <el-select 
            v-model="applyForm.user_id" 
            :loading="searchLoading"
            placeholder="请添加人员" 
            filterable 
            clearable
            style="width: 250px;">
            <el-option
              v-for="item in userList"
              :key="item.id"
              :label="item.name"
              :value="item.id"/>
            </el-select>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请输入平台项目', trigger: 'submit' }]"
          prop="project_name"
          label="平台项目">
          <el-input 
            v-model="applyForm.project_name"
            placeholder="请输入平台项目"
            :maxlength="30"
            style="width: 250px;"/>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请输入分配金额', trigger: 'submit' }]" 
          prop="system_money"
          label="分配金额">
          <el-input 
            v-model="applyForm.system_money"
            :maxlength="10"
            placeholder="请输入分配金额"
            style="width: 250px;"/>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="applyFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit('applyForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {
  getSystemBonus,
  getDistributionBonus,
  getSystemDetails,
  saveSystemDetailMoney,
  getSystemlMoneyDetail,
  modifySystemDetailMoney,
  getSearchUserList,
  handleDateTypeTransform
} from 'service'
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
  Dialog,
  Select,
  Option
} from 'element-ui'

export default {
  components: {
    'el-table': Table,
    'el-table-column': TableColumn,
    'el-button': Button,
    'el-input': Input,
    'el-select': Select,
    'el-option': Option,
    'el-pagination': Pagination,
    'el-form': Form,
    'el-form-item': FormItem,
    'el-date-picker': DatePicker,
    'el-dialog': Dialog
  },
  data() {
    return {
      loading: false,
      applyFormVisible: false,
      applyForm: {
        project_name: '',
        system_money: 0,
        user_id: ''
      },
      filters: {
        name: '',
        user_name: '',
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
      moneyTotal: 0,
      distributionTotal: 0,
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
      id: '',
      tableData: [],
      userList: [],
      searchLoading: false
    }
  },
  created() {
    this.getSystemDetails()
    this.getSystemBonus()
    this.getDistributionBonus()
    this.getUserList()
    let user_info = JSON.parse(Cookies.get('user_info'))
    this.role = user_info.roles.data[0]
    this.applyForm.applicant_name = user_info.name
    this.applyForm.applicant = user_info.id
  },
  methods: {
    getUserList() {
      this.searchLoading = true
      return getSearchUserList(this)
        .then(response => {
          this.userList = response.data
          this.searchLoading = false
        })
        .catch(err => {
          this.searchLoading = false
        })
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let args = {
            user_id: this.applyForm.user_id,
            project_name: this.applyForm.project_name,
            system_money: this.applyForm.system_money
          }
          if (this.id) {
            args.total = this.applyForm.system_money
            modifySystemDetailMoney(this, this.id, args)
              .then(res => {
                this.$message({
                  type: 'success',
                  message: '修改成功!'
                })
                this.getSystemBonus()
                this.getDistributionBonus()
                this.getSystemDetails()
                this.applyFormVisible = false
              })
              .catch(err => {
                this.$message({
                  type: 'warning',
                  message: err.response.data.message
                })
                this.applyFormVisible = false
              })
          } else {
            saveSystemDetailMoney(this, args)
              .then(res => {
                this.$message({
                  type: 'success',
                  message: '分配成功!'
                })
                this.applyFormVisible = false
                this.getSystemBonus()
                this.getDistributionBonus()
                this.getSystemDetails()
              })
              .catch(err => {
                this.$message({
                  type: 'warning',
                  message: err.response.data.message
                })
                this.applyFormVisible = false
              })
          }
        }
      })
    },
    getDistributionBonus() {
      let args = {
        start_date: handleDateTypeTransform(this.filters.beginDate[0]),
        end_date: handleDateTypeTransform(this.filters.beginDate[1])
      }
      getDistributionBonus(this, args)
        .then(res => {
          this.distributionTotal = res.distribution_bonus
        })
        .catch(err => {
          this.$message({
            type: 'warning',
            message: err.response.data.message
          })
        })
    },
    getSystemBonus() {
      let args = {
        start_date: handleDateTypeTransform(this.filters.beginDate[0]),
        end_date: handleDateTypeTransform(this.filters.beginDate[1])
      }
      getSystemBonus(this, args)
        .then(res => {
          this.moneyTotal = res.total_bonus
        })
        .catch(err => {
          this.$message({
            type: 'warning',
            message: err.response.data.message
          })
        })
    },
    modifyHandle(data) {
      this.id = data.id
      this.applyForm.project_name = data.project_name
      this.applyForm.system_money = data.system_money
      this.getSystemlMoneyDetail()
      this.applyFormVisible = true
    },
    getSystemlMoneyDetail() {
      this.loading = true
      getSystemlMoneyDetail(this, this.id)
        .then(res => {
          this.applyForm.user_id = res.user_id
          this.applyForm.project_name = res.project_name
          this.applyForm.system_money = res.system_money
          this.loading = false
        })
        .catch(err => {
          this.$message({
            type: 'warning',
            message: err.response.data.message
          })
          this.loading = false
        })
    },
    getSystemDetails() {
      this.setting.loading = true
      let args = {
        page: this.pagination.currentPage,
        name: this.filters.name,
        user_name: this.filters.user_name,
        start_date: handleDateTypeTransform(this.filters.beginDate[0]),
        end_date: handleDateTypeTransform(this.filters.beginDate[1])
      }
      if (this.filters.name === '') {
        delete args.name
      }
      if (!this.filters.user_name) {
        delete args.user_name
      }
      if (JSON.stringify(this.filters.beginDate) === '[]') {
        delete args.start_date
        delete args.end_date
      }
      getSystemDetails(this, args)
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
      this.getSystemBonus()
      this.getDistributionBonus()
      this.getSystemDetails()
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getSystemDetails()
    },
    search() {
      this.pagination.currentPage = 1
      this.getSystemBonus()
      this.getDistributionBonus()
      this.getSystemDetails()
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
          width: 250px;
        }
        .item-input {
          width: 250px;
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
