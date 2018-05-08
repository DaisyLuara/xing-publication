<template>
  <div class="item-wrap-template" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <div class="topbar">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/program/item' }">节目投放管理</el-breadcrumb-item>
        <el-breadcrumb-item>添加</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="pane">
      <div class="pane-title">
        新增节目投放
      </div>
      <el-form
        ref="projectForm"
        :model="projectForm" label-width="150px">
        <el-form-item label="节目名称" prop="project" :rules="[{ required: true, message: '请输入节目名称', trigger: 'submit' }]">
          <el-select v-model="projectForm.project" filterable placeholder="请搜索" remote :remote-method="getProject" @change="projectChangeHandle">
            <el-option
              v-for="item in projectList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="区域" prop="area"  :rules="[{ required: true, message: '请输入区域', trigger: 'submit' }]">
          <el-select v-model="projectForm.area" placeholder="请选择" filterable @change="areaChangeHandle">
            <el-option
              v-for="item in areaList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="商场" prop="market" :rules="[{ required: true, message: '请输入商场', trigger: 'submit' }]">
          <el-select v-model="projectForm.market"  placeholder="请搜索" filterable :loading="searchLoading" remote :remote-method="getMarket" @change="marketChangeHandle">
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="点位" prop="point" :rules="[{ required: true, message: '请输入点位', trigger: 'submit' }]">
          <el-select v-model="projectForm.point" placeholder="请选择"  multiple filterable @change="pointChangeHandle" :loading="searchLoading">
            <el-option
              v-for="item in pointList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="工作日模版">
          <el-select v-model="projectForm.weekday" placeholder="请选择" filterable>
            <el-option
              v-for="item in weekdayList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="周末模版">
          <el-select v-model="projectForm.weekend" placeholder="请选择" filterable>
            <el-option
              v-for="item in weekendList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="自定义模版">
          <el-select v-model="projectForm.define" placeholder="请选择" filterable>
            <el-option
              v-for="item in defineList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="投放开始时间" prop="sdate" :rules="[{ type: 'date', required: true, message: '请输入投放开始时间', trigger: 'submit' }]">
          <el-date-picker
          v-model="projectForm.sdate"
          type="date"
          placeholder="选择投放开始时间">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="投放结束时间" prop="edate" :rules="[{ type: 'date', required: true, message: '请输入投放结束时间', trigger: 'submit' }]">
          <el-date-picker
          v-model="projectForm.edate"
          type="date"
          placeholder="选择投放结束时间">
          </el-date-picker>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submit('projectForm')">完成</el-button>
        </el-form-item>
      </el-form>
    </div>
    <!-- <div class="pane" v-if="this.step === 2">
      <div class="pane-title">
        定向设置
      </div>
      <el-form
        ref="stepTwo"
        :model="stepTwo" label-width="80px">
        <el-form-item label="节目形式" prop="modality">
          <el-radio-group v-model="stepTwo.modality">
            <el-radio :label="1">硬广</el-radio>
            <el-radio :label="2">应用</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="性别">
          <el-radio-group v-model="stepTwo.gender">
            <el-radio :label="1">男</el-radio>
            <el-radio :label="2">女</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="年龄">
          <el-radio-group v-model="stepTwo.age">
            <el-radio :label="1">0-17</el-radio>
            <el-radio :label="2">18-35</el-radio>
            <el-radio :label="3">36-45</el-radio>
            <el-radio :label="4">46以上</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="颜值">
          <el-radio-group v-model="stepTwo.appearance">
            <el-radio :label="1">低</el-radio>
            <el-radio :label="2">中</el-radio>
            <el-radio :label="3">高</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="人数">
          <el-radio-group v-model="stepTwo.peopleCount">
            <el-radio :label="1">0</el-radio>
            <el-radio :label="2">1</el-radio>
            <el-radio :label="3">2</el-radio>
            <el-radio :label="4">3人以上</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="穿衣指数">
          <el-radio-group v-model="stepTwo.dressingIndex">
            <el-radio :label="1">冷</el-radio>
            <el-radio :label="2">凉</el-radio>
            <el-radio :label="3">舒适</el-radio>
            <el-radio :label="4">热</el-radio>
            <el-radio :label="5">炎热</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="气象指数">
          <el-radio-group v-model="stepTwo.weatherIndex">
            <el-radio :label="1">晴天</el-radio>
            <el-radio :label="2">阴天</el-radio>
            <el-radio :label="3">雨天</el-radio>
            <el-radio :label="4">雾霾</el-radio>
            <el-radio :label="5">雪</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <el-button type="default" size="small" @click="goPreStep">上一步</el-button>
      <el-button type="primary" size="small" @click="goNextStep">下一步</el-button>
    </div>
    <div class="pane" v-if="this.step === 3" v-show="playType">
      <div class="pane-title">
        节目设置<el-button type="success" size="small" @click="addProgram">增加</el-button>
      </div>
      <el-form
        ref="stepThree"
        :model="stepThree" label-width="80px">
        <div class="item-list" v-for="item in items" :key="item.id">
          <label class="program-title">
            节目 {{item.id}}
            <el-button type="danger" size="small" @click="removeProgram(item.id)">删除</el-button>
          </label>
          <el-form-item label="播放类型" prop="playType">
            <el-radio-group v-model="stepThree.playType">
              <el-radio :label="1">大屏播放</el-radio>
              <el-radio :label="2">小屏播放</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="素材类型">
            <el-radio-group v-model="stepThree.materialType">
              <el-radio :label="1">图片</el-radio>
              <el-radio :label="2">视频</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="上传素材">
            <div class="upload-block">
              <div class="up-area-cover">
                <img v-if="stepThree.imageUrl" :src="stepThree.imageUrl" class="cover">
                <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane"></i>
                <i v-if="stepThree.imageUrl !== ''" class="el-icon-circle-cross delete-icon-image" @click="handleImageDelete()"></i>
                <span>1920 x 1080 ( 推荐尺寸，jpg / png )</span>
              </div>
            </div>
          </el-form-item>
          <el-form-item label="转场效果">
            <el-radio-group v-model="stepThree.transitionType">
              <el-radio :label="1">淡进淡出</el-radio>
              <el-radio :label="2">放大缩小</el-radio>
              <el-radio :label="3">旋转大小</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="播放时间">
            <el-radio-group v-model="stepThree.playDate">
              <el-radio :label="1">6秒</el-radio>
              <el-radio :label="2">15秒</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="落地页面">
            <el-radio-group v-model="stepThree.pageType">
              <el-radio :label="1">H5</el-radio>
              <el-radio :label="2">小程序</el-radio>
              <el-radio :label="3">公众号</el-radio>
            </el-radio-group>
            <label style="margin-left: 30px;">修改落地页，广告不下线</label>
            <el-switch v-model="switchValue" ></el-switch>
          </el-form-item>
          <hr class="hr-line"/>
        </div>
      </el-form>
      <el-button type="default" size="small" @click="goPreStep">上一步</el-button>
      <el-button type="primary" size="small" @click="goNextStep">下一步</el-button>
    </div>
    <div class="pane" v-if="this.step === 3" v-show="!playType">
      <div class="pane-title">
        节目设置
      </div>
      <el-form
        ref="stepThree"
        :model="stepThree" label-width="80px">
        <div class="">
          <el-form-item label="上传应用">
            <div class="upload-block">
              <div class="up-area-cover">
                <img v-if="stepThree.imageUrl" :src="stepThree.imageUrl" class="cover">
                <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane"></i>
                <i v-if="stepThree.imageUrl !== ''" class="el-icon-circle-cross delete-icon-image" @click="handleImageDelete()"></i>
                <span>9:16竖屏，最大不超过100MB</span>
              </div>
            </div>
          </el-form-item>
          <el-form-item label="落地页面">
            <el-radio-group v-model="stepThree.pageType">
              <el-radio :label="1">H5</el-radio>
              <el-radio :label="2">小程序</el-radio>
              <el-radio :label="3">公众号</el-radio>
            </el-radio-group>
          </el-form-item>
        </div>
      </el-form>
      <el-button type="default" size="small" @click="goPreStep">上一步</el-button>
      <el-button type="primary" size="small" @click="goNextStep">下一步</el-button>
    </div>
    <div class="pane" v-if="this.step === 4">
      <div class="pane-title">
        提交信息
      </div>
      <div>
        <el-card class="box-card">
          <div slot="header" class="clearfix header">
            <span>信息预览</span>
          </div>
          <div class="preview-content">
            <div class="preview-info-line">
              <el-row>
                <el-col :span="2"><label class="preview-title">所属客户:</label></el-col>
                <el-col :span="10"><label class="preview-content">测试客户</label></el-col>
              </el-row>
            </div>
            <div class="preview-info-line">
              <el-row>
                <el-col :span="2"><label class="preview-title">节目合约:</label></el-col>
                <el-col :span="10"><label class="preview-content">测试客户</label></el-col>
              </el-row>
            </div>
            <div class="preview-info-line">
              <el-row>
                <el-col :span="2"><label class="preview-title">节目名称:</label></el-col>
                <el-col :span="10"><label class="preview-content">测试客户</label></el-col>
              </el-row>
            </div>
            <div class="preview-info-line">
              <el-row>
                <el-col :span="2"><label class="preview-title">发布位置:</label></el-col>
                <el-col :span="10"><label class="preview-content">上海浦东新区八佰伴1楼</label></el-col>
              </el-row>
            </div>
            <div class="preview-info-line">
              <el-row>
                <el-col :span="2"><label class="preview-title">投放日期:</label></el-col>
                <el-col :span="10"><label class="preview-content">2018-09-09</label></el-col>
              </el-row>
            </div>
            <div class="preview-info-line">
              <el-row>
                <el-col :span="2"><label class="preview-title">投放时段:</label></el-col>
                <el-col :span="10"><label class="preview-content">2018-09-09 - 2018-10-09</label></el-col>
              </el-row>
            </div>
          </div>
        </el-card>
      </div>
      <el-form
        ref="stepTwo"
        :model="stepFour" label-width="80px">
        <el-form-item label="审核人">
          <el-select v-model="stepFour.checkPeople" filterable placeholder="请选择">
            <el-option
              v-for="item in checkOptions"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <el-button type="default" size="small" @click="goPreStep">上一步</el-button>
      <el-button type="primary" size="small" @click="submit">确定发布</el-button>
    </div> -->
  </div>
