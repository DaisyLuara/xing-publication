<template>
  <div class="item-wrap-template" >
    <div class="topbar">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/ad/item/index' }">广告投放管理</el-breadcrumb-item>
        <el-breadcrumb-item>添加</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="pane" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="pane-title">
        新增广告投放
      </div>
      <el-form
        ref="adForm"
        :model="adForm" label-width="150px">
        <el-form-item label="广告行业" prop="adTrade" :rules="[{ required: true, message: '请输入广告行业名称', trigger: 'submit',type: 'number'}]">
          <el-select v-model="adForm.adTrade" filterable placeholder="请搜索" @change="adTradeChangeHandle">
            <el-option
              v-for="item in adTradeList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="广告主" prop="advertiser" :rules="[{ required: true, message: '请输入广告主名称', trigger: 'submit',type: 'number'}]">
          <el-select v-model="adForm.advertiser" filterable placeholder="请搜索" @change="advertiserChangeHandle" :loading="searchLoading">
            <el-option
              v-for="item in advertiserList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
         <el-form-item label="广告" prop="advertisement" :rules="[{ required: true, message: '请输入广告名称', trigger: 'submit',type: 'number'}]">
          <el-select v-model="adForm.advertisement" filterable  placeholder="请搜索" :loading="searchLoading" @change="advertisementChangeHandle">
            <el-option
              v-for="item in advertisementList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="区域" prop="area"  :rules="[{ required: true, message: '请输入区域', trigger: 'submit' ,type: 'number'}]">
          <el-select v-model="adForm.area" placeholder="请选择" filterable @change="areaChangeHandle">
            <el-option
              v-for="item in areaList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="商场" prop="market" :rules="[{ required: true, message: '请输入商场', trigger: 'submit' ,type: 'number'}]">
          <el-select v-model="adForm.market"  placeholder="请搜索" filterable :loading="searchLoading" remote :remote-method="getMarket" @change="marketChangeHandle">
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="点位" prop="point" :rules="[{ required: true, message: '请输入点位', trigger: 'submit',type: 'array'}]">
          <el-select v-model="adForm.point" placeholder="请选择"  multiple filterable :loading="searchLoading" :multiple-limit="10">
            <el-option
              v-for="item in pointList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="周期(s)">
          <el-input v-model="adForm.cycle" placeholder="请输入周期" style="width:380px;"></el-input>
        </el-form-item>
        <el-form-item label="开始时间" prop="sdate">
          <el-date-picker
          v-model="adForm.sdate"
          type="date"
          placeholder="选择开始时间" :editable="false"  :clearable="false">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="结束时间" prop="edate">
          <el-date-picker
          v-model="adForm.edate"
          type="date"
          placeholder="选择结束时间"
          :editable="false"
          :clearable="false"
          >
          </el-date-picker>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submit('adForm')">完成</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import search from 'service/search'
import ad from 'service/ad'
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
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      marketList: [],
      weekdayList: [],
      weekendList: [],
      defineList: [],
      pointList: [],
      adTradeList: [],
      searchLoading: false,
      advertiserList: [],
      advertisementList:[],
      adForm: {
        adTrade: '',
        advertiser: '',
        advertisement: '',
        cycle: 0,
        area: '',
        market: '',
        point: [],
        sdate: '',
        edate: '',
      },
      areaList: [],
    }
  },
  mounted() {
  },
  created() {
    this.setting.loading = true
    let areaList = this.getAreaList()
    let adTradeList = this.getAdTradeList()
    Promise.all([areaList, adTradeList]).then(() => {
      this.setting.loading = false
    }).catch((err) => {
      console.log(err)
      this.setting.loading = false
    })
  },
  methods: {
    getAdTradeList(){
      return search.getAdTradeList(this).then((response) => {
       let data = response.data
       this.adTradeList = data
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    adTradeChangeHandle() {
      this.adForm.advertiser = ''
      this.getAdvertiserList()
    },
    advertisementChangeHandle (){
      console.log(this.adForm.advertisement)
    },
    advertiserChangeHandle(){
      this.adForm.advertisement = ''
      this.getAdvertisementList()
    },
    getAdvertisementList() {
      let args = {
        advertiser_id: this.adForm.advertiser
      }
      this.searchLoading = true
      return search.getAdvertisementList(this, args).then((response) => {
        let data = response.data
        this.advertisementList = data
        this.searchLoading = false
      }).catch(error => {
        console.log(error)
        this.searchLoading = false
      })
    },
    getAdvertiserList() {
      let args = {
        ad_trade_id: this.adForm.adTrade
      }
      this.searchLoading = true
      return search.getAdvertiserList(this, args).then((response) => {
        let data = response.data
        this.advertiserList = data
        this.searchLoading = false
      }).catch(error => {
        console.log(error)
      this.searchLoading = false
      })
    },
    areaChangeHandle() {
      this.adForm.market = ''
      this.getMarket(this.adForm.market)
    },
    getAreaList () {
      this.searchLoading = true
      return search.getAeraList(this).then((response) => {
       let data = response.data
       this.areaList = data
        this.searchLoading = false
      }).catch(error => {
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
        market_id: this.adForm.market
      }
      this.searchLoading = true
      return search.gePointList(this, args).then((response) => {
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
        area_id: this.adForm.area
      }
      return search.getMarketList(this,args).then((response) => {
        this.marketList = response.data
        if(this.marketList.length == 0) {
          this.adForm.market = ''
          this.adForm.marketList = []
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
        this.setting.loading = true
          let args = {
            sdate: new Date(this.adForm.sdate).getTime() / 1000,
            edate: new Date(this.adForm.edate).getTime() / 1000,
            atid: this.adForm.adTrade,
            atiid: this.adForm.advertiser,
            aid: this.adForm.advertisement,
            marketid: this.adForm.market,
            areaid: this.adForm.area,
            oids: this.adForm.point,
            ktime: parseInt(this.adForm.cycle)
          }
          return ad.saveAdLaunch(this, args).then((response) => {
            this.setting.loading = false
            this.$message({
              message: "添加成功",
              type: "success"
            })
            this.$router.push({
              path: "/ad/item/index"
            })
          }).catch((err) => {
            this.setting.loading = false
            console.log(err)
          })
        }else{
          console.log('error submit');
          return;
        }
      })
    },
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
