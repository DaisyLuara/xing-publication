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
            <el-form-item label prop="coupon_batch_id">
              <el-select
                v-model="filters.coupon_batch_id"
                :loading="searchLoading"
                :remote-method="getCouponQuery"
                :multiple-limit="1"
                multiple
                placeholder="请选择优惠规则"
                filterable
                remote
                clearable
              >
                <el-option
                  v-for="item in couponList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item label prop="status">
              <el-select
                v-model="filters.status"
                placeholder="请选择优惠券状态"
                clearable
                class="coupon-form-select"
              >
                <el-option
                  v-for="item in statusList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item label prop="company_id">
              <el-select
                v-model="filters.company_id"
                placeholder="请选择公司"
                filterable
                clearable
                class="item-select"
                @change="handleCompany"
              >
                <el-option
                  v-for="item in companyList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item label prop="shop_customer_id">
              <el-select
                v-model="filters.shop_customer_id"
                placeholder="请选择核销人"
                filterable
                clearable
                class="item-select"
              >
                <el-option
                  v-for="item in shopCustomerList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item label prop="dataValue">
              <el-date-picker
                v-model="filters.dataValue"
                :clearable="false"
                :picker-options="pickerOptions2"
                type="datetimerange"
                start-placeholder="开始日期"
                end-placeholder="结束日期"
                align="right"
              ></el-date-picker>
            </el-form-item>
            <el-form-item label prop>
              <el-button type="primary" size="small" @click="search()">搜索</el-button>
              <el-button type="default" size="small" @click="resetSearch('filters')">重置</el-button>
            </el-form-item>
          </el-form>
        </div>
        <div class="total-wrap">
          <span class="label">总数:{{ pagination.total }}</span>
          <div>
            <el-button type="success" size="small" @click="exportList">导出</el-button>
          </div>
        </div>
        <el-table :data="tableData" style="width: 100%">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="优惠券编码">
                  <span>{{ scope.row.code }}</span>
                </el-form-item>
                <el-form-item label="优惠券名称">
                  <span>{{ scope.row.name }}</span>
                </el-form-item>
                <el-form-item label="优惠券状态">
                  <span v-if="scope.row.status===0">未领取</span>
                  <span v-if="scope.row.status===1">已使用</span>
                  <span v-if="scope.row.status===2">停用</span>
                  <span v-if="scope.row.status===3">未使用</span>
                </el-form-item>
                <el-form-item label="手机号">
                  <span>{{ scope.row.mobile }}</span>
                </el-form-item>
                <el-form-item label="微信ID">
                  <span>{{ scope.row.wx_user_id }}</span>
                </el-form-item>
                <el-form-item label="淘宝ID">
                  <span>{{ scope.row.taobao_user_id }}</span>
                </el-form-item>
                <el-form-item label="创建时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
                <el-form-item label="核销时间">
                  <span>{{ scope.row.use_date }}</span>
                </el-form-item>
                <el-form-item label="有效期">
                  <span>{{ scope.row.effect_start_date ? (scope.row.effect_start_date +' -- '+ scope.row.effect_end_date) : '' }}</span>
                </el-form-item>
                <el-form-item label="公司">
                  <span>{{ scope.row.couponBatch.company.name }}</span>
                </el-form-item>
                <el-form-item label="点位">
                  <span>{{ scope.row.point.id !== 0 ? scope.row.point.market.area.name + '-' + scope.row.point.market.name + '-' + scope.row.point.name : '' }}</span>
                </el-form-item>
                <el-form-item label="核销人">
                  <span>{{ scope.row.customer !== undefined ? scope.row.customer.name : '' }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="code" label="优惠券编码" min-width="100"/>
          <el-table-column :show-overflow-tooltip="true" prop="name" label="优惠券名称" min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="status"
            label="优惠券状态"
            min-width="100"
          >
            <template slot-scope="scope">
              <span v-if="scope.row.status===0">未领取</span>
              <span v-if="scope.row.status===1">已使用</span>
              <span v-if="scope.row.status===2">停用</span>
              <span v-if="scope.row.status===3">未使用</span>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="created_at"
            label="创建时间"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="use_date"
            label="核销时间"
            min-width="100"
          />
          <el-table-column :show-overflow-tooltip="true" prop label="公司" min-width="100">
            <template slot-scope="scope">{{ scope.row.couponBatch.company.name }}</template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop label="核销人" min-width="100">
            <template
              slot-scope="scope"
            >{{ scope.row.customer !==undefined ? scope.row.customer.name : '' }}</template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop label="点位" min-width="100">
            <template
              slot-scope="scope"
            >{{ scope.row.point.id !== 0 ? scope.row.point.market.area.name + '-' + scope.row.point.market.name + '-' + scope.row.point.name : '' }}</template>
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
import {
  handleDateTimeTransform,
  putInCouponList,
  getSearchShopCustomerList,
  getSearchCompanyList,
  getSearchCouponList,
  getExcelData
} from "service";
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
  DatePicker
} from "element-ui";