</template>

<script>
import search from 'service/search'
import {
  Form,
  Select,
  Option,
  FormItem,
  Button,
  Input,
  DatePicker,
  MessageBox,
} from 'element-ui'

export default {
  components: {
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input,
    ElDatePicker: DatePicker,
  },
  data() {
    return {
      // items:[{ message: 'Foo',id:0 }],
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      // playType: true,
      // switchValue: '',
      // userID: '',
      // step: 1,
      marketList: [],
      // loading: false,
      weekdayList: [],
      weekendList: [],
      defineList: [],
      pointList: [],
      projectList: [],
      searchLoading: false,
      projectForm: {
        project: '',
        area: '',
        market: '',
        point: [],
        weekday: '',
        weekend: '',
        define: '',
        sdate: '',
        edate: '',
      },
      areaList: [{
        value: '选项1',
        label: '上海'
      }, {
        value: '选项2',
        label: '苏州'
      }],
    }
  },
  mounted() {
  },
  created() {
    this.setting.loading = true
    let moduleList = this.getModuleList()
    let areaList = this.getAreaList()
    Promise.all([moduleList, areaList]).then(() => {
      // this.matchStoreIdToName()
      this.setting.loading = false
    }).catch((err) => {
      console.log(err)
      this.setting.loading = false
    })
  },
  methods: {
    projectChangeHandle() {
      console.log(this.projectForm.project)
    },
    getProject(query) {
      this.searchLoading = true
      let args = {
        name: query,
      }
      return search.getProjectList(this,args).then((response) => {
        this.projectList = response.data
        if(this.projectList.length == 0) {
          this.projectForm.project = ''
          this.projectForm.projectList = []
        }
        this.searchLoading = false
      }).catch(err => {
        console.log(err)
        this.searchLoading = false
      })
    },
    getModuleList() {
      return search.getModuleList(this).then((response) => {
       let data = response.data
       this.weekdayList = data
       this.weekendList = data
       this.defineList = data
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    areaChangeHandle() {
      console.log(this.projectForm.area)
      this.projectForm.market = ''
      this.getMarket(this.projectForm.market)
    },
    getAreaList () {
      return search.getAeraList(this).then((response) => {
       let data = response.data
       this.areaList = data
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    marketChangeHandle() {
      console.log(this.projectForm.market)
      this.projectForm.point = []
      this.getPoint()
    },
    pointChangeHandle() {
      console.log(this.projectForm.point)
    },
    getPoint() {
      let args = {
        include: 'market',
        market_id: this.projectForm.market
      }
      this.searchLoading = true
      return search.gePointList(this, args).then((response) => {
        console.log(response)
        this.pointList = response.data
        this.searchLoading = false
      }).catch(err => {
        this.searchLoading = false
        console.log(err)
      })
    },
    getMarket(query) {
      this.searchLoading = true
      let args = {
        name: query,
        include: 'area',
        area_id: this.projectForm.area
      }
      return search.getMarketList(this,args).then((response) => {
        this.marketList = response.data
        if(this.marketList.length == 0) {
          this.projectForm.market = ''
          this.projectForm.marketList = []
        }
        this.searchLoading = false
      }).catch(err => {
        console.log(err)
        this.searchLoading = false
      })
    },
    submit(formName) {
      this.$refs[formName].validate((valid) => {
        if(valid){
          console.log('submit')
        }else{
          console.log('error submit');
          return;
        }
      })
    },
    addProgram() {
      let i = this.items.length
      i++
      this.items.push({
        message: 'Bar',
        id: i
      })
      // console.log(i)
    },
    removeProgram(index) {
      console.log(index)
      this.items.splice(index,1)
      console.log(this.items)
    },
    goPreStep() {
      this.step--
    },
    goNextStep() {
      this.step++
    }
  },
}
</script>

<style lang="less" scoped>
  .item-wrap-template {
    .pane {
      border-radius: 5px;
      background-color: white;
      padding: 20px 40px 80px;

      .el-select,.item-input,.el-date-editor.el-input{
        width: 380px;
      }
      .item-list{
        .program-title{
          color: #555;
          font-size: 14px;
        }
        .hr-line{
          width: 800px;
          border-top: 1px solid #a7a3a3;
        }
      }
      .pane-title {
        padding-bottom: 20px;
        font-size: 18px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
      }
      .pane-input {
        width: 350px;
      }
      .upload-block{
        padding-bottom: 20px;
        .up-area-cover {
          border: 1px dashed #d9d9d9;
          width: 228px;
          height: 228px;
          cursor: pointer;
          position: relative;
          .cover{
            width: 228px;
            height: 228px;
            display: block
          }
          .cover-uploader-icon {
            font-size: 28px;
            color: #8c939d;
            width: 228px;
            height: 228px;
            line-height: 228px;
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
        }
      }
      .box-card{
        width: 900px;
        margin-bottom: 50px;
        .header{
          color: #444;
          font-size: 16px;
          font-weight: 600;
        }
        .preview-content{
          .preview-info-line{
            padding: 10px 0;
            .preview-title{
              color: #ccc;
              font-size: 16px;
            }
            .preview-content{
              color: #444;
              font-size: 16px;
            }
          }
        }
      }
    }
    .button-area {
      display: flex;
      flex-direction: row;
      justify-content: flex-end;
    }
    .button-area-begin {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
    }
  }
</style>
