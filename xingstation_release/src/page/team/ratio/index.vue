<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="program-list-wrap"
    >
      <div class="program-content-wrap">
        <div class="total-wrap">
          <div>
            <span class="label">智造比例列表</span>
          </div>
        </div>
        <el-table 
          :data="tableData" 
          style="width: 100%">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form 
                label-position="left" 
                inline 
                class="demo-table-expand">
                <el-form-item label="交互技术-中间件">
                  <span>{{ scope.row.interaction_api }}</span>
                </el-form-item>
                <el-form-item label="交互技术-交互引擎">
                  <span>{{ scope.row.interaction_linkage }}</span>
                </el-form-item>
                <el-form-item label="H5基础">
                  <span>{{ scope.row.h5_1 }}</span>
                </el-form-item>
                <el-form-item label="H5复杂">
                  <span>{{ scope.row.h5_2 }}</span>
                </el-form-item>
                <el-form-item label="运营基本">
                  <span>{{ scope.row.operation }}</span>
                </el-form-item>
                <el-form-item label="运营验收">
                  <span>{{ scope.row.operation_quality }}</span>
                </el-form-item>
                <el-form-item label="节目创意">
                  <span>{{ scope.row.originality }}</span>
                </el-form-item>
                <el-form-item label="Hidol专利">
                  <span>{{ scope.row.hidol_patent }}</span>
                </el-form-item>
                <el-form-item label="设计动画">
                  <span>{{ scope.row.animation }}</span>
                </el-form-item>
                <el-form-item label="设计动画-Hidol对接">
                  <span>{{ scope.row.animation_hidol }}</span>
                </el-form-item>
                <el-form-item label="测试基本">
                  <span>{{ scope.row.tester }}</span>
                </el-form-item>
                <el-form-item label="测试总责">
                  <span>{{ scope.row.tester_quality }}</span>
                </el-form-item>
                <el-form-item label="后端IT技术对接">
                  <span>{{ scope.row.backend_docking }}</span>
                </el-form-item>
                <el-form-item label="节目统筹">
                  <span>{{ scope.row.plan }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="interaction_linkage"
            label="交互引擎"
            min-width="70"
          />
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="h5_1" 
            label="H5基础" 
            min-width="70"/>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="h5_2" 
            label="H5复杂" 
            min-width="70"/>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="plan" 
            label="节目统筹" 
            min-width="70"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="operation"
            label="运营基本"
            min-width="70"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="originality"
            label="节目创意"
            min-width="70"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="animation"
            label="设计动画"
            min-width="70"
          />
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="tester" 
            label="测试基本" 
            min-width="70"/>
          <el-table-column
            v-if="legalAffairsManager || bonusManage"
            label="操作"
            min-width="90"
          >
            <template slot-scope="scope">
              <el-button 
                size="small" 
                type="warning" 
                @click="editHandle(scope.row)">修改</el-button>
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
import { getTeamRate } from "service";
import { Cookies } from "utils/cookies";
import {
  Button,
  Table,
  TableColumn,
  Pagination,
  MessageBox,
  Form,
  FormItem
} from "element-ui";

export default {
  components: {
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem
  },
  data() {
    return {
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      role: "",
      tableData: []
    };
  },
  computed: {
    bonusManage: function() {
      return this.role.find(r => {
        return r.name === "bonus-manager";
      });
    },
    legalAffairsManager: function() {
      return this.role.find(r => {
        return r.name === "legal-affairs-manager";
      });
    }
  },
  created() {
    this.getTeamRate();
    let user_info = JSON.parse(Cookies.get("user_info"));
    this.role = user_info.roles.data;
  },
  methods: {
    editHandle(data) {
      this.$router.push({
        path: "ratio/edit/" + data.id
      });
    },
    getTeamRate() {
      this.setting.loading = true;
      getTeamRate(this)
        .then(res => {
          this.tableData = res.data;
          this.pagination.total = res.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
          this.setting.loading = false;
        });
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getTeamRate();
    }
  }
};
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;

  .program-list-wrap {
    background: #fff;
    padding: 30px;

    .el-form-item {
      margin-bottom: 15px;
    }
    .program-content-wrap {
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
