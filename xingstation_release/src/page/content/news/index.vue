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
              placeholder="请输入新闻标题"
              style="width: 180px;"
              maxlength="18"
              show-word-limit
              clearable
            />
          </el-form-item>
          <el-form-item
            label
            prop="dataValue"
          >
            <el-date-picker
              v-model="filters.dataValue"
              :clearable="false"
              :picker-options="pickerOptions"
              type="datetimerange"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
              align="right"
            />
          </el-form-item>
          <el-form-item
            label
            prop="type"
          >
            <el-select
              v-model="filters.type"
              placeholder="请选择新闻类型"
              filterable
              clearable
            >
              <el-option
                v-for="item in newsTypeList"
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
            prop="user_name"
          >
            <el-input
              v-model="filters.user_name"
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
        <span class="label">数量: {{ pagination.total }}</span>
        <!-- 模板增加 -->
        <div>
          <el-button
            size="small"
            type="success"
            @click="linkToAddItem"
          >发布新闻</el-button>
        </div>
      </div>
      <!-- 表格数据展示 列表 -->
      <div class="list-warp">
        <el-table
          :data="tableData"
          style="width: 100%"
        >
          <el-table-column
            :show-overflow-tooltip="true"
            prop="id"
            label="ID"
            width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="title"
            label="新闻标题"
            width="240"
          />
          <el-table-column
            prop="cover_media"
            label="封面图片"
          >
            <template slot-scope="scope">
              <img
                :src="scope.row.cover_media.url"
                alt
                class="icon-item"
              >
            </template>
          </el-table-column>
          <el-table-column
            prop="type_text"
            label="新闻类型"
          >
            <template slot-scope="scope">{{scope.row.type_text}}</template>
          </el-table-column>
          <el-table-column
            prop="created_at"
            label="发布时间"
          />
          <el-table-column
            prop="user_name"
            label="发布人"
          />
          <el-table-column
            prop="status"
            label="展示状态"
          >
            <template slot-scope="scope">
              <el-switch
                v-model="scope.row.status"
                active-color="#13ce66"
                inactive-color="#ff4949"
              >
              </el-switch>
            </template>
          </el-table-column>
          <el-table-column
            fixed="right"
            label="操作"
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
      </div>
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
        type: '',
        status: '',
        dataValue: [],
        user_name: ''
      },
      newsTypeList: [
        {
          type: 'company',
          name: "公司新闻"
        },
        {
          type: 'media_report',
          name: "媒体报道"
        }
      ],
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
      tableData: [],
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
        include: '',//carousel_images
        title: this.filters.title,
        start_date: this.filters.dataValue[0] ? handleDateTimeTransform(this.filters.dataValue[0]) : "",
        end_date: this.filters.dataValue[1] ? handleDateTimeTransform(this.filters.dataValue[1]) : "",
        type: this.filters.type,
        status: this.filters.status,
        user_name: this.filters.user_name
      }
      Object.keys(searchArgs).map(key => {
        searchArgs[key] === "" ? delete searchArgs[key] : null
      })
      getTableList(this, 'news', searchArgs).then(res => {
        this.setting.loading = false;
        this.tableData = res.data
      }).catch(err => {
        this.setting.loading = true;
        console.log(err)
      })
    },
    search() {
      this.getTableList()
    },
    resetSearch(formName) {
      this.$refs[formName].resetFields();
      this.pagination.currentPage = 1;
      this.getTableList()
    },
    linkToAddItem() {
      this.$router.push({
        path: "/content/news/add"
      })
    },
    linkToEdit(item) {
      this.$router.push({
        path: "/content/news/edit/" + item.id
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
    .el-select,
    .item-input,
    .el-input {
      width: 380px;
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


