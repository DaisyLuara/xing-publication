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
          @click="addPlanTime">新增排期</el-button>
      </div>
    </div>
    <!-- 子条目列表 -->
    <el-table
      :data="tableData"
      style="width: 100%"
    >
      <el-table-column
        :show-overflow-tooltip="true"
        label="广告行业"
        min-width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.ad_plan.name }}</span>
        </template>
      </el-table-column>
      <el-table-column
        :show-overflow-tooltip="true"
        label="素材创建人"
        min-width="60">
        <template slot-scope="scope">
          <span>{{ scope.row.advertisement.create_user_name }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="素材类型"
        min-width="60">
        <template slot-scope="scope">
          <span>{{ scope.row.advertisement.type_text }}</span>
        </template>
      </el-table-column>
      <el-table-column
        :show-overflow-tooltip="true"
        label="素材名称"
        min-width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.advertisement.name }}</span>
          <br>
          <span>
            <img
              :src="(scope.row.advertisement.type === 'static' || scope.row.advertisement.type === 'gif' ) ? scope.row.advertisement.link : scope.row.advertisement.img"
              width="40px">
          </span>
        </template>
      </el-table-column>
      <el-table-column
        :show-overflow-tooltip="true"
        label="附件"
        min-width="80">
        <template slot-scope="scope">
          <a
            :href="scope.row.link"
            target="_blank"
            style="color: blue">
            <i class="el-icon-download"/>
            {{ scope.row.advertisement.size }}M
          </a>
        </template>
      </el-table-column>
      <el-table-column
        label="广告标记"
        min-width="80">
        <template slot-scope="ad_scope">
          <span>{{ scope.row.advertisement.isad_text }}</span>
        </template>
      </el-table-column>
      <template v-if="scope.row.ad_plan.type==='program'">
        <el-table-column
          :show-overflow-tooltip="true"
          label="显示格式"
          min-width="130">
          <template slot-scope="scope">
            <span>
              模式：{{ modeOptions[scope.row.mode] }}<br>
              位置：{{ oriOptions[scope.row.ori] }} <br>
              尺寸：{{ scope.row.screen }}%
            </span>
          </template>
        </el-table-column>
      </template>

      <el-table-column
        v-if="scope.row.ad_plan.tmode === 'hours'"
        label="素材投放时间"
        min-width="100">
        <template slot-scope="scope">
          <span style="color: #67C23A"><i class="el-icon-rank"/></span>
          <span>
             {{ (scope.row.shm).toString().substring(scope.row.shm.toString().length-2) }}
          </span>
          至
          <span>
            {{ (scope.row.ehm).toString().substring(scope.row.ehm.toString().length-2) }}
          </span>
          分
        </template>
      </el-table-column>

      <el-table-column
        v-else
        label="素材投放时间"
        min-width="130">
        <template
          slot-scope="ad_scope">
          <span style="color: #67C23A"><i class="el-icon-time"/></span>
          <span>
            {{ ( (Array(4).join('0') + scope.row.shm).slice(-4)).substring(0,2) + ":"
            + ( (Array(4).join('0') + scope.row.shm).slice(-4)).substring(2) }}
          </span>
          至
          <span>
            {{ ( (Array(4).join('0') + scope.row.ehm).slice(-4)).substring(0,2) + ":"
            + ( (Array(4).join('0') + scope.row.ehm).slice(-4)).substring(2) }}
          </span>
        </template>
      </el-table-column>

      <el-table-column
        label="倒计时"
        min-width="80">
        <template slot-scope="scope">
          <span>
            {{ scope.row.cdshow ?'开启':'关闭' }}<br>
            {{ scope.row.ktime ? scope.row.ktime + '秒' : '默认时长' }}
          </span>
        </template>
      </el-table-column>

      <el-table-column
        label="状态"
        min-width="65">
        <template slot-scope="scope">
          <span>{{ scope.row.visiable === 1 ? '运营中' : '下架' }}</span>
        </template>
      </el-table-column>

      <el-table-column
        label="操作"
        min-width="50"
      >
        <template slot-scope="scope">
          <el-button
            size="small"
            type="default"
            @click="editPlanTime(scope.row.id)">编辑
          </el-button>
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
import {
  getAdPlanTimeList
} from "service";

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
      plan_id: null
    };
  },
  created() {
    this.plan_id = this.$route.params.plan_id;
    this.getPlanTimeList();
  },
  methods: {
    editPlanTime(row) {
      this.$router.push({
        path: "/plan/plan_time/edit/" + row.id,
      });
    },
    addPlanTime() {
      this.$router.push({
        path: "/plan/" + this.plan_id + "/plan_time/add",
      });
    },

    getPlanTimeList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        atiid : this.plan_id,
        include : "ad_plan,advertisement"
      };
      return getAdPlanTimeList(this, args)
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
      this.getPlanTimeList();
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getPlanTimeList();
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
