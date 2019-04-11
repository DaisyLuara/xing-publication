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
        <el-button
          size="small"
          type="success"
          @click="addPolicy"
        >新增子策略</el-button>
      </div>
    </div>
    <!-- 子条目列表 -->
    <el-table
      :data="tableData"
      style="width: 100%"
    >
      <el-table-column type="expand">
        <template slot-scope="scope">
          <el-form
            label-position="left"
            inline
            class="demo-table-expand"
          >
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
      <el-table-column
        :show-overflow-tooltip="true"
        prop="id"
        label="ID"
        min-width="100"
      />
      <el-table-column
        :show-overflow-tooltip="true"
        prop="name"
        label="优惠券名称"
        min-width="130"
      >
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column
        :show-overflow-tooltip="true"
        prop="company"
        label="公司名称"
        min-width="130"
      >
        <template slot-scope="scope">{{ scope.row.company.name }}</template>
      </el-table-column>
      <el-table-column
        :show-overflow-tooltip="true"
        prop="rate"
        label="概率"
        min-width="100"
      >
        <template slot-scope="scope">{{ scope.row.pivot.rate }} %</template>
      </el-table-column>
      <el-table-column
        :show-overflow-tooltip="true"
        prop="updated_at"
        label="更新时间"
        min-width="120"
      />
      <el-table-column
        label="操作"
        min-width="120"
      >
        <template slot-scope="scope">
          <el-button
            size="small"
            type="warning"
            @click="editPolicy(scope.row)"
          >编辑</el-button>
          <el-button
            size="small"
            @click="deleteBatch(scope.row)"
          >删除</el-button>
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
import { getCouponPoliciesList, deleteBatchPolicy } from "service";

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
      tableData: [],
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pid: null,
      cid: null
    };
  },
  created() {
    this.pid = this.$route.query.pid;
    this.cid = this.$route.query.cid;
    this.getCouponPoliciesList();
  },
  methods: {
    deleteBatch(row) {
      let id = row.id;
      MessageBox.confirm("确认删除选中策略条目?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.setting.loadingText = "拼命加载中";
          this.setting.loading = true;
          deleteBatchPolicy(this, this.cid, id)
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
              this.$message({
                type: "success",
                message: err.response.data.message
              });
            });
        })
        .catch(e => {
          this.$message({
            type: "info",
            message: "取消删除！"
          });
        });
    },
    editPolicy(row) {
      this.$router.push({
        path: "/prize/strategy/edit/" + row.id,
        query: {
          cid: this.cid,
          pid: this.pid
        }
      });
    },
    addPolicy() {
      this.$router.push({
        path: "/prize/strategy/add",
        query: {
          cid: this.cid,
          pid: this.pid
        }
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
      if (min_score === "") {
        delete args.min_score;
      }
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
