<template>
  <div  
    v-loading="setting.loading" 
    :element-loading-text="setting.loadingText" 
    class="page-list-template">
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
          label="备注"/>
      </el-table>
    </div>
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
import url from 'service/url'
import VueClipboards from 'vue-clipboards'
import Vue from 'vue'
Vue.use(VueClipboards)
import {
  Input,
  Button,
  FormItem,
  Form,
  Table,
  TableColumn,
  Pagination
} from 'element-ui'
export default {
  components: {
    'el-input': Input,
    'el-button': Button,
    'el-form-item': FormItem,
    'el-form': Form,
    'el-table': Table,
    'el-table-column': TableColumn,
    'el-pagination': Pagination
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
      linkInfo: {
        link: '',
        info: ''
      },
      rules: {
        link: [{ validator: checkUrl, trigger: 'blur' }]
      },
      currentPage: 1,
      pageSize: 10,
      total: null,
      tableData: [],
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      }
    }
  },
  created() {
    this.getUrlList()
  },
  methods: {
    currentChange(e) {
      this.currentPage = e
      this.getUrlList()
    },
    getUrlList() {
      this.setting.loading = true
      let args = {
        page: this.currentPage
      }
      url
        .getUrlList(this, args)
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
    }
  }
}
</script>

<style lang="less" scoped>
.page-list-template {
  padding: 30px;
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
}
</style>

