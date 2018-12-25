<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="user-list-wrap"
  >
    <div class="user-list-content">
      <div class="search-wrap">
        <el-form :model="filters" :inline="true">
          <el-form-item label>
            <el-input
              v-model="filters.display_name"
              style="width:200px"
              placeholder="请输入角色名称"
              clearable
            />
          </el-form-item>
          <el-button type="primary" size="small" @click="search">搜索</el-button>
          <el-button type="default" size="small" @click="resetSearch">重置</el-button>
        </el-form>
      </div>
      <div class="actions-wrap">
        <span class="label">数量: {{ pagination.total }}</span>
        <el-button size="small" type="success" @click="linkToAddRole">新增角色</el-button>
      </div>
      <el-table ref="userTable" :data="tableData" highlight-current-row style="width: 100%">
        <el-table-column prop="id" label="ID" min-width="100"/>
        <el-table-column prop="display_name" label="角色名" min-width="150"/>
        <el-table-column prop="created_at" label="创建时间" min-width="150"/>
        <el-table-column label="操作" min-width="100">
          <template slot-scope="scope">
            <el-button size="small" type="danger" @click="deleteRole(scope.row)">删除</el-button>
            <el-button size="small" type="warning" @click="linkToEdit(scope.row)">修改</el-button>
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
</template>
<script>
import { getRoleList, deleteRole } from "service";
import {
  Button,
  Input,
  Table,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox
} from "element-ui";

export default {
  name: "UserList",
  components: {
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem
  },
  data() {
    return {
      tableData: [],
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      filters: {
        display_name: ""
      },
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      }
    };
  },
  created() {
    this.getRoleList();
  },
  methods: {
    linkToEdit(data) {
      this.$router.push({
        path: "/system/role/edit/" + data.id
      });
    },
    getRoleList() {
      this.setting.loading = true;
      let pageNum = this.pagination.currentPage;
      let args = {
        page: pageNum,
        display_name: this.filters.display_name
      };
      if (this.filters.display_name === "") {
        delete args.display_name;
      }
      return getRoleList(this, args)
        .then(response => {
          this.setting.loading = false;
          this.tableData = response.data;
          this.pagination.total = response.meta.pagination.total;
        })
        .catch(error => {
          this.setting.loading = false;
        });
    },
    deleteRole(data) {
      let id = data.id;
      MessageBox.confirm("确认删除选中角色?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.setting.loading = true;
          deleteRole(this, id)
            .then(response => {
              this.setting.loading = false;
              this.$message({
                type: "success",
                message: "删除成功！"
              });
              this.pagination.currentPage = 1;
              this.getRoleList();
            })
            .catch(error => {
              this.setting.loading = false;
              this.$message({
                type: "warning",
                message: error.response.data.message
              });
            });
        })
        .catch(e => {
          console.log(e);
        });
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getRoleList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getRoleList();
    },
    resetSearch() {
      this.filters.name = "";
      this.pagination.currentPage = 1;
      this.getRoleList();
    },
    linkToAddRole() {
      this.$router.push({
        path: "/system/role/add"
      });
    }
  }
};
</script>

<style lang="less" scoped>
.user-list-wrap {
  h1 {
    text-align: center;
  }
}
.user-list-content {
  .photo_img {
    width: 100%;
    padding: 5px;
  }
  .actions-wrap {
    margin-top: 5px;
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
