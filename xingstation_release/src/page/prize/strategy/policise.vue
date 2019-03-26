<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="schedule-wrap"
  >
    <div class="actions-wrap">
      <span class="label">数量: {{ pagination.total }}</span>
      <!-- 新增子策略 -->
      <div>
        <el-button size="small" type="success" @click="addPolicy">新增子策略</el-button>
      </div>
    </div>
    <!-- 子条目列表 -->
    <el-table :data="tableData" style="width: 100%">
      <el-table-column type="expand">
        <template slot-scope="scope">
          <el-form label-position="left" inline class="demo-table-expand">
            <el-form-item label="ID:">
              <span>{{ scope.row.id }}</span>
            </el-form-item>
            <el-form-item label="优惠券名称:">
              <span>{{ scope.row.name }}</span>
            </el-form-item>
            <el-form-item label="公司名称:">
              <span>{{ scope.row.company.name }}</span>
            </el-form-item>
            <el-form-item label="概率:">
              <span>{{ scope.row.pivot ? scope.row.pivot.rate:'' }}</span>
            </el-form-item>
            <el-form-item label="更新时间:">
              <span>{{ scope.row.updated_at }}</span>
            </el-form-item>
          </el-form>
        </template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="id" label="ID" min-width="100"/>
      <el-table-column :show-overflow-tooltip="true" prop="name" label="优惠券名称" min-width="130">
        <template slot-scope="scope">{{scope.row.name}}</template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="company" label="公司名称" min-width="130">
        <template slot-scope="scope">{{scope.row.company.name}}</template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="rate" label="概率" min-width="130">
        <template slot-scope="scope">{{scope.row.pivot.rate}} %</template>
      </el-table-column>
      <el-table-column
        :show-overflow-tooltip="true"
        prop="updated_at"
        label="更新时间"
        min-width="100"
      />
      <el-table-column label="操作" min-width="100">
        <template slot-scope="scope">
          <el-button size="small" type="warning" @click="modifyTemplateName(scope.row)">编辑</el-button>
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
</template>
<script>
import {
  Form,
  FormItem,
  Button,
  Select,
  Option,
  Pagination,
  Table,
  TableColumn,
  TimeSelect,
  MessageBox,
  Input
} from "element-ui";
import { getCouponPoliciesList } from "service";

