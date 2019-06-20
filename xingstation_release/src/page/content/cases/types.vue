<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
    >
      <div class="label-title">{{ $route.name }}</div>
      <!-- 业态类型列表 -->
      <div class="formats">
        <div class="table-title">业态类型管理</div>
        <div class="actions-wrap">
          <span class="label">总数: {{ pagination1.total }}</span>
          <div>
            <el-button
              size="small"
              type="success"
              @click="addType('formats')"
            >新增业态类型</el-button>
          </div>
        </div>
        <div class="list-warp">
          <el-table
            :data="formatsTypeList"
            style="width: 100%"
          >
            <el-table-column
              prop="id"
              label="ID"
              width="180"
            >
            </el-table-column>
            <el-table-column
              prop="type"
              label="业态类型"
              width="180"
            >
            </el-table-column>
            <el-table-column
              fixed="center"
              label="操作"
            >
              <template slot-scope="scope">
                <el-button
                  type="text"
                  size="small"
                  @click="edit(scope.row,'formats')"
                >编辑</el-button>
                <el-button
                  type="text"
                  size="small"
                  @click="delete(scope.row,'formats')"
                >删除</el-button>
              </template>
            </el-table-column>
          </el-table>
        </div>
        <!-- 分页 -->
        <div class="pagination-wrap">
          <el-pagination
            :total="pagination1.total"
            :page-size="pagination1.pageSize"
            :current-page="pagination1.currentPage"
            layout="prev, pager, next, jumper, total"
            @current-change="changePage('formats')"
          />
        </div>
        <!-- 弹窗 -->
        <el-dialog
          title="新增业态类型"
          :visible.sync="formatVisible"
          width="30%"
          :before-close="handleFormatClose"
        >
          <span>这是一段信息</span>
          <span
            slot="footer"
            class="dialog-footer"
          >
            <el-button @click="dialogVisible = false">取 消</el-button>
            <el-button
              type="primary"
              @click="dialogVisible = false"
            >确 定</el-button>
          </span>
        </el-dialog>
      </div>
      <!-- 案例类型列表 -->
      <div class="case">
        <div class="table-title">案例类型管理</div>
        <div class="actions-wrap">
          <span class="label">总数: {{ pagination2.total }}</span>
          <div>
            <el-button
              size="small"
              type="success"
              @click="addType('case')"
            >新增案例类型</el-button>
          </div>
        </div>
        <div class="list-warp">
          <el-table
            :data="formatsTypeList"
            style="width: 100%"
          >
            <el-table-column
              prop="id"
              label="ID"
              width="180"
            >
            </el-table-column>
            <el-table-column
              prop="type"
              label="案例类型"
              width="180"
            />
            <el-table-column
              fixed="center"
              label="操作"
            >
              <template slot-scope="scope">
                <el-button
                  type="text"
                  size="small"
                  @click="edit(scope.row,'case')"
                >编辑</el-button>
                <el-button
                  type="text"
                  size="small"
                  @click="delete(scope.row,'case')"
                >删除</el-button>
              </template>
            </el-table-column>
          </el-table>
        </div>
        <!-- 分页 -->
        <div class="pagination-wrap">
          <el-pagination
            :total="pagination2.total"
            :page-size="pagination2.pageSize"
            :current-page="pagination2.currentPage"
            layout="prev, pager, next, jumper, total"
            @current-change="changePage('case')"
          />
        </div>
        <!-- 弹窗 -->
        <el-dialog
          title="新增业态类型"
          :visible.sync="formatVisible"
          width="30%"
          :before-close="handleFormatClose"
        >
          <span>这是一段信息</span>
          <span
            slot="footer"
            class="dialog-footer"
          >
            <el-button @click="dialogVisible = false">取 消</el-button>
            <el-button
              type="primary"
              @click="dialogVisible = false"
            >确 定</el-button>
          </span>
        </el-dialog>
      </div>
    </div>
  </div>
</template>
<script>
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
  Tag,
  Radio,
  MessageBox,
  DatePicker,
  Select,
  Option,
  CheckboxGroup,
  Checkbox,
  Switch,
  Upload
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
    "el-switch": Switch,
    "el-tag": Tag,
    "el-radio": Radio,
    "el-upload": Upload,
  },
  data() {
    return {
      formatsTypeList: [],
      formatVisible: false,
      caseVisible: false,
      pagination1: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      pagination2: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
    }
  },
  mounted() { },
  methods: {
    edit(item, type) {

    },
    delete(item, type) {

    },
    changePage(currentPage) {
      // this.pagination.currentPage = currentPage;
    },
    handleFormatClose(done) {
      this.$confirm('确认关闭？')
        .then(_ => {
          done();
        })
        .catch(_ => { });
    },
    handleCaseClose(done) {
      this.$confirm('确认关闭？')
        .then(_ => {
          done();
        })
        .catch(_ => { });
    }
  }
}
</script>
<style lang="less" scoped>
.root {
  background: #fff;
  padding: 20px;
  min-height: 100vh;
  .label-title {
    margin-bottom: 20px;
  }
}
</style>


