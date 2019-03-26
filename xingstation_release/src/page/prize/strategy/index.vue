<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="schedule-wrap"
  >
    <!-- 搜索 -->
    <div class="search-wrap">
      <el-form ref="searchForm" :model="searchForm" :inline="true">
        <el-form-item label prop="name">
          <el-input v-model="searchForm.name" placeholder="请输入模板名称" clearable class="item-input"/>
        </el-form-item>
        <el-button type="primary" size="small" @click="search">搜索</el-button>
      </el-form>
    </div>
    <div class="actions-wrap">
      <span class="label">数量: {{ pagination.total }}</span>
      <!-- 模板增加 -->
      <div>
        <el-button size="small" type="success" @click="addTemplate('templateForm')">新增策略</el-button>
      </div>
    </div>
    <!-- 模板排期列表 -->
    <el-table :data="tableData" style="width: 100%">
      <el-table-column type="expand">
        <template slot-scope="scope">
          <el-form label-position="left" inline class="demo-table-expand">
            <el-form-item label="ID:">
              <span>{{ scope.row.id }}</span>
            </el-form-item>
            <el-form-item label="策略名称:">
              <span>{{ scope.row.name }}</span>
            </el-form-item>
            <el-form-item label="公司名称:">
              <span>{{ scope.row.company.name }}</span>
            </el-form-item>
            <el-form-item label="更新时间:">
              <span>{{ scope.row.updated_at }}</span>
            </el-form-item>
          </el-form>
        </template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="id" label="ID" min-width="100"/>
      <el-table-column :show-overflow-tooltip="true" prop="name" label="策略名称" min-width="130">
        <template slot-scope="scope">{{scope.row.name}}</template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="company" label="公司名称" min-width="130">
        <template slot-scope="scope">{{scope.row.company.name}}</template>
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
          <el-button size="small" @click="policy(scope.row)">子条目</el-button>
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
    <!-- 新增，修改 -->
    <el-dialog :title="title" :visible.sync="templateVisible" @close="dialogClose">
      <el-form v-loading="loading" ref="templateForm" :model="templateForm" label-width="150px">
        <el-form-item
          :rules="[{ type: 'number', required: true, message: '请选择公司', trigger: 'submit' }]"
          label="公司"
          prop="company_id"
        >
          <el-select
            v-model="templateForm.company_id"
            :loading="searchLoading"
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
        <el-form-item
          :rules="[{ type: 'string', required: true, message: '请输入名称', trigger: 'submit' }]"
          label="策略名"
          prop="name"
        >
          <el-input v-model="templateForm.name" placeholder="请输入名称" class="item-input"/>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" size="small" @click="submit('templateForm')">完成</el-button>
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
  Pagination,
  Table,
  TableColumn,
  Dialog,
  MessageBox,
  Input
} from "element-ui";
import { modifyPolicy, savePolicy, getPoliciesList } from "service";

export default {
  components: {
    ElDialog: Dialog,
    ElPagination: Pagination,
    ElInput: Input,
    ElForm: Form,
    ElFormItem: FormItem,
    ElButton: Button,
    ElTable: Table,
    ElTableColumn: TableColumn
  },
  data() {
    return {
      templateVisible: false,
      loading: false,
      title: "",
      templateForm: {
        company_id: "",
        name: "",
        id: ""
      },
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
      companyList: [],
      searchForm: {
        name: ""
      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      }
    };
  },
  created() {
    this.getPoliciesList();
  },
  methods: {
    policy(row) {
      this.$router.push({
        path: "/prize/strategy/policy",
        query: {
          pid: row.id,
          cid: row.company.id
        }
      });
    },
    modifyTemplateName(item) {
      this.title = "修改策略";
      let name = item.name;
      let company_id = item.company.id;
      let id = item.id;
      this.templateForm = {
        name: name,
        id: id,
        company_id: company_id
      };
      this.templateVisible = true;
    },
    addTemplate() {
      this.templateForm.name = "";
      this.templateForm.company_id = "";
      this.templateVisible = true;
      this.title = "增加策略";
    },
    getPoliciesList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        include: "company",
        name: this.searchForm.name
      };
      if (!this.searchForm.name) {
        delete args.name;
      }
      return getPoliciesList(this, args)
        .then(response => {
          this.tableData = response.data;
          this.pagination.total = response.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          console.log(err);
          this.setting.loading = false;
        });
    },
    dialogClose() {
      this.templateVisible = false;
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let company_id = this.templateForm.company_id;
          let args = {
            name: this.templateForm.name
          };
          if (this.title !== "增加策略") {
            let id = this.templateForm.id;
            modifyPolicy(this, id, args)
              .then(response => {
                this.$message({
                  message: "修改成功",
                  type: "success"
                });
                this.templateVisible = false;
                this.getPoliciesList();
              })
              .catch(err => {
                this.templateVisible = false;
                console.log(err);
              });
          } else {
            savePolicy(this, company_id, args)
              .then(response => {
                this.$message({
                  message: "添加成功",
                  type: "success"
                });
                this.templateVisible = false;
                this.getPoliciesList();
              })
              .catch(err => {
                this.templateVisible = false;
                console.log(err);
              });
          }
        }
      });
    },
    search() {
      this.pagination.currentPage = 1;
      this.getPoliciesList();
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getPoliciesList();
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
