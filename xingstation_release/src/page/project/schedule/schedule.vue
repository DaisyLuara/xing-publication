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
        <el-button size="small" type="success" @click="addPolicy">新增排期</el-button>
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
            <el-form-item label="节目名称:">
              <span>{{ scope.row.project.name }}</span>
            </el-form-item>
            <el-form-item label="节目图标:">
              <span>
                <img :src="scope.row.project.icon" style="width:20%;padding:10px">
              </span>
            </el-form-item>
            <el-form-item label="皮肤名称:">
              <span>{{ scope.row.skin ? scope.row.skin.name:'' }}</span>
            </el-form-item>
            <el-form-item label="皮肤图标:">
              <span>
                <img :src="scope.row.skin.icon" style="width:40%;padding:10px">
              </span>
            </el-form-item>
            <el-form-item label="开始时间:">
              <span>{{ scope.row.date_start }}</span>
            </el-form-item>
            <el-form-item label="结束时间:">
              <span>{{ scope.row.date_end }}</span>
            </el-form-item>
          </el-form>
        </template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="id" label="ID" min-width="100"/>
      <el-table-column :show-overflow-tooltip="true" prop="name" label="节目名称" min-width="130">
        <template slot-scope="scope">{{ scope.row.project.name }}</template>
      </el-table-column>
      <el-table-column prop="project_icon" label="节目图标" width="130">
        <template slot-scope="scope">
          <img :src="scope.row.project.icon" style="width:100%">
        </template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="name" label="皮肤名称" min-width="130">
        <template slot-scope="scope">{{ scope.row.skin? scope.row.skin.name:'' }}</template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="skin_icon" label="皮肤图标" width="130">
        <template slot-scope="scope">
          <img :src="scope.row.skin.icon" style="width:100%">
        </template>
      </el-table-column>
      <el-table-column
        :show-overflow-tooltip="true"
        prop="date_start"
        label="开始时间"
        min-width="100"
      />
      <el-table-column :show-overflow-tooltip="true" prop="date_end" label="结束时间" min-width="100"/>
      <el-table-column label="操作" min-width="120">
        <template slot-scope="scope">
          <el-button size="small" type="warning" @click="editPolicy(scope.row)">编辑</el-button>
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
import { getScheduleList } from "service";

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
      pid: null
    };
  },
  created() {
    this.pid = this.$route.query.pid;
    this.getScheduleList();
  },
  methods: {
    editPolicy(row) {
      this.$router.push({
        path: "/project/template/edit/" + row.id,
        query: {
          pid: this.pid
        }
      });
    },
    addPolicy() {
      this.$router.push({
        path: "/project/template/add",
        query: {
          pid: this.pid
        }
      });
    },
    getScheduleList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage
      };
      return getScheduleList(this, this.pid, args)
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

    search() {
      this.pagination.currentPage = 1;
      this.getScheduleList();
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getScheduleList();
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