export default {
  components: {
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
      genderList: [
        {
          id: 1,
          name: "女"
        },
        {
          id: 0,
          name: "男"
        }
      ],
      tableData: [
        {
          id: 85,
          name: "19美陈展—谢谢惠顾",
          description: "",
          image_url: "",
          bs_image_url: "",
          amount: 0,
          count: 10000,
          stock: 10000,
          people_max_get: 1,
          pmg_status: 0,
          day_max_get: 1,
          dmg_status: 0,
          is_fixed_date: 1,
          delay_effective_day: 0,
          effective_day: 0,
          start_date: "2019-03-23 00:00:00",
          end_date: "2019-04-30 23:59:59",
          is_active: 1,
          third_code: "",
          pivot: {
            policy_id: 11,
            coupon_batch_id: 85,
            rate: 20,
            min_age: 0,
            max_age: 0,
            max_score: 0,
            min_score: 0,
            gender: 0,
            type: "rate",
            id: 47
          },
          wx_user_id: null,
          type: 1,
          redirect_url: "",
          title: "",
          sort_order: 1,
          dynamic_stock_status: 0,
          write_off_status: 1,
          credit: 0,
          scene_type: 2,
          updated_at: "2019-03-23 15:10:07",
          company: {
            id: 11,
            name: "星视度",
            internal_name: null,
            address: "上海市浦东新区1118号",
            category: 0,
            status: 1,
            description: null,
            logo: "",
            created_at: "2018-09-05 11:10:10",
            updated_at: "2018-09-05 11:10:10"
          }
        }
      ],
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pid: null
    };
  },
  created() {
    this.pid = this.$route.query.pid;
    // this.getCompanyList();
    // this.getCouponPoliciesList();
  },
  methods: {
    addPolicy() {
      this.$router.push({
        path: "/prize/strategy/add"
      });
    },
    getCouponList(company_id) {
      let args = {
        company_id: company_id
      };
      getSearchCouponList(this, args)
        .then(result => {
          this.couponList = result.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    getCompanyList() {
      getSearchCompany(this)
        .then(result => {
          this.companyList = result.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    deleteBatch(row) {
      let id = row.id;
      let company_id = row.pivot.policy_id;
      MessageBox.confirm("确认删除选中策略条目?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.setting.loadingText = "拼命加载中";
          this.setting.loading = true;
          deleteBatchPolicy(this, company_id, id)
            .then(response => {
              this.setting.loading = false;
              this.$message({
                type: "success",
                message: "删除成功！"
              });
              this.pagination.currentPage = 1;
              this.getCouponPoliciesList();
            })
            .catch(error => {
              this.setting.loading = false;
              console.log(error);
            });
        })
        .catch(e => {
          console.log(e);
        });
    },
    editBatch(row) {
      let id = row.id;
      let policy_id = row.pivot.policy_id;
      let max_age = row.pivot.max_age;
      let min_age = row.pivot.min_age;
      let max_score = row.pivot.max_score;
      let min_score = row.pivot.min_score;
      let gender = row.pivot.gender;
      let rate = row.pivot.rate;
      let coupon_batch_id = row.pivot.coupon_batch_id;
      if (max_age === "" && min_age === "" && gender === "" && rate === "") {
        this.$message({
          message: "概率，性别，最大年龄，最小年龄不能都为空",
          type: "warning"
        });
        return;
      }
      if (
        (max_age !== "" && min_age === "") ||
        (max_age === "" && min_age !== "")
      ) {
        this.$message({
          message: "最大年龄，最小年龄必须都填写",
          type: "warning"
        });
        return;
      }
      this.setting.loading = true;
      let args = {
        min_age: parseInt(min_age),
        max_age: parseInt(max_age),
        gender: parseInt(gender),
        max_score: parseFloat(max_score),
        min_score: parseFloat(min_score),
        rate: rate,
        coupon_batch_id: coupon_batch_id
      };
      if (!min_age) {
        delete args.min_age;
      }
      if (!max_age) {
        delete args.max_age;
      }
      if (!rate) {
        delete args.rate;
      }
      if (max_score === "") {
        delete args.max_score;
      }
      if (min_score === "") {
        delete args.min_score;
      }
      modifyBatchPolicy(this, policy_id, args, id)
        .then(response => {
          this.$message({
            message: "修改成功",
            type: "success"
          });
          this.getCouponPoliciesList();
          this.setting.loading = false;
        })
        .catch(err => {
          console.log(err);
          this.getCouponPoliciesList();
          this.setting.loading = false;
        });
    },
    saveBatch(row) {
      let policy_id = row.pivot.policy_id;
      let max_age = row.pivot.max_age;
      let min_age = row.pivot.min_age;
      let gender = row.pivot.gender;
      let rate = row.pivot.rate;
      let min_score = row.pivot.min_score;
      let max_score = row.pivot.max_score;
      let coupon_batch_id = row.pivot.coupon_batch_id;
      if (max_age === "" && min_age === "" && gender === "" && rate === "") {
        this.$message({
          message: "概率，性别，最大年龄，最小年龄不能都为空",
          type: "warning"
        });
        return;
      }
      if (coupon_batch_id === "") {
        this.$message({
          message: "优惠券必须填写",
          type: "warning"
        });
        return;
      }
      if (
        (max_age !== "" && min_age === "") ||
        (max_age === "" && min_age !== "")
      ) {
        this.$message({
          message: "最大年龄，最小年龄必须都填写",
          type: "warning"
        });
        return;
      }
      this.setting.loading = true;
      let args = {
        min_age: parseInt(min_age),
        max_age: parseInt(max_age),
        gender: parseInt(gender),
        rate: rate,
        max_score: parseFloat(max_score),
        min_score: parseFloat(min_score),
        coupon_batch_id: coupon_batch_id
      };
      if (!min_age) {
        delete args.min_age;
      }
      if (!max_age) {
        delete args.max_age;
      }
      if (!rate) {
        delete args.rate;
      }
      if (max_score === "") {
        delete args.max_score;
      }
      if (min_score === "") {
        delete args.min_score;
      }

      saveBatchPolicy(this, policy_id, args)
        .then(response => {
          this.$message({
            message: "添加成功",
            type: "success"
          });
          this.getCouponPoliciesList();
          this.setting.loading = false;
        })
        .catch(err => {
          console.log(err);
          this.getCouponPoliciesList();
          this.setting.loading = false;
        });
    },
    getCouponPoliciesList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        include: "company"
      };
      return getCouponPoliciesList(this, this.pid, args)
        .then(response => {
          this.activeNames = 0;
          this.tableData = response.data;
          this.pagination.total = response.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          console.log(err);
          this.setting.loading = false;
        });
    },

    search() {
      this.pagination.currentPage = 1;
      this.getCouponPoliciesList();
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getCouponPoliciesList();
    }
  }
};
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
