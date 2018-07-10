<template>
  <div class="schedule-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <!-- 搜索 -->
    <div class="search-wrap">
      <el-form :model="searchForm" :inline="true" ref="searchForm" >
        <el-form-item label="" prop="name">
          <el-input v-model="searchForm.name" placeholder="请输入模板名称" class="item-input" clearable></el-input>
        </el-form-item>
        <el-button @click="search" type="primary" size="small">搜索</el-button>
      </el-form>
    </div>
    <div class="actions-wrap">
      <span class="label">
        数量: {{pagination.total}}
      </span>
      <!-- 模板增加 -->
      <div>
        <el-button size="small" type="success" @click="addTemplate('templateForm')">新增策略</el-button>
      </div>
    </div>
    <!-- 模板排期列表 -->
    <el-collapse v-model="activeNames" accordion>
      <el-collapse-item :name="index" v-for="(item, index) in tableData" :key="item.id" >
        <template slot="title">
          {{item.name }} ( {{item.company.name}} ) <el-button type="primary" icon="el-icon-edit" circle size="mini" @click="modifyTemplateName(item)"></el-button>
        </template>
        <div class="actions-wrap">
          <span class="label">
            数目: {{item.batches.data.length}}
          </span>
          <div>
            <el-button size="small" @click="addbatch(index)">增加</el-button>
          </div>
        </div>
        <el-table :data="item.batches.data" style="width: 100%">
          <el-table-column
            prop="name"
            label="优惠券名称"
            min-width="150"
            >
            <template slot-scope="scope">
              <el-select v-model="scope.row.pivot.coupon_batch_id" placeholder="请选择优惠券" filterable :loading="searchLoading" clearable class="item-select">
                <el-option
                  v-for="item in couponList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </template>
          </el-table-column>
          <el-table-column
            prop="name"
            label="性别"
            min-width="150"
            >
            <template slot-scope="scope">
              <el-select v-model="scope.row.pivot.gender" filterable placeholder="请搜索" clearable :loading="searchLoading" style="width: 180px;" @change="genderChangeHandle(index, scope.$index, scope.row.pivot.gender)" >
                <el-option
                  v-for="item in genderList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </template>
          </el-table-column>
          <el-table-column
            prop="max_age"
            label="最大年龄"
            min-width="120"
            >
            <template slot-scope="scope">
              <el-input v-model="scope.row.pivot.max_age" ></el-input>
            </template>
          </el-table-column>
          <el-table-column
            prop="min_age"
            label="最小年龄"
            min-width="120"
            >
            <template slot-scope="scope">
              <el-input v-model="scope.row.pivot.min_age" ></el-input>
            </template>
          </el-table-column>
          <el-table-column
            prop="rate"
            label="概率"
            min-width="120"
            >
            <template slot-scope="scope">
              <el-input v-model="scope.row.pivot.rate" ></el-input>
            </template>
          </el-table-column>
          <el-table-column label="操作" min-width="100">
            <template slot-scope="scope">
              <el-button size="mini" type="warning" v-if="!scope.row.addStauts" @click="editSchedule(scope.row)">编辑</el-button>
              <el-button size="mini" type="danger" icon="el-icon-delete" v-if="scope.row.addStauts" @click="deleteAddBatch(index, scope.$index, scope.row)"></el-button>
              <el-button size="mini" style="background-color: #8bc34a;border-color: #8bc34a; color: #fff;" v-if="scope.row.addStauts" @click="saveSchedule(scope.row)">保存</el-button>
            </template>
          </el-table-column>
        </el-table> 
      </el-collapse-item>
    </el-collapse>
    <div class="pagination-wrap">
      <el-pagination
      layout="prev, pager, next, jumper, total"
      :total="pagination.total"
      :page-size="pagination.pageSize"
      :current-page="pagination.currentPage"
      @current-change="changePage"
      >
      </el-pagination>
    </div>
    <!-- 新增，修改 -->
    <el-dialog :title="title" :visible.sync="templateVisible" @close="dialogClose" >
      <el-form
      ref="templateForm"
      :model="templateForm" label-width="150px" v-loading="loading">
        <el-form-item label="公司" prop="company_id" :rules="[{ type: 'number', required: true, message: '请选择公司', trigger: 'submit' }]">
          <el-select v-model="templateForm.company_id" placeholder="请选择公司" filterable :loading="searchLoading" clearable class="item-select">
            <el-option
              v-for="item in companyList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="策略名" prop="name" :rules="[{ type: 'string', required: true, message: '请输入名称', trigger: 'submit' }]">
          <el-input v-model="templateForm.name" placeholder="请输入名称" class="item-input"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submit('templateForm')" size="small">完成</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script>
import {
  Form,
  FormItem,
  Button,
  Collapse,
  CollapseItem,
  Select,
  Option,
  Pagination,
  Table,
  TableColumn,
  Dialog,
  TimeSelect,
  MessageBox,
  Input
} from 'element-ui'
import search from 'service/search'
import policies from 'service/policies'

