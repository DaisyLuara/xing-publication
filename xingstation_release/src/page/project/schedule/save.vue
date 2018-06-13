<template>
  <div class="item-wrap-template">
    <div class="topbar">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/project/item' }">模板排期管理</el-breadcrumb-item>
        <el-breadcrumb-item>添加</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="pane"  :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="pane-title">
        新增模板排期
      </div>
      <el-form
        ref="scheduleForm"
        :model="scheduleForm" label-width="150px">
        <el-form-item label="节目名称" prop="project" :rules="[{ required: true, message: '请输入节目名称', trigger: 'submit',type: 'number'}]">
          <el-select v-model="scheduleForm.project" filterable placeholder="请搜索" remote :remote-method="getProject" clearable :loading="searchLoading">
            <el-option
              v-for="item in projectList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="模版" :rules="[{ required: true, message: '请请选择模版', trigger: 'submit',type: 'number'}]">
          <el-select v-model="scheduleForm.template" placeholder="请选择" filterable clearable>
            <el-option
              v-for="item in templateList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="开始时间" prop="sdate" >
          <el-time-picker
            v-model="scheduleForm.sdate"
            placeholder="请选择开始时间"
            format="HH:mm">
          </el-time-picker>
        </el-form-item>
        <el-form-item label="结束时间" prop="edate">
          <el-time-picker
            v-model="scheduleForm.edate"
            placeholder="请选择结束时间"
            format="HH:mm">
          </el-time-picker>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submit('scheduleForm')">完成</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import search from 'service/search'
import project from 'service/project'
import {
  Form,
  Select,
  Option,
  FormItem,
  Button,
  TimePicker,
  Input,
  DatePicker,
  MessageBox
} from 'element-ui'

export default {
  components: {
    ElTimePicker: TimePicker,
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input,
    ElDatePicker: DatePicker
  },
  data() {
    return {
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      templateList: [],
      projectList: [],
      searchLoading: false,
      scheduleForm: {
        project: '',
        template: '',
        sdate: '',
        edate: ''
      }
    }
  },
  mounted() {},
  created() {
    this.setting.loading = true
    this.getModuleList()
    this.setting.loading = false
  },
  methods: {
    getProject(query) {
      this.searchLoading = true
      let args = {
        name: query
      }
      return search
        .getProjectList(this, args)
        .then(response => {
          this.projectList = response.data
          if (this.projectList.length == 0) {
            this.scheduleForm.project = ''
            this.projectList = []
          }
          this.searchLoading = false
        })
        .catch(err => {
          console.log(err)
          this.searchLoading = false
        })
    },
    getModuleList() {
      return search
        .getModuleList(this)
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
          this.setting.loading = true
          let edate =
            (new Date(this.scheduleForm.edate).getTime() +
              ((23 * 60 + 59) * 60 + 59) * 1000) /
            1000
          let args = {
            sdate: new Date(this.scheduleForm.sdate).getTime() / 1000,
            edate: edate,
            default_plid: this.scheduleForm.project,
          }
          return project
            .savePorjectLaunch(this, args)
            .then(response => {
              this.setting.loading = false
              this.$message({
                message: '添加成功',
                type: 'success'
              })
              this.$router.push({
                path: '/project/schedule'
              })
            })
            .catch(err => {
              this.setting.loading = false
              console.log(err)
            })
        } else {
          return
        }
      })
    }
  }
}
</script>

<style lang="less" scoped>
.item-wrap-template {
  .pane {
    border-radius: 5px;
    background-color: white;
    padding: 20px 40px 80px;

    .el-select,
    .item-input,
    .el-date-editor.el-input {
      width: 380px;
    }
    .item-list {
      .program-title {
        color: #555;
        font-size: 14px;
      }
    }
    .pane-title {
      padding-bottom: 20px;
      font-size: 18px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
    }
  }
}
</style>
