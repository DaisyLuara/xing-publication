<template>
  <div 
    class="item-wrap-template" >
    <div 
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="pane" >
      <div 
        class="pane-title">
        新增广告投放
      </div>
      <el-form
        ref="adForm"
        :model="adForm" 
        label-width="150px">
        <el-form-item 
          :rules="[{ required: true, message: '请输入广告行业名称', trigger: 'submit',type: 'number'}]"
          label="广告行业"
          prop="ad_trade_id" >
          <el-select 
            v-model="adForm.ad_trade_id"
            filterable 
            placeholder="请搜索" 
            clearable
            @change="adTradeChangeHandle">
            <el-option
              v-for="item in adTradeList"
              :key="item.id"
              :label="item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请输入广告模版名称', trigger: 'submit',type: 'number'}]"
          label="广告模版"
          prop="ad_plan_id">
          <el-select 
            v-model="adForm.ad_plan_id"
            :loading="searchLoading" 
            filterable 
            placeholder="请搜索" 
            clearable>
            <el-option
              v-for="item in adPlanList"
              :key="item.id"
              :label="item.name+'('+item.type_text+')'"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请输入区域', trigger: 'submit' ,type: 'number'}]"
          label="区域" 
          prop="area">
          <el-select 
            v-model="adForm.area" 
            placeholder="请选择" 
            filterable 
            clearable
            @change="areaChangeHandle" >
            <el-option
              v-for="item in areaList"
              :key="item.id"
              :label="item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请输入商场', trigger: 'submit'}]" 
          label="商场" 
          prop="market">
          <el-select
            v-model="adForm.market"  
            :remote-method="getMarket" 
            :loading="searchLoading" 
            :multiple-limit="1"
            multiple
            placeholder="请搜索" 
            filterable
            remote
            clearable
            @change="marketChangeHandle" >
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item 
          :rules="[{ required: true, message: '请输入点位', trigger: 'submit',type: 'array'}]"
          label="点位" 
          prop="point">
          <el-select 
            v-model="adForm.point"  
            :loading="searchLoading" 
            :multiple-limit="10" 
            placeholder="请选择"  
            multiple 
            filterable
            clearable>
            <el-option
              v-for="item in pointList"
              :key="item.id"
              :label="item.name"
              :value="item.id"/>
          </el-select>
        </el-form-item>

        <el-form-item
          :rules="[{ required: true, message: '请输入节目', trigger: 'submit',type: 'number'}]"
          label="节目"
          prop="piid">
          <el-select
            v-model="adForm.piid"
            :loading="searchLoading"
            :remote-method="getProject"
            remote
            placeholder="请输入节目名称"
            filterable
            clearable
          >
            <el-option
              v-for="item in projectList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>

        <el-form-item 
          label="开始时间" 
          prop="sdate">
          <el-date-picker
            v-model="adForm.sdate"
            :editable="false"  
            :clearable="false"
            type="datetime"
            placeholder="选择开始时间" 
          />
        </el-form-item>
        <el-form-item 
          label="结束时间" 
          prop="edate">
          <el-date-picker
            v-model="adForm.edate"
            :editable="false"
            :clearable="false"
            type="datetime"
            placeholder="选择结束时间"
          />
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择状态', trigger: 'submit'}]"
          label="状态"
          prop="visiable">
          <el-radio 
            v-model="adForm.visiable" 
            :label="1">运营中</el-radio>
          <el-radio 
            v-model="adForm.visiable" 
            :label="0">下架</el-radio>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请选择唯一性', trigger: 'submit'}]"
          label="唯一"
          prop="only">
          <el-radio 
            v-model="adForm.only" 
            :label="1">是</el-radio>
          <el-radio 
            v-model="adForm.only" 
            :label="0">否</el-radio>
        </el-form-item>
        <el-form-item>
          <el-button 
            type="primary" 
            @click="submit('adForm')">完成</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import {
  saveAdLaunch,
  getSearchAdTradeList,
  getSearchAdPlanList,
  getSearchPointList,
  getSearchAeraList,
  getSearchMarketList,
  getSearchProjectList,
} from 'service'

