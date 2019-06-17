<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="schedule-wrap"
  >
    <!-- 搜索 -->
    <div class="search-wrap">
      <el-form 
        ref="searchForm" 
        :model="searchForm" 
        :inline="true">
        <el-form-item 
          label 
          prop="name">
          <el-input 
            v-model="searchForm.name" 
            placeholder="请输入模板名称" 
            clearable 
            class="item-input"/>
        </el-form-item>
        <el-button 
          type="primary" 
          size="small" 
          @click="search">搜索</el-button>
      </el-form>
    </div>
    <div class="actions-wrap">
      <span class="label">数量: {{ pagination.total }}</span>
      <!-- 模板增加 -->
      <div>
        <el-button 
          size="small" 
          type="success" 
          @click="addTemplate">新增模板</el-button>
      </div>
    </div>
    <!-- 模板排期列表 -->
    <el-table 
      :data="tableData" 
      style="width: 100%">
      <el-table-column 
        :show-overflow-tooltip="true" 
        prop="id" 
        label="ID" 
        min-width="100"/>
      <el-table-column 
        :show-overflow-tooltip="true" 
        prop="name" 
        label="模版名称" 
        min-width="130"/>
      <el-table-column 
        label="操作" 
        min-width="100">
        <template slot-scope="scope">
          <el-button 
            size="small" 
            type="warning" 
            @click="modifyTemplateName(scope.row)">编辑</el-button>
          <el-button 
            size="small" 
            @click="schedule(scope.row)">子条目</el-button>
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
    <el-dialog 
      :title="title" 
      :visible.sync="templateVisible" 
      @close="templateVisible = false">
      <el-form 
        v-loading="loading" 
        ref="templateForm" 
        :model="templateForm" 
        label-width="150px">
        <el-form-item
          :rules="[{ type: 'string', required: true, message: '请输入名称', trigger: 'submit' }]"
          label="模板名"
          prop="name"
        >
          <el-input 
            v-model="templateForm.name" 
            placeholder="请输入名称" 
            class="item-input"/>
        </el-form-item>
        <el-form-item>
          <el-button 
            type="primary" 
            size="small" 
            @click="submit('templateForm')">完成</el-button>
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
import { getTemplateList, saveTemplate, modifyTemplate } from "service";

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
        tpl_id: "",
        name: ""
      },
      tableData: [],
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
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
    this.getTemplateList();
  },
  methods: {
    modifyTemplateName(item) {
      this.loading = false;
      this.title = "修改模板";
      this.templateForm = {
        tpl_id: item.id,
        name: item.name
      };
      this.templateVisible = true;
    },
    schedule(data) {
      this.$router.push({
        path: "/project/template/schedule",
        query: {
          pid: data.id
        }
      });
    },
    addTemplate(formName) {
      this.templateForm.tpl_id = "";
      this.templateForm.name = "";
      this.templateVisible = true;
      this.title = "增加模板";
    },
    getTemplateList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        name: this.searchForm.name
      };
      if (this.searchForm.name === "") {
        delete args.name;
      }
      getTemplateList(this, args)
        .then(response => {
          this.tableData = response.data;
          this.pagination.total = response.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          this.setting.loading = false;
        });
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let args = {
            point_id: this.templateForm.point_id,
            name: this.templateForm.name
          };
          if (this.templateForm.tpl_id) {
            modifyTemplate(this, this.templateForm.tpl_id, args)
              .then(response => {
                this.successHandle('修改成功')
              })
              .catch(err => {
                this.templateVisible = false;
              });
          } else {
            saveTemplate(this, args)
              .then(response => {
                this.successHandle('添加成功')
              })
              .catch(err => {
                this.templateVisible = false;
              });
          }
        }
      });
    },
    successHandle(message) {
      this.$message({
        message: message,
        type: "success"
      });
      this.templateVisible = false;
      this.getTemplateList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getTemplateList();
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getTemplateList();
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
