<template>
  <div  
    v-loading="setting.loading" 
    :element-loading-text="setting.loadingText" 
    class="page-list-template">
    <div 
      class="search-wrap">
      <el-form 
        ref="searchForm" 
        :inline="true"
        :model="searchForm" 
        class="search-form">
        <el-form-item 
          label="" 
          prop="description">
          <el-input 
            v-model="searchForm.description" 
            placeholder="请输入备注" 
            clearable/>
        </el-form-item>
        
        <el-form-item 
          label="" 
          prop="description">
          <el-select v-model="searchForm.type" clearable placeholder="请选择类型">
            <el-option
              v-for="item in linkTypes"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button 
            type="primary"
            size="small"
            @click="search">搜索</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div 
      class="actions-wrap">
      <span 
        class="label">
        短链数量: {{ total }}
      </span>
      <el-button 
        type="success" 
        size="small" 
        @click="addUrl">新增</el-button>
    </div>
    <div 
      class="table-area">
      <el-table
        :data="tableData"
        style="width: 100%">
        <el-table-column
          prop="url"
          label="短链接" 
          min-width="200">
          <template 
            slot-scope="scope">
            {{ scope.row.url }}
            <span  
              v-clipboard="scope.row.url"
              class="copy-link"
              @success="copyUrlSucess"
              @error="copyUrlError"
            >复制</span>
          </template>
        </el-table-column>
        <el-table-column
          prop="created_at"
          label="生成时间" 
          width="180"/>
        <el-table-column
          prop="target_url"
          label="原始链接" 
          min-width="280"/>
        <el-table-column
          prop="description"
          min-width="100"
          label="备注"/>
        <el-table-column
          label="下载" 
          min-width="80">
            <template slot-scope="scope">
                <el-button
                  v-if="scope.row.url_type === 1 ? true:false"
                  size="mini"
                  @click="showDialog(scope.row)">下载
                </el-button>
            </template>
          </el-table-column>
      </el-table>
    </div>
    <el-dialog
      title="请选择类型"
      :visible.sync="centerDialogVisible"
      width="50%"
      center 
      :show-close=false>
      <el-form
        ref="templateForm"
        :model="templateForm"
        label-position="top"
      >
        <el-form-item
          :rules="[{ required: true, message: '请选择时间', trigger: 'submit'}]"
          label="请选择时间"
          prop="date"
        >
          <el-date-picker
            class='el_date_picker'
            v-model="templateForm.date"
            type="daterange"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择类型', trigger: 'submit'}]"
          label="类型"
          prop="value"
        >
          <el-select v-model="templateForm.value" placeholder="请选择">
            <el-option
              v-for="item in types"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
           <el-button type="primary" size="small" @click="submit('templateForm')">下载</el-button>
           <el-button size="small" @click="resetField('templateForm')">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <div 
      class="pagination">
      <el-pagination
        :current-page="currentPage"
        :page-size="pageSize"
        :total="total" 
        layout="total, prev, pager, next, jumper"
        @current-change="currentChange"/>
    </div>
  </div>
</template>
<script>
import { getUrlList, getExportDownload } from 'service'
import VueClipboards from 'vue-clipboards'
import Vue from 'vue'
import moment from "moment"
Vue.use(VueClipboards)
import {
  Input,
  Button,
  FormItem,
  Form,
  Table,
  TableColumn,
  Pagination,
  DatePicker,
  Select,
  Option,
  Dialog
} from 'element-ui'
export default {
  components: {
    'el-input': Input,
    'el-button': Button,
    'el-form-item': FormItem,
    'el-form': Form,
    'el-table': Table,
    'el-table-column': TableColumn,
    'el-pagination': Pagination,
    'el-date-picker': DatePicker,
    'el-select': Select,
    'el-option': Option,
    "el-dialog": Dialog
  },
  data() {
    var checkUrl = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('url不能为空'))
      }
      if (
        !/(((^https?:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)$/g.test(
          value
        )
      ) {
        callback(new Error('url必须符合规范'))
      } else {
        callback()
      }
    }
    return {
      centerDialogVisible: false,
      searchForm: {
        description: '',
        type:''
      },
      currentPage: 1,
      pageSize: 10,
      total: null,
      tableData: [],
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      id:null,
      templateForm: {
        date: '',
        value:''
      },
      types: [{
        value: 'num',
        label: '人数'
      }, {
        value: 'times',
        label: '人次'
      }],
      linkTypes: [{
        value: '1',
        label: '外链'
      },{
        value: '0',
        label: '内部链接'
      }]
    }
  },
  created() {
    this.getUrlList()
  },
  methods: {
    search() {
      this.currentPage = 1
      this.getUrlList()
    },
    currentChange(e) {
      this.currentPage = e
      this.getUrlList()
    },
    getUrlList() {
      this.setting.loading = true
      let args = {
        page: this.currentPage,
        description: this.searchForm.description,
        url_type: this.searchForm.type
      }
      if (!this.searchForm.description) {
        delete args.description
      }
      if (!this.searchForm.type) {
        delete args.url_type
      }
      getUrlList(this, args)
        .then(response => {
          this.tableData = response.data
          this.total = response.meta.pagination.total
          this.setting.loading = false
        })
        .catch(err => {
          this.setting.loading = false
        })
    },
    addUrl() {
      this.$router.push({
        path: '/ad/url/add'
      })
    },
    copyUrlSucess: function(e) {
      this.$message({
        message: '链接复制成功',
        type: 'success'
      })
    },
    copyUrlError: function(e) {
      this.$message({
        message: '链接复制失败',
        type: 'error'
      })
    },
    showDialog(index, row) {
       this.centerDialogVisible = true
       this.id = row.id
    },
    submit(formName){
      this.$refs[formName].validate(valid => {
        if (valid) {
          let args={
            id:this.id,
            start_date:moment(this.templateForm.date[0]).format("YYYY-MM-DD"),
            end_date:moment(this.templateForm.date[1]).format("YYYY-MM-DD"),
            data_type:this.templateForm.value,
            type: "short_url"
          }
            getExportDownload(this, args)
              .then(response => {
                this.centerDialogVisible = false;
                const a = document.createElement("a");
                a.href = response;
                a.download = "download";
                a.click();
                window.URL.revokeObjectURL(response);
              })
              .catch(err => {
                console.log(err);
              });
          }
      });
    },
    resetField(formName){
      this.centerDialogVisible = false;
      this.$refs[formName].resetFields();
    }
  }
}
</script>

<style lang="less" scoped>
.page-list-template {
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
    .el-input {
      width: 200px;
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
  .copy-link {
    color: #03a9f4;
  }
  .download_date{
    height: 70px;
  }
  .download_type{
    height: 100px;
  }
  .el_date_picker{
    width: 100%;
  }
}
</style>

