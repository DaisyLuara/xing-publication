<template>
  <div 
    class="root">
    <div
      v-loading="setting.loading" 
      :element-loading-text="setting.loadingText" 
      class="item-list-wrap" >
      <div 
        class="item-content-wrap">
        <div 
          class="search-wrap">
          <el-form 
            ref="searchForm"
            :model="filters" 
            :inline="true">
            <el-form-item 
              label="" 
              prop="company_id">
              <el-input 
                v-model="filters.name"  
                placeholder="请输入优惠券名称" 
                clearable/>
            </el-form-item>
            <el-button 
              type="primary" 
              size="small"
              @click="search()" >搜索</el-button>
          </el-form>
          <div>
            <el-button 
              type="default" 
              size="small" 
              @click="thirdParty">导入第三方优惠券</el-button>
          </div>
        </div>
        <div 
          class="total-wrap">
          <span 
            class="label">
            总数:{{ pagination.total }} 
          </span>
          <div>
            <el-button 
              size="small" 
              type="success" 
              @click="addCoupon">新增</el-button>
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
                  label="公司">
                  <span>{{ scope.row.company.name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="创建人">
                  <span>{{ scope.row.user.name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="优惠券名称">
                  <span>{{ scope.row.name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="优惠券描述">
                  <span>{{ scope.row.description }}</span> 
                </el-form-item>
                <el-form-item 
                  label="图片">
                  <a 
                    :href="scope.row.image_url"
                    target="_blank" 
                    style="color: blue">查看</a> 
                </el-form-item>
                <el-form-item 
                  label="金额">
                  <span>{{ scope.row.amount }}</span> 
                </el-form-item>
                <el-form-item 
                  label="库存总数">
                  <span>{{ scope.row.count }}</span> 
                </el-form-item>
                <el-form-item 
                  label="剩余库存">
                  <span>{{ scope.row.stock }}</span> 
                </el-form-item>
                <el-form-item 
                  label="每人最大获取数">
                  <span>{{ scope.row.people_max_get }}</span> 
                </el-form-item>
                <el-form-item 
                  label="是否开启没人无限领取">
                  <span>{{ scope.row.pmg_status === 1 ? '开启' : '关闭' }}</span> 
                </el-form-item>
                <el-form-item 
                  label="每天最大获取数">
                  <span>{{ scope.row.day_max_get }}</span> 
                </el-form-item>
                <el-form-item 
                  label="是否开启每天无限领取">
                  <span>{{ scope.row.dmg_status === 1 ? '固定' : '不固定' }}</span> 
                </el-form-item>
                <el-form-item 
                  label="是否固定日期">
                  <span>{{ scope.row.is_fixed_date === 1 ? '固定' : '不固定' }}</span> 
                </el-form-item>
                <el-form-item 
                  label="延后生效天数">
                  <span>{{ scope.row.delay_effective_day }}</span> 
                </el-form-item>
                <el-form-item 
                  label="有效天数">
                  <span>{{ scope.row.effective_day }}</span> 
                </el-form-item>
                <el-form-item 
                  label="开始日期">
                  <span>{{ scope.row.start_date }}</span> 
                </el-form-item>
                <el-form-item 
                  label="结束日期">
                  <span>{{ scope.row.end_date }}</span>
                </el-form-item>
                <el-form-item 
                  label="状态">
                  <span>{{ scope.row.is_active === 1 ? '启用' :'停用' }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="company_id"
            label="公司"
            min-width="100"
          >
            <template 
              slot-scope="scope">
              {{ scope.row.company.name }}
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="user_name"
            label="创建人"
            min-width="100"
          >
            <template 
              slot-scope="scope">
              {{ scope.row.user.name }}
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="name"
            label="名称"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="amount"
            label="金额"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="count"
            label="库存总数"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="stock"
            label="剩余库存"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="effective_day"
            label="有效天数"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="start_date"
            label="开始时间"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="end_date"
            label="结束时间"
            min-width="100"
          />
          <el-table-column 
            label="操作" 
            min-width="150">
            <template 
              slot-scope="scope">
              <el-button 
                size="small" 
                type="warning" 
                @click="linkToEdit(scope.row)">编辑</el-button>
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
    <!-- 导入第三方优惠券 -->
    <el-dialog 
      :visible.sync="templateVisible" 
      title="导入第三方优惠券"
      @close="dialogClose" >
      <el-form
        v-loading="loading"
        ref="templateForm"
        :model="templateForm" 
        label-width="150px">
        <el-form-item 
          :rules="[{ type: 'number', required: true, message: '请选择公司', trigger: 'submit' }]"
          label="公司" 
          prop="company_id" >
          <el-select 
            v-model="templateForm.company_id" 
            placeholder="请选择公司" 
            filterable 
            clearable 
            class="item-select">
            <el-option
              v-for="item in companyList"
              :key="item.id"
              :label="item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button 
            type="primary" 
            size="small" 
            @click="thirdSubmit('templateForm')">完成</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import coupon from 'service/coupon'
import search from 'service/search'

import {
  Button,
  Input,
  Table,
  Select,
  Option,
  TableColumn,
  Pagination,
  Form,
  Dialog,
  FormItem,
  MessageBox
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
    'el-dialog': Dialog
  },
  data() {
    return {
      loading: true,
      companyList: [],
      templateForm: {
        company_id: null
      },
      templateVisible: false,
      filters: {
        name: ''
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
      tableData: []
    }
  },
  created() {
    this.getCouponList()
    this.getCompanyList()
  },
  methods: {
    thirdSubmit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let args = {
            company_id: this.templateForm.company_id
          }
          coupon
            .getSyncCoupon(this, args)
            .then(result => {
              this.getCouponList()
              this.templateVisible = false
            })
            .catch(err => {
              this.templateVisible = false
              console.log(err)
            })
        }
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
    thirdParty() {
      this.loading = false
      this.templateVisible = true
    },
    dialogClose() {
      this.templateVisible = false
    },
    linkToEdit(currentCoupon) {
      this.$router.push({
        path: '/setting/coupon/edit/' + currentCoupon.id
      })
    },
    getCouponList() {
      this.setting.loading = true
      let args = {
        include: 'user,company',
        page: this.pagination.currentPage,
        name: this.filters.name
      }
      coupon
        .getCouponList(this, args)
        .then(response => {
          let data = response.data
          console.log(data)
          this.tableData = data
          this.pagination.total = response.meta.pagination.total
          this.setting.loading = false
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    addCoupon() {
      this.$router.push({
        path: '/setting/coupon/add'
      })
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getCouponList()
    },
    search() {
      this.pagination.currentPage = 1
      this.getCouponList()
    }
  }
}
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;
  .item-list-wrap {
    background: #fff;
    padding: 30px;

    .el-form-item {
      margin-bottom: 0;
    }
    .item-content-wrap {
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
          margin-bottom: 0;
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