export default {
  components: {
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-select": Select,
    "el-option": Option,
    "el-form-item": FormItem,
    "el-date-picker": DatePicker
  },
  data() {
    return {
      loading: true,
      templateVisible: false,
      filters: {
        coupon_batch_id: "",
        status: "",
        company_id: "",
        dataValue: [],
        shop_customer_id: ""
      },
      pickerOptions2: {
        shortcuts: [
          {
            text: "今天",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "昨天",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24);
              end.setTime(end.getTime() - 3600 * 1000 * 24);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "最近一周",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "最近一个月",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "最近三个月",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit("pick", [start, end]);
            }
          }
        ]
      },
      shopCustomerList: [],
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      statusList: [
        {
          id: 0,
          name: "未领取"
        },
        {
          id: 1,
          name: "已使用"
        },
        {
          id: 2,
          name: "停用"
        },
        {
          id: 3,
          name: "未使用"
        }
      ],
      companyList: [],
      tableData: [],
      couponList: [],
      searchLoading: false
    };
  },
  created() {
    this.putInCouponList();
    this.getCompanyList();
  },
  methods: {
    exportList() {
      let args = this.setArgs();
      args.type = "coupon";
      delete args.page;
      getExcelData(this, args)
        .then(response => {
          const a = document.createElement("a");
          a.href = response;
          a.download = "download";
          a.click();
          window.URL.revokeObjectURL(response);
        })
        .catch(err => {
          console.log(err);
        });
    },
    resetSearch(formName) {
      this.$refs[formName].resetFields();
      this.pagination.currentPage = 1;
      this.putInCouponList();
    },
    handleCompany() {
      this.filters.shop_customer_id = "";
      this.getShopCustomerList();
    },
    getShopCustomerList() {
      this.searchLoading = true;
      let args = {
        company_id: this.filters.company_id
      };
      return getSearchShopCustomerList(this, args)
        .then(res => {
          this.shopCustomerList = res.data;
          this.searchLoading = false;
        })
        .catch(err => {
          this.searchLoading = false;

          console.log(err);
        });
    },
    getCompanyList() {
      return getSearchCompanyList(this)
        .then(result => {
          this.companyList = result.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getCouponQuery(query) {
      if (query !== "") {
        this.searchLoading = true;
        let args = {
          name: query
        };
        return getSearchCouponList(this, args)
          .then(response => {
            this.couponList = response.data;
            this.searchLoading = false;
          })
          .catch(err => {
            this.searchLoading = false;
          });
      } else {
        this.marketList = [];
      }
    },
    setArgs() {
      let args = {
        include: "couponBatch.company,point.market.area,customer",
        page: this.pagination.currentPage,
        coupon_batch_id: this.filters.coupon_batch_id[0],
        status: this.filters.status,
        company_id: this.filters.company_id,
        shop_customer_id: this.filters.shop_customer_id
      };
      if (this.filters.coupon_batch_id.length === 0) {
        delete args.coupon_batch_id;
      }
      if (this.filters.status === "") {
        delete args.status;
      }
      if (this.filters.company_id === "") {
        delete args.company_id;
      }
      if (this.filters.shop_customer_id === "") {
        delete args.shop_customer_id;
      }
      if (this.filters.dataValue) {
        if (this.filters.dataValue.length !== 0) {
          if (this.filters.dataValue[0]) {
            args.start_date = handleDateTimeTransform(
              this.filters.dataValue[0]
            );
          }
          if (this.filters.dataValue[1]) {
            args.end_date = handleDateTimeTransform(this.filters.dataValue[1]);
          }
        }
      }
      return args;
    },
    putInCouponList() {
      this.setting.loading = true;
      let args = this.setArgs();
      // let args = {
      //   include: "couponBatch.company,point.market.area,customer",
      //   page: this.pagination.currentPage,
      //   coupon_batch_id: this.filters.coupon_batch_id[0],
      //   status: this.filters.status,
      //   company_id: this.filters.company_id,
      //   shop_customer_id: this.filters.shop_customer_id
      // };
      // if (this.filters.coupon_batch_id.length === 0) {
      //   delete args.coupon_batch_id;
      // }
      // if (this.filters.status === "") {
      //   delete args.status;
      // }
      // if (this.filters.company_id === "") {
      //   delete args.company_id;
      // }
      // if (this.filters.shop_customer_id === "") {
      //   delete args.shop_customer_id;
      // }
      // if (this.filters.dataValue) {
      //   if (this.filters.dataValue.length !== 0) {
      //     if (this.filters.dataValue[0]) {
      //       args.start_date = handleDateTimeTransform(
      //         this.filters.dataValue[0]
      //       );
      //     }
      //     if (this.filters.dataValue[1]) {
      //       args.end_date = handleDateTimeTransform(this.filters.dataValue[1]);
      //     }
      //   }
      // }
      putInCouponList(this, args)
        .then(response => {
          let data = response.data;
          this.tableData = data;
          this.pagination.total = response.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(error => {
          console.log(error);
          this.setting.loading = false;
        });
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.putInCouponList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.putInCouponList();
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
      margin-bottom: 15px;
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
