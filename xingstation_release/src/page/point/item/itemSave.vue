<template>
  <div 
    class="item-wrap-template">
    <div 
      class="topbar">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/project/item/index' }">点位管理</el-breadcrumb-item>
        <el-breadcrumb-item>添加</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div  
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText" 
      class="pane" >
      <div class="pane-title">
        新增点位
      </div>
      <el-form
        ref="pointForm"
        :model="pointForm" 
        label-width="150px">
        <el-form-item 
          :rules="[{ required: true, message: '请输入节目名称', trigger: 'submit',type: 'number'}]"
          label="场景" 
          prop="scene">
          <el-select 
            v-model="pointForm.scene" 
            filterable
            placeholder="请搜索" >
            <el-option
              v-for="item in sceneList"
              :key="item.id"
              :label="item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请输入区域', trigger: 'submit' ,type: 'number'}]"
          label="区域" 
          prop="area" >
          <el-select 
            v-model="pointForm.area" 
            placeholder="请选择" 
            filterable 
            @change="areaChangeHandle">
            <el-option
              v-for="item in areaList"
              :key="item.id"
              :label="item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请输入商场', trigger: 'submit' ,type: 'number'}]"
          label="商场" 
          prop="market" >
          <el-select 
            v-model="pointForm.market"  
            :remote-method="getMarket" 
            :loading="searchLoading" 
            placeholder="请搜索" 
            filterable 
            remote 
            @change="marketChangeHandle">
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请选择状态', trigger: 'submit' ,type: 'number'}]"
          label="状态"
          prop="status">
          <el-select 
            v-model="pointForm.status" 
            placeholder="请选择">
            <el-option
              v-for="item in statusList"
              :key="item.id"
              :label="item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item
          label="图标">
          <div class="up-area">
            <img 
              v-if="thumbnail_url !== ''" 
              :src="mediaBase + stepTwo.thumbnail_url" 
              class="avatar">
            <i 
              v-else 
              class="el-icon-plus avatar-uploader-icon"
              @click="handleOpenPane()"/>
            <i 
              v-if="thumbnail_url !== ''" 
              class="el-icon-circle-cross delete-icon-image" 
              @click="handleImageDelete()"/>
          </div>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请输入点位', trigger: 'submit',type: 'array'}]"
          label="点位" 
          prop="point">
          <el-select
            v-model="pointForm.point"
            multiple
            filterable
            allow-create
            default-first-option
            placeholder="请输入点位">
            <el-option
              v-for="item in pointList"
              :key="item.value"
              :label="item.label"
              :value="item.value"/>
          </el-select>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请选择类型', trigger: 'submit' ,type: 'number'}]"
          label="类型" 
          prop="type">
          <el-select 
            v-model="pointForm.type" 
            placeholder="请选择">
            <el-option
              v-for="item in typeList"
              :key="item.id"
              :label="item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button 
            type="primary">完成</el-button>
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
  Input,
  MessageBox
} from 'element-ui'

export default {
  components: {
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input
  },
  data() {
    return {
      mediaBase: process.env.SERVER_URL,
      thumbnail_url: '',
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      marketList: [],
      pointList: [],
      sceneList: [],
      statusList: [],
      typeList: [],
      searchLoading: false,
      pointForm: {
        scene: '',
        area: '',
        market: '',
        point: [],
        status: '',
        weekend: '',
        define: '',
        sdate: '',
        edate: ''
      },
      areaList: []
    }
  },
  created() {
    this.setting.loading = true
    this.setting.loading = false
  },
  methods: {
    handleClose() {
      this.panelVisible = false
    },
    handleImageDelete() {
      this.thumbnail_url = ''
    },
    handleOpenPane() {
      this.panelVisible = true
    },
    projectChangeHandle() {
      console.log(this.pointForm.project)
    },
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
            this.pointForm.project = ''
            this.projectList = []
          }
          this.searchLoading = false
        })
        .catch(err => {
          console.log(err)
          this.searchLoading = false
        })
    },
    areaChangeHandle() {
      console.log(this.pointForm.area)
      this.pointForm.market = ''
      this.getMarket(this.pointForm.market)
    },
    getAreaList() {
      return search
        .getAeraList(this)
        .then(response => {
          let data = response.data
          this.areaList = data
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    marketChangeHandle() {
      console.log(this.pointForm.market)
      this.pointForm.point = []
      this.getPoint()
    },
    pointChangeHandle() {
      console.log(this.pointForm.point)
    },
    getPoint() {
      let args = {
        include: 'market',
        market_id: this.pointForm.market
      }
      this.searchLoading = true
      return search
        .gePointList(this, args)
        .then(response => {
          this.pointList = response.data
          this.searchLoading = false
        })
        .catch(err => {
          this.searchLoading = false
          console.log(err)
        })
    },
    getMarket(query) {
      this.searchLoading = true
      let args = {
        name: query,
        include: 'area',
        area_id: this.pointForm.area
      }
      return search
        .getMarketList(this, args)
        .then(response => {
          this.marketList = response.data
          if (this.marketList.length == 0) {
            this.pointForm.market = ''
            this.pointForm.marketList = []
          }
          this.searchLoading = false
        })
        .catch(err => {
          console.log(err)
          this.searchLoading = false
        })
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.setting.loading = true
          let edate =
            (new Date(this.pointForm.edate).getTime() +
              ((23 * 60 + 59) * 60 + 59) * 1000) /
            1000
          console.log(edate)
          let args = {
            sdate: new Date(this.pointForm.sdate).getTime() / 1000,
            edate: edate,
            default_plid: this.pointForm.project,
            weekday_tvid: this.pointForm.weekday,
            weekend_tvid: this.pointForm.weekend,
            div_tvid: this.pointForm.define,
            oids: this.pointForm.point
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
                path: '/project/item/index'
              })
              console.log(response)
            })
            .catch(err => {
              this.setting.loading = false
              console.log(err)
            })
        } else {
          console.log('error submit')
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
    .up-area {
      border: 1px dashed #d9d9d9;
      width: 150px;
      height: 150px;
      cursor: pointer;
      position: relative;
    }
    .avatar {
      width: 150px;
      height: 150px;
      display: block;
    }
    .avatar-uploader .el-upload:hover {
      border-color: #409eff;
    }
    .avatar-uploader-icon {
      font-size: 28px;
      color: #8c939d;
      width: 150px;
      height: 150px;
      line-height: 150px;
      text-align: center;
    }
    .delete-icon-image {
      position: absolute;
      top: 5px;
      right: 5px;
      font-size: 20px;
      color: #83909a;
      cursor: pointer;
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
