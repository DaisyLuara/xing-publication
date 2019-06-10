<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="schedule-wrap"
  >
    <div class="actions-wrap">
      <div>
        <span class="label">{{ adPlan.name }}</span>
        <br>
        <span class="label">数量: {{ pagination.total }}</span>
      </div>
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
      ref="multipleTable" 
      :data="tableData" 
      style="width: 100%" 
      highlight-current-row>
      <el-table-column type="expand">
        <template slot-scope="scope">
          <el-form 
            label-position="left" 
            inline 
            class="demo-table-expand">
            <el-form-item label="ID">
              <span>{{ scope.row.id }}</span>
            </el-form-item>
            <el-form-item label="广告行业">
              <span>{{ scope.row.advertisement.ad_trade_name }}</span>
            </el-form-item>
            <el-form-item label="素材创建人">
              <span>{{ scope.row.advertisement.create_user_name }}</span>
            </el-form-item>
            <el-form-item label="素材类型">
              <span>{{ scope.row.advertisement.type_text }}</span>
            </el-form-item>
            <el-form-item label="素材名称">
              <span>{{ scope.row.advertisement.name }}</span>
            </el-form-item>
            <el-form-item label="附件">
              <a 
                :href="scope.row.advertisement.link" 
                target="_blank" 
                style="color: blue">
                <i class="el-icon-download"/>
                {{ scope.row.advertisement.size }}M
              </a>
            </el-form-item>
            <el-form-item label="广告标记">
              <span>{{ scope.row.advertisement.isad_text }}</span>
            </el-form-item>

            <template v-if="adPlan.type === 'program'">
              <el-form-item label="显示模式">
                <span>{{ modeOptions[scope.row.mode] }}</span>
              </el-form-item>
              <el-form-item label="显示位置">
                <span>{{ oriOptions[scope.row.ori] }}</span>
              </el-form-item>
              <el-form-item label="显示尺寸">
                <span>{{ scope.row.screen }}%</span>
              </el-form-item>
            </template>

            <el-form-item label="素材投放时间">
              <template v-if="adPlan.tmode === 'hours'">
                <span style="color: #67C23A">
                  <i class="el-icon-rank"/>
                </span>
                <span>{{ (scope.row.shm).toString().substring(scope.row.shm.toString().length-2) }}</span>
                至
                <span>{{ (scope.row.ehm).toString().substring(scope.row.ehm.toString().length-2) }}</span>
                分
              </template>
              <template v-else>
                <span style="color: #67C23A">
                  <i class="el-icon-time"/>
                </span>
                <span>{{ scope.row.shm }}</span>
                至
                <span>{{ scope.row.ehm }}</span>
              </template>
            </el-form-item>
            <el-form-item label="倒计时">
              <span>{{ scope.row.cdshow ?'开启':'关闭' }}</span>
            </el-form-item>
            <el-form-item label="播放时长">
              <span>{{ scope.row.ktime ? scope.row.ktime + '秒' : '默认时长' }}</span>
            </el-form-item>
            <el-form-item label="状态">
              <span>{{ scope.row.visiable === 1 ? '运营中' : '下架' }}</span>
            </el-form-item>
          </el-form>
        </template>
      </el-table-column>
      <el-table-column 
        prop="id" 
        label="ID" 
        min-width="50"/>
      <el-table-column
        :show-overflow-tooltip="true"
        label="广告行业"
        prop="advertisement.ad_trade_name"
        min-width="80"
      />
      <el-table-column 
        label="类型" 
        prop="advertisement.type_text" 
        min-width="60"/>
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
              width="80px"
            >
          </span>
        </template>
      </el-table-column>
      <el-table-column 
        :show-overflow-tooltip="true" 
        label="附件" 
        min-width="80">
        <template slot-scope="scope">
          <a 
            :href="scope.row.advertisement.link" 
            target="_blank" 
            style="color: blue">
            <i class="el-icon-download"/>
            {{ scope.row.advertisement.size }}M
          </a>
        </template>
      </el-table-column>
      <el-table-column 
        label="广告标记" 
        prop="advertisement.isad_text" 
        min-width="80"/>
      <el-table-column
        v-if="adPlan.type === 'program'"
        :show-overflow-tooltip="true"
        label="显示格式"
        min-width="130"
      >
        <template slot-scope="scope">
          <span>
            模式：{{ modeOptions[scope.row.mode] }}
            <br>
            位置：{{ oriOptions[scope.row.ori] }}
            <br>
            尺寸：{{ scope.row.screen }}%
          </span>
        </template>
      </el-table-column>
      <el-table-column 
        v-if="adPlan.tmode === 'hours'" 
        label="素材投放时间" 
        min-width="120">
        <template slot-scope="scope">
          <span style="color: #67C23A">
            <i class="el-icon-rank"/>
          </span>
          <span>{{ (scope.row.shm).toString().substring(scope.row.shm.toString().length-2) }}</span>
          至
          <span>{{ (scope.row.ehm).toString().substring(scope.row.ehm.toString().length-2) }}</span>
          分
        </template>
      </el-table-column>
      <el-table-column 
        v-else 
        label="素材投放时间" 
        min-width="130">
        <template slot-scope="scope">
          <span style="color: #67C23A">
            <i class="el-icon-time"/>
          </span>
          <span>{{ scope.row.shm }}</span>
          至
          <span>{{ scope.row.ehm }}</span>
        </template>
      </el-table-column>
      <el-table-column 
        label="倒计时" 
        min-width="80">
        <template slot-scope="scope">
          <span>
            {{ scope.row.cdshow ?'开启':'关闭' }}
            <br>
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
        min-width="80">
        <template slot-scope="scope">
          <el-button 
            size="small" 
            type="warning" 
            @click="editPlanTime(scope.row.id)">编辑</el-button>
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
  Pagination,
  Table,
  TableColumn,
  MessageBox
} from "element-ui";
import { getAdPlanTimeList } from "service";

export default {
  components: {
    ElPagination: Pagination,
    ElForm: Form,
    ElFormItem: FormItem,
    ElButton: Button,
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
      plan_id: null,
      adPlan: {
        type: "",
        name: "",
        tmode: ""
      },

      modeOptions: {
        fullscreen: "全屏显示",
        unmanned: "无人互动",
        qrcode: "二维码页面",
        qrcode: "二维码页",
        floating: "浮窗显示"
      },

      oriOptions: {
        center: "居中",
        top: "顶部居中",
        bottom: "底部居中",
        left_top: "左上角",
        left: "左侧居中",
        left_bottom: "左下角",
        right_top: "右上角",
        right: "右侧居中",
        right_bottom: "右下角",
        center: "居中"
      }
    };
  },
  created() {
    this.adPlan = this.$route.query;
    this.plan_id = this.$route.params.plan_id;
    this.getPlanTimeList();
  },
  methods: {
    editPlanTime(id) {
      this.$router.push({
        path: "/ad/plan/plan_time/edit/" + id
      });
    },
    addPlanTime() {
      this.$router.push({
        path: "/ad/plan/" + this.plan_id + "/plan_time/add"
      });
    },
    getPlanTimeList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        atiid: this.plan_id,
        include: "advertisement"
      };
      getAdPlanTimeList(this, args)
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
