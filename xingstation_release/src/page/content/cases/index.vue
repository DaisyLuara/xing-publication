<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="item-list-warp"
    >
      <!-- 搜索 -->
      <div class="search-wrap">
        <el-form
          ref="filters"
          :model="filters"
          :inline="true"
        >
          <el-form-item
            label
            prop="title"
          >
            <el-input
              v-model="filters.title"
              placeholder="请输入案例标题"
              style="width:100%;"
              maxlength="18"
              show-word-limit
              clearable
            />
          </el-form-item>

          <el-form-item
            label
            prop="activityDate"
          >
            <el-date-picker
              v-model="filters.activityDate"
              :clearable="false"
              :picker-options="pickerOptions"
              type="datetimerange"
              start-placeholder="案例活动开始时间"
              end-placeholder="案例活动结束时间"
              style="width:100%;"
            />
          </el-form-item>
          <el-form-item
            label
            prop="formatsType"
          >
            <el-select
              v-model="filters.formatsType"
              placeholder="请选择业态类型（可多选）"
              style="width:100%;"
              multiple
              filterable
              clearable
            >
              <el-option
                v-for="item in formatsTypeList"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>

          <el-form-item
            label
            prop="casesType"
          >
            <el-select
              v-model="filters.casesType"
              placeholder="请选择案例类型（可多选）"
              style="width:100%;"
              multiple
              filterable
              clearable
            >
              <el-option
                v-for="item in casesTypeList"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            label
            prop="status"
          >
            <el-select
              v-model="filters.status"
              placeholder="请选择展示状态"
              style="width:100%;"
              filterable
              clearable
            >
              <el-option
                v-for="item in visiableList"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            label
            prop="activityDate"
          >
            <el-date-picker
              v-model="filters.activityDate"
              :clearable="false"
              :picker-options="pickerOptions"
              type="datetimerange"
              start-placeholder="发布开始时间"
              end-placeholder="发布结束时间"
              style="width:100%;"
            />
          </el-form-item>
          <el-form-item
            label
            prop="people"
          >
            <el-input
              v-model="filters.people"
              placeholder="请输入发布人"
              style="width: 180px;"
              show-word-limit
              clearable
            />
          </el-form-item>
          <el-form-item
            label
            prop
          >
            <el-button
              type="primary"
              size="small"
              @click="search('filters')"
            >搜索</el-button>
            <el-button
              type="default"
              size="small"
              @click="resetSearch('filters')"
            >重置</el-button>
          </el-form-item>
        </el-form>
      </div>
      <div class="actions-wrap">
        <span class="label">总数: {{ pagination.total }}</span>
        <!-- 模板增加 -->
        <div>
          <el-button
            size="small"
            type="success"
            @click="linkToAddItem"
          >发布案例</el-button>
          <el-button
            size="small"
            type="success"
            @click="linkToTypeManage"
          >类型管理</el-button>
        </div>
      </div>
      <!-- 表格数据展示 列表 -->
      <el-table
        ref="table"
        :data="tableData"
        style="width: 100%"
        highlight-current-row
        type="expand"
      >
        <el-table-column type="expand">
          <template slot-scope="scope">
            <el-form
              label-position="left"
              inline
              class="demo-table-expand"
            >
              <el-form-item label="案例标题">
                <span>{{ scope.row.title }}</span>
              </el-form-item>
              <el-form-item label="封面图片">
                <a
                  :href="scope.row.icon"
                  target="_blank"
                  style="color: blue"
                >查看</a>
              </el-form-item>
              <el-form-item label="业态类型">
                <span
                  v-for="item in scope.row.formatsType"
                  :key="item.id"
                >{{ item }}</span>
              </el-form-item>
              <el-form-item label="案例类型">
                <span
                  v-for="item in scope.row.casesType"
                  :key="item.id"
                  class="span-type"
                >{{ item }}</span>
              </el-form-item>
              <el-form-item label="案例活动时间">
                <span>{{ scope.row.activityDate }}</span>
              </el-form-item>
              <el-form-item label="发布时间">
                <span>{{ scope.row.releaseDate }}</span>
              </el-form-item>
              <el-form-item label="发布人">
                <span>{{ scope.row.people }}</span>
              </el-form-item>
              <el-form-item label="">
                <span
                  v-for="item in scope.row.relateData"
                  :key="item.id"
                  class="span-data"
                >{{ item }}</span>
              </el-form-item>
              <el-form-item label="展示状态">
                <span>{{ scope.row.visiable === 1 ? '展示中' : '已隐藏' }}</span>
              </el-form-item>
            </el-form>
          </template>
        </el-table-column>
        <el-table-column
          prop="id"
          label="ID"
          width="80"
        />
        <el-table-column
          :show-overflow-tooltip="true"
          prop="title"
          label="案例标题"
          width="100"
        />
        <el-table-column
          :show-overflow-tooltip="true"
          prop="icon"
          label="封面图片"
          width="120"
        >
          <template slot-scope="scope">
            <img
              :src="scope.row.icon"
              alt
              class="icon-item"
            >
          </template>
        </el-table-column>
        <el-table-column
          :show-overflow-tooltip="true"
          prop="formatsType"
          label="业态类型"
          width="100"
        >
          <template slot-scope="scope">
            <ul class="ul-case">
              <li
                v-for="item in scope.row.formatsType"
                :key="item.id"
              >{{item}}</li>
            </ul>
          </template>
        </el-table-column>
        <el-table-column
          :show-overflow-tooltip="true"
          prop="casesType"
          label="案例类型"
        >
          <template slot-scope="scope">
            <ul class="ul-case">
              <li
                v-for="item in scope.row.casesType"
                :key="item.id"
              >{{item}}</li>
            </ul>
          </template>
        </el-table-column>
        <el-table-column
          prop="activityDate"
          label="案例活动起止时间"
        />
        <el-table-column
          :show-overflow-tooltip="true"
          prop="releaseDate"
          label="发布时间"
        />
        <el-table-column
          :show-overflow-tooltip="true"
          prop="people"
          label="发布人"
        />
        <el-table-column
          :show-overflow-tooltip="true"
          prop="relateData"
          label="相关数据"
        >
          <template slot-scope="scope">
            <ul class="ul-case">
              <li
                v-for="item in scope.row.relateData"
                :key="item.id"
              >{{item}}</li>
            </ul>
          </template>
        </el-table-column>
        <el-table-column
          :show-overflow-tooltip="true"
          prop="visiable"
          label="展示状态"
          width="80"
        >
          <template slot-scope="scope">{{ scope.row.visiable === 1 ? '展示中' : '已隐藏' }}</template>
        </el-table-column>
        <el-table-column
          fixed="right"
          label="操作"
          width="60"
        >
          <template slot-scope="scope">
            <el-button
              type="text"
              size="small"
              @click="linkToEdit(scope.row)"
            >编辑</el-button>
          </template>
        </el-table-column>
      </el-table>
      <!-- 分页 -->
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
import { handleDateTimeTransform, getTableList } from 'service'
import {
  Loading,
  Button,
  Input,
  Table,
  Row,
  Col,
  TableColumn,
  Pagination,
  Dialog,
  Form,
  FormItem,
  MessageBox,
  DatePicker,
  Select,
  Option,
  CheckboxGroup,
  Checkbox,
  Switch
} from "element-ui";

