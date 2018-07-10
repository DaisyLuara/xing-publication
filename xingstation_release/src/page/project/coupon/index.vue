<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
        <div class="search-wrap">
          <el-form :model="filters" :inline="true" ref="searchForm" >
            <el-form-item label="" prop="company_id">
              <el-input v-model="filters.name"  placeholder="请输入优惠券名称" clearable></el-input>
            </el-form-item>
            <el-button @click="search()" type="primary" size="small">搜索</el-button>
          </el-form>
        </div>
        <div class="total-wrap">
          <span class="label">
            总数:{{pagination.total}} 
          </span>
          <div>
            <el-button size="small" type="success" @click="addCoupon">新增</el-button>
          </div>
        </div>
        <el-table :data="tableData" style="width: 100%" >
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="公司">
                  <span>{{scope.row.company.name}}</span> 
                </el-form-item>
                <el-form-item label="创建人">
                  <span>{{scope.row.user.name}}</span> 
                </el-form-item>
                <el-form-item label="优惠券名称">
                  <span>{{scope.row.name}}</span> 
                </el-form-item>
                <el-form-item label="优惠券描述">
                  <span>{{scope.row.description}}</span> 
                </el-form-item>
                <el-form-item label="图片">
                  <a :href="scope.row.image_url" target="_blank" style="color: blue">查看</a> 
                </el-form-item>
                <el-form-item label="金额">
                  <span>{{scope.row.amount}}</span> 
                </el-form-item>
                <el-form-item label="库存总数">
                  <span>{{scope.row.count}}</span> 
                </el-form-item>
                <el-form-item label="剩余库存">
                  <span>{{scope.row.stock}}</span> 
                </el-form-item>
                <el-form-item label="每人最大获取数">
                  <span>{{scope.row.people_max_get}}</span> 
                </el-form-item>
                <el-form-item label="是否开启没人无限领取">
                  <span>{{scope.row.pmg_status === '1' ? '开启' : '关闭'}}</span> 
                </el-form-item>
                <el-form-item label="每天最大获取数">
                  <span>{{scope.row.day_max_get}}</span> 
                </el-form-item>
                <el-form-item label="是否开启每天无限领取">
                  <span>{{scope.row.dmg_status ==='1' ? '固定' : '不固定'}}</span> 
                </el-form-item>
                <el-form-item label="是否固定日期">
                  <span>{{scope.row.is_fixed_date === '1' ? '固定' : '不固定'}}</span> 
                </el-form-item>
                <el-form-item label="延后生效天数">
                  <span>{{scope.row.delay_effective_day}}</span> 
                </el-form-item>
                <el-form-item label="有效天数">
                  <span>{{scope.row.effective_day}}</span> 
                </el-form-item>
                <el-form-item label="开始日期">
                  <span>{{scope.row.start_date}}</span> 
                </el-form-item>
                <el-form-item label="结束日期">
                  <span>{{ scope.row.end_date }}</span>
                </el-form-item>
                <el-form-item label="状态">
                  <span>{{ scope.row.is_active === '1' ? '启用' :'停用' }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            prop="company_id"
            label="公司"
            min-width="100"
            :show-overflow-tooltip="true"
            >
            <template slot-scope="scope">
              {{scope.row.company.name}}
            </template>
          </el-table-column>
          <el-table-column
            prop="user_name"
            label="创建人"
            min-width="100"
            :show-overflow-tooltip="true"
            >
            <template slot-scope="scope">
              {{scope.row.user.name}}
            </template>
          </el-table-column>
          <el-table-column
            prop="name"
            label="名称"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="amount"
            label="金额"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="count"
            label="库存总数"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="stock"
            label="剩余库存"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="effective_day"
            label="有效天数"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="start_date"
            label="开始时间"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="end_date"
            label="结束时间"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column label="操作" min-width="150">
            <template slot-scope="scope">
              <el-button size="small" type="warning" @click='linkToEdit(scope.row)'>编辑</el-button>
              <!-- <el-button size="small" type="danger" @click="deleteCoupon(scope.row)">删除</el-button> -->
            </template>
          </el-table-column>
        </el-table>
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
      </div>  
    </div>
  </div>
</template>

<script>
import coupon from 'service/coupon'
// import search from 'service/search'

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
  MessageBox
} from 'element-ui'

export default {
  data() {
    return {
      filters: {
        name: ''
      },
      companyList: [],
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
  },
  methods: {
    linkToEdit(currentCoupon) {
      this.$router.push({
        path: '/project/coupon/edit/' + currentCoupon.id
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
    deleteCoupon(currentCoupon) {
      let id = currentCoupon.id
      MessageBox.confirm('确认删除选中优惠券?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          this.setting.loadingText = '删除中'
          this.setting.loading = true
          // coupon
          //   .deleteCoupon(this, id)
          //   .then(response => {
          //     this.setting.loading = false
          //     this.$message({
          //       type: 'success',
          //       message: '删除成功！'
          //     })
          //     this.pagination.currentPage = 1
          //     this.getCouponList()
          //   })
          //   .catch(error => {
          //     this.setting.loading = false
          //     console.log(error)
          //   })
        })
        .catch(e => {
          console.log(e)
        })
    },
    addCoupon() {
      this.$router.push({
        path: '/project/coupon/add'
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
  },
  components: {
    'el-table': Table,
    'el-table-column': TableColumn,
    'el-button': Button,
    'el-input': Input,
    'el-pagination': Pagination,
    'el-form': Form,
    'el-select': Select,
    'el-option': Option,
    'el-form-item': FormItem
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
