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
              prop="alias">
              <el-input 
                v-model="filters.alias" 
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
              prop="onlineDate">
              <el-date-picker
                v-model="filters.onlineDate"
                :clearable="false"
                :picker-options="pickerOptions"
                type="daterange"
                start-placeholder="上线开始时间"
                end-placeholder="上线结束时间"
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
            <div 
              class="label">
              奖金总额:10000.00
            </div>
          </div>
          <div>
            <el-button
              v-if="role.name === 'project-manager'" 
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
                  label="平台名称">
                  <span>{{ scope.row.project_name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="申请人">
                  <span>{{ scope.row.applicant_name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="开始时间">
                  <span>{{ scope.row.begin_date }}</span> 
                </el-form-item>
                <el-form-item 
                  label="上线时间">
                  <span>{{ scope.row.online_date }}</span> 
                </el-form-item>
                <el-form-item 
                  label="状态">
                  <span>{{ scope.row.status }}</span> 
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
            label="平台名称"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="applicant_name"
            label="申请人"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="begin_date"
            label="开始时间"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="online_date"
            label="上线时间"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="status"
            label="状态"
            min-width="100"/>
          <el-table-column 
            label="操作" 
            min-width="150">
            <template 
              slot-scope="scope">
              <el-button
                v-if="role.name === 'legal-affairs-manager'" 
                size="small" 
                type="warning"
                @click="rejectHandle(scope.row)">驳回</el-button>
              <el-button 
                v-if="role.name === 'legal-affairs-manager'" 
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
        :model="applyForm"
        label-width="100px">
        <el-form-item label="申请人">
          <el-input 
            v-model="applyForm.applicant_name"
            :disabled="true"/>
        </el-form-item>
        <el-form-item label="平台项目名称">
          <el-input v-model="applyForm.project_name"/>
        </el-form-item>
        <el-form-item label="备注">
          <el-input 
            v-model="applyForm.remark"
            type="textarea"
            :autosize="{ minRows: 2, maxRows: 4}"
            :maxlength="400"
            rows="2"/>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="applyFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="applyFormVisible = false">确 定</el-button>
      </div>
    </el-dialog>
    <el-dialog 
      :visible.sync="allocationFormVisible"
      title="申请奖金" 
      :show-close="false">
      <el-form 
        :model="allocationForm"
        label-width="100px">
        <el-form-item label="可发奖金">
          <el-input 
            v-model="allocationForm.total"
            :disabled="true"/>
        </el-form-item>
        <el-form-item label="分配数额">
          <el-input v-model="allocationForm.count"/>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="allocationFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="allocationFormVisible = false">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getProgramList, confirmProgram } from 'service'
import search from 'service/search'
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
      allocationFormVisible: false,
      allocationForm: {
        total: 100,
        count: ''
      },
      applyForm: {
        applicant: '',
        applicant_name: '',
        project_name: '',
        remark: ''
      },
      applyFormVisible: false,
      filters: {
        alias: '',
        status: '',
        onlineDate: []
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
          name: '已驳回'
        },
        {
          id: 3,
          name: '已分配'
        }
      ],
      pickerOptions: {
        disabledDate: time => {
          return (
            time.getTime() < new Date('2018/11/29').getTime()
          )
        }
      },
      tableData: [
        {
          id: 1,
          project_name: '测试',
          applicant_name: '测试',
          begin_date: '2018-09-09',
          online_date: '2018-10-10',
          status: '申请中'
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
    allocationHandle(row) {
      this.allocationFormVisible = true
    },
    rejectHandle() {
      this.$confirm('确认驳回吗?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          this.$message({
            type: 'success',
            message: '驳回成功!'
          })
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: '已取消驳回'
          })
        })
    },
    applyReward() {
      this.applyFormVisible = true
    },
    getProgramList() {
      this.setting.loading = true
      let args = {
        page: this.pagination.currentPage,
        alias: this.filters.alias,
        status: this.filters.status,
        start_date_online: this.handleDateTransform(this.filters.onlineDate[0]),
        end_date_online: this.handleDateTransform(this.filters.onlineDate[1])
      }
      if (this.filters.alias === '') {
        delete args.alias
      }
      if (!this.filters.status) {
        delete args.status
      }
      if (JSON.stringify(this.filters.onlineDate) === '[]') {
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