import {
  Radio,
  Form,
  Select,
  Option,
  FormItem,
  Button,
  Input,
  DatePicker,
  MessageBox
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
    ElRadio:Radio
  },
  data() {
    return {
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      marketList: [],
      weekdayList: [],
      weekendList: [],
      defineList: [],
      pointList: [],
      adTradeList: [],
      searchLoading: false,
      adPlanList: [],
      adForm: {
        ad_trade_id: '',
        ad_plan_id: '',
        area: '',
        market: [],
        point: [],
        piid: null,
        sdate: '',
        edate: '',
        visiable : 1,
        only : 1,
      },
      areaList: [],
      projectList:[],
    }
  },
  mounted() {},
  created() {
    this.setting.loading = true
    let areaList = this.getAreaList()
    let adTradeList = this.getAdTradeList()
    Promise.all([areaList, adTradeList])
      .then(() => {
        this.setting.loading = false
      })
      .catch(err => {
        console.log(err)
        this.setting.loading = false
      })
  },
  methods: {
    getProject(query) {
      if (query !== "") {
        this.searchLoading = true;
        let args = {
          name: query
        };
        return getSearchProjectList(this, args)
          .then(response => {
            this.projectList = response.data;
            if (this.projectList.length === 0) {
              this.filters.alias = "";
              this.projectList = [];
            }
            this.searchLoading = false;
          })
          .catch(err => {
            this.searchLoading = false;
          });
      } else {
        this.projectList = [];
      }
    },
    getAdTradeList() {
      return getSearchAdTradeList(this)
        .then(response => {
          let data = response.data
          this.adTradeList = data
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    adTradeChangeHandle() {
      this.adForm.ad_plan_id = ''
      this.getAdPlanList()
    },
    getAdPlanList() {
      let args = {
        ad_trade_id: this.adForm.ad_trade_id
      }
      this.searchLoading = true
      return getSearchAdPlanList(this, args)
        .then(response => {
          let data = response.data
          this.adPlanList = data
          this.searchLoading = false
        })
        .catch(error => {
          console.log(error)
          this.searchLoading = false
        })
    },
    areaChangeHandle() {
      this.adForm.market = []
      this.adForm.point = []
      this.getMarket(this.adForm.market[0])
    },
    getAreaList() {
      this.searchLoading = true
      return getSearchAeraList(this)
        .then(response => {
          let data = response.data
          this.areaList = data
          this.searchLoading = false
        })
        .catch(error => {
          console.log(error)
          this.searchLoading = false
        })
    },
    marketChangeHandle() {
      this.adForm.point = []
      this.getPoint()
    },
    getPoint() {
      let args = {
        include: 'market',
        market_id: this.adForm.market[0]
      }
      this.searchLoading = true
      return getSearchPointList(this, args)
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
      if (query !== '') {
        this.searchLoading = true
        let args = {
          name: query,
          include: 'area',
          area_id: this.adForm.area
        }
        return getSearchMarketList(this, args)
          .then(response => {
            this.marketList = response.data
            if (this.marketList.length == 0) {
              this.adForm.market = []
              this.adForm.marketList = []
            }
            this.searchLoading = false
          })
          .catch(err => {
            console.log(err)
            this.searchLoading = false
          })
      } else {
        this.marketList = []
      }
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let edate =
            (new Date(this.adForm.edate).getTime() +
              ((23 * 60 + 59) * 60 + 59) * 1000) /
            1000
          this.setting.loading = true
          let args = {
            sdate: this.adForm.sdate,
            edate: this.adForm.edate,
            atiid: this.adForm.ad_plan_id,
            marketid: this.adForm.market[0],
            oids: this.adForm.point,
            piid: this.adForm.piid,
            visiable:this.adForm.visiable,
            only:this.adForm.only,
          }
          return saveAdLaunch(this, args)
            .then(response => {
              this.setting.loading = false
              this.$message({
                message: '添加成功',
                type: 'success'
              })
              this.$router.push({
                path: '/ad/item'
              })
            })
            .catch(err => {
              this.setting.loading = false
              this.$message({
                message: err.response.data.message,
                type: 'error'
              })
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
