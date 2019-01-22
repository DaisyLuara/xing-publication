<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="item-list-wrap"
    >
      <div class="item-content-wrap">
        <div class="search-wrap">
          <el-form ref="filters" :model="filters" :inline="true">
            <el-form-item label prop="coupon_code">
              <el-input v-model="filters.coupon_code" placeholder="优惠券code" clearable/>
            </el-form-item>
            <el-form-item label prop="coupon_batch_id">
              <el-select v-model="filters.coupon_batch_id" clearable placeholder="请选择优惠券">
                <el-option
                  v-for="item in couponList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-button type="primary" size="small" @click="search()">搜索</el-button>
            <el-button type="default" size="small" @click="resetSearch('filters')">重置</el-button>
          </el-form>
        </div>
        <div class="total-wrap">
          <span class="label">总数:{{ pagination.total }}</span>
        </div>
        <el-table :data="tableData" style="width: 100%">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="ID">
                  <span>{{ scope.row.id }}</span>
                </el-form-item>
                <el-form-item label="优惠券code">
                  <span>{{ scope.row.coupon_code }}</span>
                </el-form-item>
                <el-form-item label="优惠券">
                  <span>{{ scope.row.couponBatch ? scope.row.couponBatch.name:'' }}</span>
                </el-form-item>
                <el-form-item label="流水账号">
                  <span>{{ scope.row.mch_billno }}</span>
                </el-form-item>
                <el-form-item label="openID">
                  <span>{{ scope.row.re_openid }}</span>
                </el-form-item>
                <el-form-item label="金额">{{ scope.row.total_amount/100 }}</el-form-item>
                <el-form-item label="交易状态">{{ scope.row.return_code === 'SUCCESS'? '成功': '失败' }}</el-form-item>
                <el-form-item label="交易时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="id" label="ID" min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="coupon_code"
            label="优惠券code"
            min-width="100"
          />
          <el-table-column prop="icon" label="优惠券" min-width="130">
            <template slot-scope="scope">{{ scope.row.couponBatch ? scope.row.couponBatch.name:'' }}</template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="mch_billno" label="流水账号"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="re_openid"
            label="openID"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="total_amount"
            label="金额"
            min-width="100"
          >
            <template slot-scope="scope">{{ scope.row.total_amount/100 }}</template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="pass" label="交易状态" min-width="100">
            <template slot-scope="scope">{{ scope.row.return_code === 'SUCCESS'? '成功': '失败' }}</template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="created_at"
            label="获得时间"
            min-width="150"
          />
          <el-table-column label="操作" min-width="100">
            <template slot-scope="scope">
              <el-button
                v-if="scope.row.return_code === 'FAIL'"
                size="small"
                type="warning"
                @change="handleRetry(scope.row)"
              >重发</el-button>
            </template>
          </el-table-column>
        </el-table>
        <div class="pagination-wrap">
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
import { getSearchCouponList, getActivityBillList } from "service";

import {
  Button,
  Input,
  Table,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox,
  Select,
  Option
} from "element-ui";

export default {
  components: {
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-select": Select,
    "el-option": Option
  },
  data() {
    return {
      searchLoading: false,
      templateVisible: false,
      couponList: [],
      filters: {
        coupon_code: "",
        coupon_batch_id: ""
      },
      templateForm: {
        policy_id: null,
        ids: []
      },
      policyList: [],
      statusList: [
        {
          id: 0,
          name: "未提交"
        },
        {
          id: 1,
          name: "已参与"
        },
        {
          id: 2,
          name: "失效"
        }
      ],
      loading: false,
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      tableData: []
    };
  },
  created() {
    this.getSearchCouponList();
    this.getActivityBillList();
  },
  methods: {
    handleRetry(data) {},
    getSearchCouponList() {
      this.searchLoading = true;
      getSearchCouponList(this)
        .then(result => {
          this.couponList = result.data;
          this.searchLoading = false;
        })
        .catch(err => {
          this.searchLoading = false;
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    getActivityBillList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        include: "couponBatch",
        coupon_code: this.filters.coupon_code,
        coupon_batch_id: this.filters.coupon_batch_id
      };
      if (this.filters.coupon_code === "") {
        delete args.coupon_code;
      }
      if (this.filters.coupon_batch_id === "") {
        delete args.coupon_batch_id;
      }
      getActivityBillList(this, args)
        .then(res => {
          this.tableData = res.data;
          this.pagination.total = res.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          this.setting.loading = false;
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getActivityBillList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.tableData = [];
      this.getActivityBillList();
    },
    resetSearch(formName) {
      this.$refs[formName].resetFields();
      this.pagination.currentPage = 1;
      this.getActivityBillList();
    }
  }
};
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
        // padding: 10px;
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
          width: 250px;
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
