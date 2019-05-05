<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="item-list-wrap"
    >
      <div class="item-content-wrap">
        <div class="search-wrap">
          <el-form 
            ref="searchForm" 
            :model="filters" 
            :inline="true">
            <el-form-item 
              label 
              prop="name">
              <el-input 
                v-model="filters.name" 
                placeholder="请输入优惠券名称" 
                clearable/>
            </el-form-item>
            <el-form-item 
              label 
              prop="company_id">
              <el-select
                v-model="filters.company_id"
                placeholder="请选择公司"
                filterable
                clearable
                class="item-select"
              >
                <el-option
                  v-for="item in companyList"
                  :key="item.id"
                  :label="item.name +'('+ item.internal_name+')'"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item 
              label 
              prop="scene_type">
              <el-select
                v-model="filters.scene_type"
                placeholder="请选择适用场景"
                filterable
                clearable
                class="item-select"
              >
                <el-option
                  v-for="item in sceneTypeList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item 
              label 
              prop="create_user_name">
              <el-input 
                v-model="filters.create_user_name" 
                placeholder="请输入创建人" 
                clearable/>
            </el-form-item>
            <el-button 
              type="primary" 
              size="small" 
              @click="search()">搜索</el-button>
          </el-form>
          <div>
            <el-button 
              type="default" 
              size="small" 
              @click="thirdParty">导入第三方优惠券</el-button>
          </div>
        </div>
        <div class="total-wrap">
          <span class="label">总数:{{ pagination.total }}</span>
          <div>
            <el-button 
              size="small" 
              type="success" 
              @click="addCoupon">新增</el-button>
            <el-button
              size="small"
              type="danger"
              @click="addCouponImport">批量导入</el-button>
          </div>
        </div>
        <rulesTable 
          :table-data="tableData" 
          @getCouponList="getCouponList"/>
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
    <!-- 导入第三方优惠券 -->
    <el-dialog 
      :visible.sync="templateVisible" 
      title="导入第三方优惠券" 
      @close="dialogClose">
      <el-form 
        v-loading="loading" 
        ref="templateForm" 
        :model="templateForm" 
        label-width="150px">
        <el-form-item
          :rules="[{ type: 'number', required: true, message: '请选择公司', trigger: 'submit' }]"
          label="公司"
          prop="company_id"
        >
          <el-select
            v-model="templateForm.company_id"
            placeholder="请选择公司"
            filterable
            clearable
            class="item-select"
          >
            <el-option
              v-for="item in companyList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
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
import {
  getCouponList,
  getSyncCoupon,
  saveCoupon,
  getSearchCompany
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
  Dialog,
  FormItem,
  MessageBox
} from "element-ui";
import rulesTable from "./com/rulesTable";
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
    "el-dialog": Dialog,
    rulesTable
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
        scene_type: null,
        company_id: null,
        create_user_name: null,
        name: ""
      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      status: null,
      sceneTypeList: [
        {
          id: 1,
          name: "场地通用"
        },
        {
          id: 2,
          name: "场地自营"
        },
        {
          id: 3,
          name: "商户通用"
        },
        {
          id: 4,
          name: "商户自营"
        }
      ],
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      tableData: []
    };
  },
  created() {
    this.getCouponList();
    this.getCompanyList();
  },
  methods: {
    thirdSubmit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let args = {
            company_id: this.templateForm.company_id
          };
          getSyncCoupon(this, args)
            .then(result => {
              this.getCouponList();
              this.templateVisible = false;
            })
            .catch(err => {
              this.templateVisible = false;
              console.log(err);
            });
        }
      });
    },
    getCompanyList() {
      getSearchCompany(this)
        .then(result => {
          this.companyList = result.data;
        })
        .catch(error => {
          this.$message({
            type: "warning",
            message: error.response.data.message
          });
        });
    },
    thirdParty() {
      this.loading = false;
      this.templateVisible = true;
    },
    dialogClose() {
      this.templateVisible = false;
    },
    linkToEdit(currentCoupon) {
      this.$router.push({
        path: "/prize/rules/edit/" + currentCoupon.id
      });
    },
    getCouponList() {
      this.setting.loading = true;
      let args = {
        include: "user,company,writeOffMarket",
        page: this.pagination.currentPage,
        status: this.status,
        name: this.filters.name,
        company_id: this.filters.company_id,
        create_user_name: this.filters.create_user_name,
        scene_type: this.filters.scene_type
      };
      if (!this.status) {
        delete args.status;
      }
      if (this.filters.company_id === "") {
        delete args.company_id;
      }
      if (this.filters.name === "") {
        delete args.name;
      }
      if (!this.filters.create_user_name) {
        delete args.create_user_name;
      }
      if (!this.filters.scene_type) {
        delete args.scene_type;
      }
      getCouponList(this, args)
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
    addCoupon() {
      this.$router.push({
        path: "/prize/rules/add"
      });
    },
    addCouponImport() {
      this.$router.push({
        path: "/prize/rules/import"
      });
    },
    copyRules(data) {
      this.setting.loading = true;
      let company_id = data.company.id;
      let args = {
        name: data.name,
        description: data.description,
        image_url: data.image_url,
        amount: data.amount,
        count: data.count,
        stock: data.stock,
        people_max_get: data.people_max_get,
        pmg_status: data.pmg_status,
        day_max_get: data.day_max_get,
        dmg_status: data.dmg_status,
        is_fixed_date: data.is_fixed_date,
        delay_effective_day: data.delay_effective_day,
        effective_day: data.effective_day,
        start_date: data.start_date,
        end_date: data.end_date,
        is_active: data.is_active,
        redirect_url: data.redirect_url,
        type: data.type,
        sort_order: data.sort_order,
        title: data.title,
        dynamic_stock_status: data.dynamic_stock_status,
        write_off_status: data.write_off_status,
        credit: data.credit,
        bs_image_url: data.bs_image_url
      };
      if (!args.image_url) {
        delete args.image_url;
      }
      if (args.title === "") {
        delete args.title;
      }
      if (args.redirect_url === "") {
        delete args.redirect_url;
      }
      if (!args.description) {
        delete args.description;
      }
      if (!args.end_date) {
        delete args.end_date;
      }
      if (!args.start_date) {
        delete args.start_date;
      }
      saveCoupon(this, args, "", company_id)
        .then(result => {
          this.loading = false;
          this.$message({
            message: "复制成功",
            type: "success"
          });
          this.getCouponList();
        })
        .catch(error => {
          this.loading = false;
          console.log(error);
        });
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getCouponList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getCouponList();
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
