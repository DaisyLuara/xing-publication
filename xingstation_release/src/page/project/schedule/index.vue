<template>
  <div  
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText" 
    class="schedule-wrap">
    <!-- 搜索 -->
    <div 
      class="search-wrap">
      <el-form 
        ref="searchForm"
        :model="searchForm" 
        :inline="true" >
        <el-form-item 
          label="" 
          prop="name">
          <el-input 
            v-model="searchForm.name"
            placeholder="请输入模板名称" 
            clearable
            class="item-input" 
          />
        </el-form-item>
        <el-button  
          type="primary" 
          size="small" 
          @click="search">搜索</el-button>
      </el-form>
    </div>
    <div 
      class="actions-wrap">
      <span 
        class="label">
        数量: {{ pagination.total }}
      </span>
      <!-- 模板增加 -->
      <div>
        <el-button 
          size="small" 
          type="success" 
          @click="addTemplate('templateForm')">新增模板</el-button>
      </div>
    </div>
    <!-- 模板排期列表 -->
    <el-collapse 
      v-model="activeNames" 
      accordion>
      <el-collapse-item 
        v-for="(item, index) in tableData" 
        :name="index" 
        :key="item.id" >
        <template 
          slot="title">
          {{ item.name }}
          <el-button 
            type="primary" 
            icon="el-icon-edit" 
            circle 
            size="mini" 
            @click="modifyTemplateName(item)"/>
        </template>
        <div class="actions-wrap">
          <span class="label">
            数目: {{ item.schedules.data.length }}
          </span>
          <div>
            <el-button 
              size="small" 
              @click="addSchedule(index)">增加</el-button>
          </div>
        </div>
        <el-table 
          :data="item.schedules.data" 
          style="width: 100%">
          <el-table-column
            prop=""
            label="节目名称"
            min-width="150"
          >
            <template 
              slot-scope="scope">
              <el-select 
                v-model="scope.row.project.name" 
                :loading="searchLoading" 
                :remote-method="getProject"
                filterable 
                placeholder="请搜索"
                remote 
                clearable 
                style="width: 180px;"
                @change="projectChangeHandle(index, scope.$index, scope.row.project.name)" >
                <el-option
                  v-for="item in projectList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
            </template>
          </el-table-column>
          <el-table-column
            prop="icon"
            label="节目图标"
            width="100"
          >
            <template 
              slot-scope="scope">
              <img 
                :src="scope.row.project.icon" 
                style="width: 50%">
            </template>
          </el-table-column>
          <el-table-column
            prop="stime"
            label="开始时间"
            min-width="120"
          >
            <template 
              slot-scope="scope">
              <el-time-select
                v-model="scope.row.date_start"
                :picker-options="{
                  start: '10:00',
                  step: '02:00',
                  end: '22:00'
                }"
                placeholder="开始时间"
                format="HH:mm"
                style="width: 150px"/>
            </template>
          </el-table-column>
          <el-table-column
            prop="etime"
            label="结束时间"
            min-width="120"
          >
            <template 
              slot-scope="scope">
              <el-time-select
                v-model="scope.row.date_end"
                :picker-options="{
                  start: '10:00',
                  step: '02:00',
                  end: '22:00',
                  minTime: scope.row.date_start
                }"
                placeholder="结束时间"
                format="HH:mm"
                style="width: 150px"/>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="time"
            label="时间"
            min-width="100"
          >
            <template 
              slot-scope="scope">
              {{ scope.row.project.created_at }}
            </template>
          </el-table-column>
          <el-table-column 
            label="操作" 
            min-width="100">
            <template 
              slot-scope="scope">
              <el-button 
                v-if="scope.row.project.icon"
                size="mini"
                type="warning"
                @click="editSchedule(scope.row)">编辑</el-button>
              <el-button 
                v-if="!scope.row.project.icon" 
                size="mini" 
                type="danger" 
                icon="el-icon-delete" 
                @click="deleteAddSchedule(index, scope.$index, scope.row)"/>
              <el-button 
                v-if="!scope.row.project.icon" 
                size="mini" 
                style="background-color: #8bc34a;border-color: #8bc34a; color: #fff;"
                @click="saveSchedule(scope.row)">保存</el-button>
            </template>
          </el-table-column>
        </el-table> 
      </el-collapse-item>
    </el-collapse>
    <div 
      class="pagination-wrap">
      <el-pagination
        :total="pagination.total"
        :page-size="pagination.pageSize"
        :current-page="pagination.currentPage"
        layout="prev, pager, next, jumper, total"
        @current-change="changePage"
      />
    </div>
    <!-- 新增，修改 -->
    <el-dialog 
      :title="title"
      :visible.sync="templateVisible" 
      @close="dialogClose" >
      <el-form
        v-loading="loading"
        ref="templateForm"
        :model="templateForm" 
        label-width="150px" >
        <el-form-item 
          :rules="[{ type: 'string', required: true, message: '请输入名称', trigger: 'submit' }]"
          label="模板名" 
          prop="name" >
          <el-input 
            v-model="templateForm.name" 
            placeholder="请输入名称" 
            class="item-input"/>
        </el-form-item>
        <el-form-item>
          <el-button 
            type="primary" 
            size="small"
            @click="submit('templateForm')" >完成</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script>