export default {
  components: {
    "el-row": Row,
    "el-col": Col,
    "el-table": Table,
    "el-date-picker": DatePicker,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-select": Select,
    "el-option": Option,
    "el-checkbox-group": CheckboxGroup,
    "el-checkbox": Checkbox,
    "el-dialog": Dialog,
    "el-switch": Switch
  },
  data() {
    return {
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      filters: {
        title: '',
        activityDate: [],
        formatsType: [],
        casesType: [],
        status: '',
        releaseDate: [],
        people: ''
      },
      formatsTypeList: [],
      casesTypeList: [],
      visiableList: [
        {
          id: 1,
          name: "展示中"
        },
        {
          id: 0,
          name: "已隐藏"
        }
      ],
      tableData: [
        // {
        //   id: 1,
        //   title: '案例标题1',
        //   icon: 'http://image.xingstation.cn/1007/image/71_3b94c5f90c44bc356435c0c4bcab81b.png',
        //   formatsType: ['购物中心', ' 健身房'],
        //   casesType: [' 商场 - 单点陈美', ' 商场 - 主题阵地商户 - 人气约饭机'],
        //   activityDate: ' 2019-03-23  —  2019-04-23',
        //   releaseDate: '2019-03-23  16：23：30',
        //   relateData: ['围观 200', '拉新会员数 100', 'fCPL转化率 30%'],
        //   visitable: 0,
        //   people: 'a'
        // },
        // {
        //   id: 2,
        //   title: '案例标题2案例',
        //   icon: 'http://image.xingstation.cn/1007/image/71_3b94c5f90c44bc356435c0c4bcab81b.png',
        //   formatsType: ['购物中心'],
        //   casesType: [' 商场 - 单点陈美', ' 商场 - 主题阵地商户 - 人气约饭机'],
        //   activityDate: ' 2019-03-23  —  2019-04-23',
        //   releaseDate: '2019-03-23  16：23：30',
        //   relateData: ['围观 200', '拉新会员数 100', 'fCPL转化率 30%'],
        //   visitable: 1,
        //   people: 'a'
        // },
        // {
        //   id: 3,
        //   title: '案例标题3',
        //   icon: 'http://image.xingstation.cn/1007/image/71_3b94c5f90c44bc356435c0c4bcab81b.png',
        //   formatsType: ['健身房'],
        //   casesType: [' 商场 - 单点陈美', ' 商场 - 主题阵地商户 - 人气约饭机'],
        //   activityDate: ' 2019-03-23  —  2019-04-23',
        //   releaseDate: '2019-03-23  16：23：30',
        //   relateData: ['围观 200', '拉新会员数 100', 'fCPL转化率 30%'],
        //   visitable: 0,
        //   people: 'a'
        // },
      ],
      pickerOptions: {
        shortcuts: [{
          text: '最近一周',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近一个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近三个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          }
        }]
      },
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
    }
  },
  created() {
    this.getTableList()
  },
  mounted() { },
  methods: {
    getTableList() {
      this.setting.loading = true;
      let searchArgs = {
        page: this.pagination.currentPage,
        include: '',
        name: this.filters.name,
        start_date: this.filters.dataValue[0] ? handleDateTimeTransform(this.filters.dataValue[0]) : "",
        end_date: this.filters.dataValue[1] ? handleDateTimeTransform(this.filters.dataValue[1]) : "",
        type: this.filters.type,
        status: this.filters.status,
        user_name: this.filters.user_name
      }
      for (let i in searchArgs)
        searchArgs[i] === "" ? delete searchArgs[i] : null
      getTableList(this, 'cases', searchArgs).then(res => {
        console.log(res)
        this.tableData = res.data
      }).catch(err => {

      })
    },
    search() {
      this.getTableList()
    },
    resetSearch() {
      this.$refs[formName].resetFields();
      this.pagination.currentPage = 1;
      this.getTableList()
    },
    linkToAddItem() {
      this.$router.push({
        path: "/content/cases/edit"
      })
    },
    linkToEdit(item) {
      this.$router.push({
        path: "/content/cases/edit/" + item.id
      });
    },
    linkToTypeManage() {
      this.$router.push({
        path: "/content/cases/type"
      });
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getTableList();
    }
  }
}
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;
  .item-list-warp {
    background: #fff;
    padding: 30px;
    .icon-item {
      padding: 10px;
      width: 50%;
    }
    .el-select {
      width: 100%;
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
}
</style>