export default {
  components: {
    ElCollapse: Collapse,
    ElCollapseItem: CollapseItem,
    ElTimeSelect: TimeSelect,
    ElDialog: Dialog,
    ElPagination: Pagination,
    ElInput: Input,
    ElForm: Form,
    ElFormItem: FormItem,
    ElButton: Button,
    ElSelect: Select,
    ElOption: Option,
    ElTable: Table,
    ElTableColumn: TableColumn
  },
  data() {
    return {
      activeNames: 0,
      templateVisible: false,
      loading: false,
      title: '',
      templateList: [],
      templateForm: {
        company_id: '',
        name: '',
        id: ''
      },
      genderList: [
        {
          id: 'female',
          name: '女'
        },
        {
          id: 'male',
          name: '男'
        }
      ],
      projectList: [],
      tableData: [],
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      companyList: [],
      searchForm: {
        name: ''
      },
      couponList: [],
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      searchLoading: false
    }
  },
  created() {
    this.getCompanyList()
    this.getPoliciesList()
    this.getCouponList()
  },
  methods: {
    getCouponList() {
      search
        .getCouponList(this)
        .then(result => {
          this.couponList = result.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    getCompanyList() {
      search
        .getCompanyList(this)
        .then(result => {
          this.companyList = result.data
          console.log(this.companyList)
        })
        .catch(error => {
          console.log(error)
        })
    },
    modifyTemplateName(item) {
      // this.loading = true
      this.title = '修改策略'
      let name = item.name
      console.log(item)
      let company_id = item.company.id
      let id = item.id
      this.templateForm = {
        name: name,
        id: id,
        company_id: company_id
      }
      console.log(this.templateForm)
      this.templateVisible = true
    },
    genderChangeHandle(pIndex, index, val) {
      console.log(val)
      this.tableData[pIndex].batches.data[index].gender = val
    },
    editSchedule(row) {
      this.setting.loading = true
      let id = row.id
      let date_end = row.date_end
      let date_start = row.date_start
      let project_id = row.project.id
      if (date_end && date_start && project_id) {
        let args = {
          include: 'project',
          project_id: project_id,
          date_end: date_end,
          date_start: date_start
        }
        schedule
          .modifySchedule(this, id, args)
          .then(response => {
            this.setting.loading = false
            this.$message({
              message: '修改成功',
              type: 'success'
            })
            this.getScheduleList()
          })
          .catch(err => {
            console.log(err)
            this.setting.loading = false
          })
      } else {
        this.setting.loading = false
        this.$message({
          message: '节目名称，开始时间，结束时间不能为空',
          type: 'warning'
        })
      }
    },
    saveSchedule(row) {
      this.setting.loading = true
      let date_end = row.date_end
      let date_start = row.date_start
      let tpl_id = row.tpl_id
      let project_id = row.project.id
      if (date_end && date_start && project_id) {
        let args = {
          tpl_id: tpl_id,
          project_id: project_id,
          date_end: date_end,
          date_start: date_start
        }
        schedule
          .saveSchedule(this, args)
          .then(response => {
            this.setting.loading = false
            this.$message({
              message: '添加成功',
              type: 'success'
            })
            this.getScheduleList()
          })
          .catch(err => {
            console.log(err)
            this.setting.loading = false
          })
      } else {
        this.setting.loading = false
        this.$message({
          message: '节目名称，开始时间，结束时间不能为空',
          type: 'warning'
        })
      }
    },
    addTemplate() {
      this.templateForm.name = ''
      this.templateForm.company_id = ''
      this.templateVisible = true
      this.title = '增加策略'
    },
    deleteAddBatch(pIndex, index, r) {
      this.tableData[pIndex].batches.data.splice(index, 1)
    },
    getPoliciesList() {
      this.setting.loading = true
      let args = {
        page: this.pagination.currentPage,
        include: 'batches,company',
        name: this.searchForm.name
      }
      return policies
        .getPoliciesList(this, args)
        .then(response => {
          console.log(response.data)
          this.tableData = response.data
          this.pagination.total = response.meta.pagination.total
          this.setting.loading = false
        })
        .catch(err => {
          console.log(err)
          this.setting.loading = false
        })
    },
    addbatch(index) {
      let company_id = this.tableData[index].id
      let td = {
        name: '',
        addStauts: true,
        pivot: {
          min_age: '',
          rate: '',
          max_age: '',
          gender: ''
        },
        company_id: company_id
      }
      this.tableData[index].batches.data.push(td)
    },
    dialogClose() {
      this.templateVisible = false
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let company_id = this.templateForm.company_id
          let args = {
            name: this.templateForm.name
          }
          console.log(args + company_id)
          if (this.title !== '增加策略') {
            let id = this.templateForm.id
            policies
              .modifyPolicy(this, id, args)
              .then(response => {
                this.$message({
                  message: '修改成功',
                  type: 'success'
                })
                this.templateVisible = false
                this.getPoliciesList()
              })
              .catch(err => {
                this.templateVisible = false
                console.log(err)
              })
          } else {
            policies
              .savePolicy(this, company_id, args)
              .then(response => {
                this.$message({
                  message: '添加成功',
                  type: 'success'
                })
                this.templateVisible = false
                this.getPoliciesList()
              })
              .catch(err => {
                this.templateVisible = false
                console.log(err)
              })
          }
        }
      })
    },
    search() {
      this.pagination.currentPage = 1
      this.getPoliciesList()
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getPoliciesList()
    }
  }
}
</script>
<style lang="less" scoped>
.schedule-wrap {
  background: #fff;
  padding: 30px;
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
      width: 180px;
    }
    .item-input {
      width: 180px;
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
  .item-input {
    width: 220px;
  }
  .item-select {
    width: 220px;
  }
  .el-button.is-circle {
    padding: 5px;
  }
  .actions-wrap {
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
  .pagination-wrap {
    margin: 10px auto;
    text-align: right;
  }
}
</style>