import {
  Form,
  FormItem,
  Button,
  Collapse,
  CollapseItem,
  Select,
  Option,
  Pagination,
  Table,
  TableColumn,
  Dialog,
  TimeSelect,
  MessageBox,
  Input
} from 'element-ui'
import {
  modifySchedule,
  saveSchedule,
  getScheduleList,
  saveTemplate,
  getSearchModuleList,
  getSearchProjectList,
  modifyTemplate,
} from 'service'

export default {
  components: {
    ElCollapse: Collapse,
    ElCollapseItem: CollapseItem,
    ElTimeSelect: TimeSelect,
    ElDialog: Dialog,
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
      activeNames: 0,
      templateVisible: false,
      loading: false,
      title: '',
      templateList: [],
      templateForm: {
        tpl_id: '',
        name: '',
      },
      projectList: [],
      tableData: [],
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      searchForm: {
        name: ''
      },
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      searchLoading: false
    }
  },
  created() {
    this.getModuleList()
    this.getScheduleList()
  },
  methods: {
    modifyTemplateName(item) {
      this.loading = false
      this.title = '修改模板'
      let name = item.name
      this.templateForm = {
        tpl_id: item.id,
        name: name,
      }
      this.templateVisible = true
    },
    projectChangeHandle(pIndex, index, val) {
      this.tableData[pIndex].schedules.data[index].project.id = val
    },
    editSchedule(row) {
      this.setting.loading = true
      let id = row.id
      let date_end = row.date_end
      let date_start = row.date_start
      let project_id = row.project.id
      if (date_end && date_start && project_id) {
        let args = {
          include: 'project',
          project_id: project_id,
          date_end: date_end,
          date_start: date_start
        }
        modifySchedule(this, id, args)
          .then(response => {
            this.setting.loading = false
            this.$message({
              message: '修改成功',
              type: 'success'
            })
            this.getScheduleList()
          })
          .catch(err => {
            console.log(err)
            this.setting.loading = false
          })
      } else {
        this.setting.loading = false
        this.$message({
          message: '节目名称，开始时间，结束时间不能为空',
          type: 'warning'
        })
      }
    },
    saveSchedule(row) {
      this.setting.loading = true
      let date_end = row.date_end
      let date_start = row.date_start
      let tpl_id = row.tpl_id
      let project_id = row.project.id
      if (date_end && date_start && project_id) {
        let args = {
          tpl_id: tpl_id,
          project_id: project_id,
          date_end: date_end,
          date_start: date_start
        }
        saveSchedule(this, args)
          .then(response => {
            this.setting.loading = false
            this.$message({
              message: '添加成功',
              type: 'success'
            })
            this.getScheduleList()
          })
          .catch(err => {
            console.log(err)
            this.setting.loading = false
          })
      } else {
        this.setting.loading = false
        this.$message({
          message: '节目名称，开始时间，结束时间不能为空',
          type: 'warning'
        })
      }
    },
    addTemplate() {
      this.templateForm.name = ''
      this.templateForm.tpl_id = ''
      this.templateVisible = true
      this.title = '增加模板'
    },
    deleteAddSchedule(pIndex, index, r) {
      this.tableData[pIndex].schedules.data.splice(index, 1)
    },
    getScheduleList() {
      this.setting.loading = true
      let args = {
        page: this.pagination.currentPage,
        include: 'schedules.project',
        name: this.searchForm.name
      }
      return getScheduleList(this, args)
        .then(response => {
          this.tableData = response.data
          this.pagination.total = response.meta.pagination.total
          this.setting.loading = false
        })
        .catch(err => {
          console.log(err)
          this.setting.loading = false
        })
    },
    addSchedule(index) {
      let tpl_id = this.tableData[index].id
      let td = {
        date_start: '',
        date_end: '',
        project: {
          id: '',
          info: '',
          icon: '',
          created_at: ''
        },
        tpl_id: tpl_id
      }
      this.tableData[index].schedules.data.push(td)
    },
    dialogClose() {
      this.templateVisible = false
    },
    getProject(query) {
      if (query !== '') {
        this.searchLoading = true
        let args = {
          name: query
        }
        return getSearchProjectList(this, args)
          .then(response => {
            this.projectList = response.data
            if (this.projectList.length == 0) {
              this.projectList = []
            }
            this.searchLoading = false
          })
          .catch(err => {
            console.log(err)
            this.searchLoading = false
          })
      } else {
        this.projectList = []
      }
    },
    getModuleList() {
      return getSearchModuleList(this)
        .then(response => {
          let data = response.data
          this.templateList = data
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let args = {
            point_id: this.templateForm.point_id,
            name: this.templateForm.name
          }
          let id = this.templateForm.tpl_id
          if (this.templateForm.tpl_id) {
            modifyTemplate(this, id, args)
              .then(response => {
                this.$message({
                  message: '修改成功',
                  type: 'success'
                })
                this.templateVisible = false
                this.getScheduleList()
              })
              .catch(err => {
                this.templateVisible = false
                console.log(err)
              })
          } else {
            saveTemplate(this, args)
              .then(response => {
                this.$message({
                  message: '添加成功',
                  type: 'success'
                })
                this.templateVisible = false
                this.getScheduleList()
              })
              .catch(err => {
                this.templateVisible = false
                console.log(err)
              })
          }
        }
      })
    },
    search() {
      this.pagination.currentPage = 1
      this.getScheduleList()
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getScheduleList()
    }
  }
}
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
