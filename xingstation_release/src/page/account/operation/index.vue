<template>
  <div class="root">
    <div class="operation-wrap">
      <div class="scope-wrap">
        <label class="search-text">选择操作范围：</label>
        <el-select v-model="value" placeholder="请选择">
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
        <label style="margin-left: 20px;" class="search-text"> 选择时间：</label>
        <el-date-picker
          v-model="value1"
          type="daterange"
          range-separator="至"
          start-placeholder="开始日期"
          end-placeholder="结束日期">
        </el-date-picker>
      </div>
      <div class="actions-wrap">
        <div>
          <label class="search-text">选择操作内容：</label>
          <el-radio v-model="radio" label="1">所有</el-radio>
          <el-radio v-model="radio" label="2">自定义</el-radio>
        </div>
          <el-button size="small" type="success">下载日志</el-button>
      </div>
      <el-table :data="tableData" style="width: 100%" border>
        <el-table-column
          prop="date"
          label="操作时间"
          >
        </el-table-column>
        <el-table-column
          prop="name"
          label="操作者"
          >
        </el-table-column>
        <el-table-column
          prop="object"
          label="操作对象"
          >
        </el-table-column>
        <el-table-column
          prop="content"
          label="操纵内容"
          >
        </el-table-column>
        <el-table-column label="操作详情">
          <el-table-column
            prop="change_item"
            label="变更项"
            >
          </el-table-column>
          <el-table-column label="变更前" prop="change_before">
          </el-table-column>
        </el-table-column>
      </el-table> 
      <div class="pagination-wrap">
      <el-pagination
      layout="prev, pager, next, jumper, total"
      :total="pagination.total"
      :page-size="pagination.pageSize"
      :current-page="pagination.currentPage"
      @current-change="changePage"
      >
      </el-pagination>
    </div> 
    </div>
  </div>
</template>

<script>
import { DatePicker, Select, Option, Button, Radio, Table, TableColumn, Pagination} from 'element-ui'

export default {
  name: 'operationLog',
  data() {
    return {
      pagination: {
        total: 100,
        pageSize: 10,
        currentPage: 1
      },
      tableData: [{
        date: '2016-05-03 12:03:46',
        name: '王小虎',
        object: '好孩子快乐星球',
        content: '修改投放时间',
        change_item: '2018/01/01-2018/04/04',
        change_before: '2018/01/01-2018/03/04'
      }, {
        date: '2016-05-03 12:03:46',
        name: '王小虎',
        object: '好孩子快乐星球',
        content: '修改投放时间',
        change_item: '2018/01/01-2018/04/04',
        change_before: '2018/01/01-2018/03/04'
      }, {
        date: '2016-05-03 12:03:46',
        name: '王小虎',
        object: '好孩子快乐星球',
        content: '修改投放时间',
        change_item: '2018/01/01-2018/04/04',
        change_before: '2018/01/01-2018/03/04'
      }, {
        date: '2016-05-03 12:03:46',
        name: '王小虎',
        object: '好孩子快乐星球',
        content: '修改投放时间',
        change_item: '2018/01/01-2018/04/04',
        change_before: '2018/01/01-2018/03/04'
      }, {
        date: '2016-05-03 12:03:46',
        name: '王小虎',
        object: '好孩子快乐星球',
        content: '修改投放时间',
        change_item: '2018/01/01-2018/04/04',
        change_before: '2018/01/01-2018/03/04'
      }],
      options: [{
        value: '选项1',
        label: '黄金糕'
      }, {
        value: '选项2',
        label: '双皮奶'
      }, {
        value: '选项3',
        label: '蚵仔煎'
      }, {
        value: '选项4',
        label: '龙须面'
      }, {
        value: '选项5',
        label: '北京烤鸭'
      }],
      value: '',
      value1: '',
      radio: '1',
    }
  },
  created: function(){
   
  },
  methods: {
    changePage (currentPage) {
      this.pagination.currentPage = currentPage
    }
  },
  components: {
    "el-select": Select,
    "el-option": Option,
    "el-button": Button,
    "el-radio": Radio,
    "el-date-picker": DatePicker,
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-pagination": Pagination
  }
}
</script>
<style scoped lang="less">
  .root {
    padding: 10px;
    font-size: 14px;
    color: #5E6D82;
    .operation-wrap{
      background: #fff;
      padding: 15px 30px;
      .scope-wrap{
        .search-text{
          font-size: 14px;
        }
      }
      .actions-wrap{
        margin-top: 15px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
        margin-bottom: 10px;
        .search-text{
          font-size: 14px;
        }
        .label {
          font-size: 14px;
        }
      }
      .pagination-wrap{
        margin: 10px auto;
        text-align: right;
      }
    }
  }
</style>

