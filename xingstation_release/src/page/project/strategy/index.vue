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
              <el-select v-model="scope.row.pivot.coupon_batch_id" placeholder="请选择优惠券" filterable :loading="searchLoading" class="item-select">
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
              <el-button size="mini" type="warning" v-if="!scope.row.addStauts" @click="editBatch(scope.row)">编辑</el-button>
              <el-button size="mini" type="info" v-if="!scope.row.addStauts" @click="deleteBatch(scope.row)">删除</el-button>
              <el-button size="mini" type="danger" icon="el-icon-delete" v-if="scope.row.addStauts" @click="deleteAddBatch(index, scope.$index, scope.row)"></el-button>
              <el-button size="mini" style="background-color: #8bc34a;border-color: #8bc34a; color: #fff;" v-if="scope.row.addStauts" @click="saveBatch(scope.row)">保存</el-button>
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
        })
        .catch(error => {
          console.log(error)
        })
    },
    deleteBatch(row) {
      let id = row.id
      let company_id = row.pivot.policy_id
      MessageBox.confirm('确认删除选中策略条目?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          this.setting.loadingText = '删除中'
          this.setting.loading = true
          policies
            .deleteBatchPolicy(this,company_id, id)
            .then(response => {
              this.setting.loading = false
              this.$message({
                type: 'success',
                message: '删除成功！'
              })
              this.pagination.currentPage = 1
              this.getPoliciesList()
            })
            .catch(error => {
              this.setting.loading = false
              console.log(error)
            })
        })
        .catch(e => {
          console.log(e)
        })
    },
    modifyTemplateName(item) {
      this.title = '修改策略'
      let name = item.name
      let company_id = item.company.id
      let id = item.id
      this.templateForm = {
        name: name,
        id: id,
        company_id: company_id
      }
      this.templateVisible = true
    },
    genderChangeHandle(pIndex, index, val) {
      this.tableData[pIndex].batches.data[index].gender = val
    },
    editBatch(row) {
      let id = row.id
      let company_id = row.pivot.policy_id
      let max_age = row.pivot.max_age
      let min_age = row.pivot.min_age
      let gender = row.pivot.gender
      let rate = row.pivot.rate
      let coupon_batch_id = row.pivot.coupon_batch_id
      if (max_age === '' && min_age === '' && gender == '' && rate === '') {
        this.$message({
          message: '概率，性别，最大年龄，最小年龄不能都为空',
          type: 'warning'
        })
        return
      }
      if (
        (max_age !== '' && min_age === '') ||
        (max_age === '' && min_age !== '')
      ) {
        this.$message({
          message: '最大年龄，最小年龄必须都填写',
          type: 'warning'
        })
        return
      }
      this.setting.loading = true
      let args = {
        min_age: min_age,
        max_age: max_age,
        gender: gender,
        rate: rate,
        coupon_batch_id: coupon_batch_id
      }
      if (!min_age) {
        delete args.min_age
      }
      if (!max_age) {
        delete args.max_age
      }
      if (!rate) {
        delete args.rate
      }
      if (!gender) {
        delete args.gender
      }
      policies
        .modifyBatchPolicy(this, company_id, args, id)
        .then(response => {
          this.$message({
            message: '修改成功',
            type: 'success'
          })
          this.getPoliciesList()
          this.setting.loading = false
        })
        .catch(err => {
          console.log(err)
          this.getPoliciesList()
          this.setting.loading = false
        })
    },
    saveBatch(row) {
      let company_id = row.pivot.policy_id
      let max_age = row.pivot.max_age
      let min_age = row.pivot.min_age
      let gender = row.pivot.gender
      let rate = row.pivot.rate
      let coupon_batch_id = row.pivot.coupon_batch_id
      if (max_age === '' && min_age === '' && gender == '' && rate === '') {
        this.$message({
          message: '概率，性别，最大年龄，最小年龄不能都为空',
          type: 'warning'
        })
        return
      }
      if (coupon_batch_id === '') {
        this.$message({
          message: '优惠券必须填写',
          type: 'warning'
        })
        return
      }
      if (
        (max_age !== '' && min_age === '') ||
        (max_age === '' && min_age !== '')
      ) {
        this.$message({
          message: '最大年龄，最小年龄必须都填写',
          type: 'warning'
        })
        return
      }
      this.setting.loading = true
      let args = {
        min_age: min_age,
        max_age: max_age,
        gender: gender,
        rate: rate,
        coupon_batch_id: coupon_batch_id
      }
      if (!min_age) {
        delete args.min_age
      }
      if (!max_age) {
        delete args.max_age
      }
      if (!rate) {
        delete args.rate
      }
      if (!gender) {
        delete args.gender
      }
      policies
        .saveBatchPolicy(this, company_id, args)
        .then(response => {
          this.$message({
            message: '添加成功',
            type: 'success'
          })
          this.getPoliciesList()
          this.setting.loading = false
        })
        .catch(err => {
          console.log(err)
          this.getPoliciesList()
          this.setting.loading = false
        })
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
      let policy_id = this.tableData[index].id
      let td = {
        name: '',
        addStauts: true,
        pivot: {
          coupon_batch_id: '',
          min_age: '',
          rate: '',
          max_age: '',
          gender: '',
          policy_id: policy_id
        }
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
