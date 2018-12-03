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
                placeholder="请输入平台名称"
                clearable/>
            </el-form-item>
            <el-form-item 
              label="" 
              prop="status">
              <el-select 
                v-model="filters.status" 
                placeholder="请选择状态" 
                clearable
                class="coupon-form-select">
                <el-option
                  v-for="item in statusList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
            </el-form-item>
            <el-form-item 
              label="" 
              prop="applyDate">
              <el-date-picker
                v-model="filters.applyDate"
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
            <div>
            <span 
              class="label">
              奖金总额: {{ moneyTotal }}
            </span>
            <span 
              class="label"
              style="margin-left:20px;">
              已分配奖金: {{ distributionTotal }}
             </span>
            </div>
          </div>
          <div>
            <el-button
              type="success" 
              size="small"
              @click="applyReward">申请奖励</el-button>
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
                  <span>{{ scope.row.name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="申请人">
                  <span>{{ scope.row.applicant_name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="申请时间">
                  <span>{{ scope.row.created_at }}</span> 
                </el-form-item>
                <el-form-item 
                  label="状态">
                  <span>{{ scope.row.status }}</span> 
                </el-form-item>
                <el-form-item
                  v-if="scope.row.reject_message"
                  label="驳回意见">
                  <span>{{ scope.row.reject_message }}</span> 
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
            prop="name"
            label="平台项目"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="applicant_name"
            label="申请人"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="created_at"
            label="申请时间"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="status"
            label="状态"
            min-width="100"/>
          <el-table-column
            v-if="role.name === 'legal-affairs-manager'" 
            label="操作" 
            min-width="150">
            <template 
              slot-scope="scope">
              <el-button
                v-if="role.name === 'legal-affairs-manager' && scope.row.status === '申请中'" 
                size="small" 
                type="warning"
                @click="rejectFormVisible = true,id = scope.row.id">驳回</el-button>
              <el-button 
                v-if="role.name === 'legal-affairs-manager'  && scope.row.status === '申请中' " 
                size="small"
                @click="allocationHandle(scope.row)">分配</el-button>
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
      title="申请奖金" 
      :show-close="false">
      <el-form 
        v-loading="applyLoading"
        ref="applyForm"
        :model="applyForm"
        label-width="100px">
        <el-form-item 
          prop="applicant_name"
          label="申请人">
          <el-input 
            v-model="applyForm.applicant_name"
            :disabled="true"/>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请输入平台项目', trigger: 'submit' }]"
          prop="name"
          label="平台项目">
          <el-input v-model="applyForm.name"/>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请输入备注', trigger: 'submit' }]"
          prop="remark"
          label="备注">
          <el-input 
            v-model="applyForm.remark"
            type="textarea"
            :autosize="{ minRows: 2, maxRows: 4}"
            :maxlength="400"
            rows="2"/>
        </el-form-item>
        <el-form-item>
          <el-button 
            size="small"
            type="primary" 
            @click="submitApply('applyForm')">确 定</el-button>
          <el-button
            size="small" 
            @click="applyFormVisible = false">取 消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <el-dialog 
      :visible.sync="allocationFormVisible"
      title="分配奖金" 
      :show-close="false">
      <el-form
        v-loading="allocationLoading" 
        ref="allocationForm"
        :model="allocationForm"
        label-width="100px">
        <el-form-item label="可发奖金">
          <el-input 
            v-model="allocationForm.total"
            :disabled="true"/>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请输入分配数额', trigger: 'submit' }]"
          prop="count"
          label="分配数额">
          <el-input v-model="allocationForm.count"/>
        </el-form-item>
        <el-form-item>
          <el-button
            size="small" 
            @click="allocationFormVisible = false">取 消</el-button>
          <el-button 
            size="small"
            type="primary" 
            @click="systemDistribute('allocationForm')">确 定</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <el-dialog 
      :visible.sync="rejectFormVisible"
      title="驳回申请" 
      :show-close="false">
      <el-form 
        ref="rejectForm"
        :model="rejectForm"
        label-width="100px">
        <el-form-item 
          prop="reject_message"
          label="驳回意见">
          <el-input v-model="rejectForm.reject_message"/>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button 
          size="small"
          @click="rejectFormVisible = false">取 消</el-button>
        <el-button 
          size="small"
          type="primary" 
          @click="rejectHandle('rejectForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {
  getTeamSystemProject,
  saveTeamSystemProject,
  systemDistribute,
  getSystemBonus,
  getDistributionBonus,
  systemReject
} from 'service'
import { Cookies } from 'utils/cookies'
import {
  Button,
  Input,
  Table,
  Select,
  Option,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox,
  DatePicker,
  Checkbox,
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
    'el-select': Select,
    'el-option': Option,
    'el-form-item': FormItem,
    'el-date-picker': DatePicker,
    'el-checkbox': Checkbox,
    'el-dialog': Dialog
  },
  data() {
    return {
      applyLoading: false,
      rejectForm: {
        reject_message: ''
      },
      rejectFormVisible: false,
      allocationFormVisible: false,
      allocationForm: {
        total: 100,
        count: ''
      },
      applyForm: {
        applicant: '',
        applicant_name: '',
        name: '',
        remark: ''
      },
      moneyTotal: 0,
      residueTotal: 0,
      distributionTotal: 0,
      applyFormVisible: false,
      filters: {
        name: '',
        status: '',
        applyDate: [
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
      statusList: [
        {
          id: 1,
          name: '申请中'
        },
        {
          id: 2,
          name: '已分配'
        },
        {
          id: 3,
          name: '已驳回'
        }
      ],
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
      tableData: [],
      id: null,
      allocationLoading: false
    }
  },
  created() {
    this.getTeamSystemProject()
    this.getSystemBonus()
    this.getDistributionBonus()
    let user_info = JSON.parse(Cookies.get('user_info'))
    this.role = user_info.roles.data[0]
    this.applyForm.applicant_name = user_info.name
    this.applyForm.applicant = user_info.id
  },
  methods: {
    getDistributionBonus() {
      let args = {
        start_date: this.handleDateTransform(this.filters.applyDate[0]),
        end_date: this.handleDateTransform(this.filters.applyDate[1])
      }
      getDistributionBonus(this, args)
        .then(res => {
          this.distributionTotal = res.distribution_bonus
          this.residueTotal = this.moneyTotal - this.distributionTotal
          this.allocationForm.total = this.residueTotal
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
        start_date: this.handleDateTransform(this.filters.applyDate[0]),
        end_date: this.handleDateTransform(this.filters.applyDate[1])
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
    systemDistribute(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.allocationLoading = true
          let args = {
            money: this.allocationForm.count
          }
          if (this.allocationForm.total - this.allocationForm.count < 0) {
            this.$message({
              type: 'warning',
              message: '分配数额大于可发奖金，请重新填写!'
            })
            return
          }
          systemDistribute(this, this.id, args)
            .then(res => {
              this.allocationFormVisible = false
              this.$message({
                type: 'success',
                message: '分配成功!'
              })
              this.allocationLoading = false
              this.getTeamSystemProject()
              this.getDistributionBonus()
              this.getSystemBonus()
            })
            .catch(err => {
              this.$message({
                type: 'warning',
                message: err.response.data.message
              })
              this.allocationLoading = false
              this.allocationFormVisible = false
            })
        }
      })
    },
    submitApply(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.applyLoading = true
          let args = {
            name: this.applyForm.name,
            applicant: this.applyForm.applicant,
            remark: this.applyForm.remark
          }
          saveTeamSystemProject(this, args)
            .then(res => {
              this.applyFormVisible = false
              this.$message({
                type: 'success',
                message: '申请成功!'
              })
              this.applyLoading = false
              this.getTeamSystemProject()
            })
            .catch(err => {
              this.$message({
                type: 'warning',
                message: err.response.data.message
              })
              this.applyLoading = false
              this.applyFormVisible = false
            })
        }
      })
    },
    allocationHandle(row) {
      this.id = row.id
      this.getDistributionBonus()
      this.allocationFormVisible = true
    },
    rejectHandle() {
      let args = {
        reject_message: this.rejectForm.reject_message
      }
      systemReject(this, this.id, args)
        .then(res => {
          this.$message({
            type: 'success',
            message: '驳回成功'
          })
          this.getTeamSystemProject()
          this.rejectFormVisible = false
        })
        .catch(err => {
          this.$message({
            type: 'warning',
            message: err.response.data.message
          })
          this.rejectFormVisible = false
        })
    },
    applyReward() {
      this.applyFormVisible = true
    },
    getTeamSystemProject() {
      this.setting.loading = true
      let args = {
        page: this.pagination.currentPage,
        name: this.filters.name,
        status: this.filters.status,
        start_date: this.handleDateTransform(this.filters.applyDate[0]),
        end_date: this.handleDateTransform(this.filters.applyDate[1])
      }
      if (this.filters.name === '') {
        delete args.name
      }
      if (!this.filters.status) {
        delete args.status
      }
      if (JSON.stringify(this.filters.applyDate) === '[]') {
        delete args.start_date
        delete args.end_date
      }
      getTeamSystemProject(this, args)
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
      this.getTeamSystemProject()
      this.getSystemBonus()
      this.getDistributionBonus()
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getTeamSystemProject()
    },
    search() {
      this.pagination.currentPage = 1
      this.getTeamSystemProject()
      this.getSystemBonus()
      this.getDistributionBonus()
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
          font-size: 14px;
          margin: 5px 0;
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
