<template>
  <div 
    class="root">
    <div 
      v-loading="setting.loading" 
      :element-loading-text="setting.loadingText" 
      class="item-list-wrap">
      <div 
        class="item-content-wrap">
        <div 
          class="search-wrap">
          <el-form 
            ref="adSearchForm" 
            :model="adSearchForm" 
            class="search-form">
            <el-row 
              :gutter="24">
              <el-col 
                :span="8">
                <el-form-item 
                  label="" 
                  prop="area_id">
                  <el-select 
                    v-model="adSearchForm.area_id" 
                    placeholder="请选择区域" 
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
              </el-col>
              <el-col 
                :span="8">
                <el-form-item 
                  label="" 
                  prop="market_id">
                  <el-select 
                    v-model="adSearchForm.market_id"
                    :remote-method="getMarket"
                    :loading="searchLoading" 
                    :multiple-limit="1"
                    multiple
                    placeholder="请搜索商场" 
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
              </el-col>
              <el-col 
                :span="8">
                <el-form-item 
                  label="" 
                  prop="point_id">
                  <el-select 
                    v-model="adSearchForm.point_id" 
                    :loading="searchLoading"
                    placeholder="请选择点位"   
                    filterable 
                    clearable>
                    <el-option
                      v-for="item in pointList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row
              :gutter="24">
              <el-col
                :span="8">
                <el-form-item
                  label=""
                  prop="adTrade">
                  <el-select
                    v-model="adSearchForm.ad_trade_id"
                    filterable
                    placeholder="请搜索广告行业"
                    clearable
                    @change="adTradeChangeHandle('search')" >
                    <el-option
                      v-for="item in adTradeList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col
                :span="8">
                <el-form-item
                  label=""
                  prop="ad_plan_id">
                  <el-select
                    v-model="adSearchForm.ad_plan_id"
                    :loading="searchLoading"
                    filterable
                    placeholder="请搜索广告方案"
                    clearable>
                    <el-option
                      v-for="item in adPlanList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col
                :span="8">
                <el-form-item>
                  <el-button
                    type="primary"
                    @click="search('adSearchForm')">搜索</el-button>
                  <el-button
                    @click="resetSearch('adSearchForm')">重置</el-button>
                </el-form-item>
              </el-col>
            </el-row>
          </el-form>
        </div>
        <div 
          class="editCondition-wrap" 
          style="padding: 0 0 15px;">
          <el-form 
            ref="editForm"
            :model="editCondition" 
            :inline="true" 
          >
            <el-form-item 
              label="修改选项" 
              style="margin-bottom: 0;">
              <el-checkbox-group 
                v-model="editCondition.conditionList">
                <el-checkbox 
                  v-for="item in conditionContent" 
                  :label="item" 
                  :key="item"/>
              </el-checkbox-group>
            </el-form-item>
            <el-button 
              type="danger" 
              size="small"
              @click="modifyEdit">修改</el-button>
          </el-form>
        </div>
        <div 
          class="actions-wrap">
          <span 
            class="label">
            方案投放数量: {{ pagination.total }}
          </span>
          <el-button 
            size="small" 
            type="success"
            @click="linkToAddItem">投放广告方案</el-button>
        </div>
        <el-table 
          ref="multipleTable"
          :data="adList" 
          style="width: 100%"
          highlight-current-row 
          @selection-change="handleSelectionChange" 
        >
          <el-table-column 
            type="selection" 
            width="55"/>
          <el-table-column 
            type="expand">
            <template 
              slot-scope="scope">
              <el-form
                label-position="left"
                inline
                class="demo-table-expand">
                <el-form-item
                  label="点位">
                  <span>{{ scope.row.point }}</span>
                </el-form-item>
                <el-form-item
                  label="节目">
                  <span>{{ scope.row.project }}</span>
                </el-form-item>
                <el-form-item
                  label="广告行业">
                  <span>{{ scope.row.ad_trade }}</span>
                </el-form-item>
                <el-form-item
                  label="广告方案">
                  <span>{{ scope.row.ad_plan_name}}</span>
                </el-form-item>
                <el-form-item
                  label="类型">
                  <span>{{ scope.row.ad_plan.type_text}}</span>
                </el-form-item>
                <el-form-item
                  label="状态">
                  <span>{{ scope.row.visiable}}</span>
                </el-form-item>
                <el-form-item
                        label="创建时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
                <el-form-item
                        label="修改时间">
                  <span>{{ scope.row.updated_at }}</span>
                </el-form-item>
                <el-form-item
                        label="开始时间">
                  <span>{{ scope.row.sdate }}</span>
                </el-form-item>
                <el-form-item
                        label="结束时间">
                  <span>{{ scope.row.edate }}</span>
                </el-form-item>
              </el-form>
              <template>
                <el-table
                  :data="scope.row.ad_plan.advertisements.data"
                  style="width: 100%"
                >
                  <el-table-column
                    label="广告行业"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.ad_trade_name }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="创建人"
                    min-width="80">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.create_user_name }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="图片"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span>
                        <img :src="ad_scope.row.img" width="40px"/>
                      </span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="广告名"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.name }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="类型"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.type_text }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="附件"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <a
                        :href="ad_scope.row.link"
                        target="_blank"
                        style="color: blue">{{ad_scope.row.size}}K</a>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="广告标记"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.isad_text }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="显示"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span v-if="ad_scope.row.pivot">
                        {{ad_scope.row.pivot.mode}}
                      </span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="屏幕"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span v-if="ad_scope.row.pivot">
                        {{ad_scope.row.pivot.ori}} <br/>
                        {{ad_scope.row.pivot.screen}}%
                      </span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="倒计时"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span v-if="ad_scope.row.pivot">
                        {{ad_scope.row.pivot.cdshow ?'开启':'关闭'}}<br/>
                        {{ad_scope.row.pivot.ktime}}s
                      </span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="开始时间"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span v-if="ad_scope.row.pivot">
                        {{ ( (Array(4).join('0') + ad_scope.row.pivot.shm).slice(-4)).substring(0,1) + ":"
                        + ( (Array(4).join('0') + ad_scope.row.pivot.shm).slice(-4)).substring(2,3)}}
                      </span>
                    </template>
                  </el-table-column>

                  <el-table-column
                    label="结束时间"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span v-if="ad_scope.row.pivot">
                         {{ ( (Array(4).join('0') + ad_scope.row.pivot.ehm).slice(-4)).substring(0,1) + ":"
                        + ( (Array(4).join('0') + ad_scope.row.pivot.ehm).slice(-4)).substring(2,3)}}
                      </span>
                    </template>
                  </el-table-column>
                </el-table>
              </template>
            </template>
          </el-table-column>
          <el-table-column
            prop="id"
            label="ID"
            min-width="80"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="point"
            label="点位"
            min-width="150"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="project"
            label="节目"
            min-width="80"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="ad_trade"
            label="广告行业"
            min-width="150"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="ad_plan_name"
            label="广告方案"
            min-width="150"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="ad_plan.type_text"
            label="类型"
            min-width="80"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="visiable_text"
            label="状态"
            min-width="80"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="only_text"
            label="唯一性"
            min-width="80"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="created_at"
            label="创建时间"
            min-width="150"
          />
        </el-table>
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
      </div>
      <el-dialog  
        v-loading="loading"
        :visible.sync="editVisible" 
        title="批量修改" 
        @close="dialogClose"
      >
        <el-form
          ref="adForm"
          :model="adForm" 
          label-width="150px">
          <el-form-item 
            v-if="modifyOptionFlag.ad_trade_id" 
            :rules="[{ type: 'number', required: true, message: '请选择广告行业', trigger: 'submit' }]"
            label="广告行业" 
            prop="ad_trade_id">
            <el-select 
              v-model="adForm.ad_trade_id" 
              filterable 
              placeholder="请搜索"
              clearable
              @change="adTradeChangeHandle('edit')" >
              <el-option
                v-for="item in adTradeList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.ad_plan_id"
            :rules="[{ type: 'number', required: true, message: '请选择广告方案', trigger: 'submit' }]"
            label="广告方案"
            prop="ad_plan_id" >
            <el-select 
              v-model="adForm.ad_plan_id"
              :loading="searchLoading" 
              placeholder="请选择" 
              filterable 
              clearable
              @change="adPlanChangeHandle('edit')"
            >
              <el-option
                v-for="item in advertiserFormList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.cycle" 
            :rules="[{ required: true, message: '请输入周期', trigger: 'submit'}]"
            label="周期(s)" 
            prop="cycle">
            <el-input 
              v-model="adForm.cycle" 
              placeholder="请输入周期" />
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.sdate" 
            :rules="[{ type: 'date', required: true, message: '请输入开始时间', trigger: 'submit' }]"
            label="开始时间"
            prop="sdate" >
            <el-date-picker
              v-model="adForm.sdate"
              :editable="false"
              type="date"
              placeholder="选择开始时间" 
            />
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.edate" 
            :rules="[{ type: 'date', required: true, message: '请输入结束时间', trigger: 'submit' }]"
            label="结束时间" 
            prop="edate" >
            <el-date-picker
              v-model="adForm.edate"
              :editable="false"
              type="date"
              placeholder="选择结束时间"
            />
          </el-form-item>
          <el-form-item>
            <el-button 
              type="primary" 
              @click="submitModify('adForm')">完成</el-button>
          </el-form-item>
        </el-form>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import {
  modifyAdLaunch,
  getAdList,
  getSearchAdTradeList,
  getSearchMarketList,
  getSearchPointList,
  getSearchAdvertisementList,
  getSearchAeraList,
  getSearchAdPlanList
} from 'service'

import {
  Button,
  Input,
  Table,
  Select,
  Option,
  Col,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox,
  DatePicker,
  Checkbox,
  CheckboxGroup,
  Dialog,
  Row
} from 'element-ui'

export default {
  components: {
    'el-table': Table,
    'el-date-picker': DatePicker,
    'el-table-column': TableColumn,
    'el-button': Button,
    'el-input': Input,
    'el-pagination': Pagination,
    'el-form': Form,
    'el-select': Select,
    'el-option': Option,
    'el-form-item': FormItem,
    'el-checkbox-group': CheckboxGroup,
    'el-checkbox': Checkbox,
    'el-dialog': Dialog,
    'el-col': Col,
    'el-row': Row
  },
  data() {
    return {
      filters: {
        name: ''
      },
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      conditionContent: [
        '广告行业',
        '广告方案',
        '周期',
        '开始时间',
        '结束时间'
      ],
      editCondition: {
        conditionList: []
      },
      loading: false,
      marketList: [],
      weekdayList: [],
      weekendList: [],
      defineList: [],
      pointList: [],
      adTradeList: [],
      searchLoading: false,
      adPlanList: [],
      advertiserFormList: [],
      advertisementList: [],
      advertisementFormList: [],
      adSearchForm: {
        ad_trade_id: '',
        ad_plan_id: '',
        area_id: '',
        market_id: [],
        point_id: ''
      },
      areaList: [],
      dataValue: '',
      arUserName: '',
      dataShowFlag: true,
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      modifyOptionFlag: {
        ad_trade_id: false,
        ad_plan_id: false,
        cycle: false,
        sdate: false,
        edate: false
      },
      adForm: {
        ad_trade_id: '',
        ad_plan_id: '',
        cycle: 0,
        sdate: '',
        edate: ''
      },
      aoids: [],
      adList: [],
      selectAll: [],
      editVisible: false,
      slectedLength: 0
    }
  },
  created() {
    this.setting.loading = true
    let areaList = this.getAreaList()
    let adTradeList = this.getAdTradeList()
    let adList = this.getAdList()
    Promise.all([areaList, adTradeList, adList])
      .then(() => {
        this.setting.loading = false
      })
      .catch(err => {
        console.log(err)
        this.setting.loading = false
      })
  },
  methods: {
    handleSelectionChange(val) {
      this.selectAll = val
    },
    dialogClose() {
      if (!this.editVisible) {
        this.editCondition.conditionList = []
        this.$refs.multipleTable.clearSelection()
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
    adTradeChangeHandle(type) {
      if (type === 'edit') {
        this.adForm.ad_plan_id = ''
      } else {
        this.adSearchForm.ad_plan_id = ''
      }
      this.getAdPlanList(type)
    },
    getAdPlanList(type) {
      let args = {}
      if (type === 'edit') {
        args = {
          ad_trade_id: this.adForm.ad_trade_id
        }
      } else {
        args = {
          ad_trade_id: this.adSearchForm.ad_trade_id
        }
      }
      this.searchLoading = true
      return getSearchAdPlanList(this, args)
        .then(response => {
          let data = response.data
          if (type === 'edit') {
            this.advertiserFormList = data
          } else {
            this.adPlanList = data
          }
          this.searchLoading = false
        })
        .catch(error => {
          console.log(error)
          this.searchLoading = false
        })
    },
    areaChangeHandle() {
      this.adSearchForm.market_id = []
      this.getMarket()
    },
    getAreaList() {
      return getSearchAeraList(this)
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
      this.adSearchForm.point_id = ''
      this.getPoint()
    },
    getPoint() {
      let args = {
        include: 'market',
        market_id: this.adSearchForm.market_id[0]
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
          area_id: this.adSearchForm.area_id
        }
        return getSearchMarketList(this, args)
          .then(response => {
            this.marketList = response.data
            if (this.marketList.length == 0) {
              this.adSearchForm.market_id = ''
              this.adSearchForm.marketList = []
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
    getAdList() {
      this.setting.loadingText = '拼命加载中'
      this.setting.loading = true
      let searchArgs = {
        page: this.pagination.currentPage,
        ad_trade_id: this.adSearchForm.ad_trade_id,
        ad_plan_id: this.adSearchForm.ad_plan_id,
        market_id: this.adSearchForm.market_id[0],
        point_id: this.adSearchForm.point_id,
        include: 'ad_plan.advertisements'
      }
      this.adSearchForm.ad_trade_id !== ''
        ? searchArgs
        : delete searchArgs.ad_trade_id
      this.adSearchForm.ad_plan_id !== ''
        ? searchArgs
        : delete searchArgs.ad_plan_id
      this.adSearchForm.area_id !== '' ? searchArgs : delete searchArgs.area_id
      this.adSearchForm.market_id.length !== 0
        ? searchArgs
        : delete searchArgs.market_id
      this.adSearchForm.point_id !== ''
        ? searchArgs
        : delete searchArgs.point_id
      return getAdList(this, searchArgs)
        .then(response => {
          let data = response.data
          this.adList = data
          this.pagination.total = response.meta.pagination.total
          this.setting.loading = false
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    search(formName) {
      this.pagination.currentPage = 1
      this.editCondition.conditionList = []
      this.getAdList()
    },
    resetSearch(formName) {
      this.adSearchForm.ad_trade_id = ''
      this.adSearchForm.ad_plan_id = ''
      this.adSearchForm.area_id = ''
      this.adSearchForm.market_id = []
      this.adSearchForm.point_id = ''
      this.pagination.currentPage = 1
      this.editCondition.conditionList = []
      this.getAdList()
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.editCondition.conditionList = []
      this.getAdList()
    },
    modifyEdit() {
      if (this.selectAll.length == 0) {
        this.$message({
          message: '请选择广告',
          type: 'warning'
        })
      } else {
        if (this.editCondition.conditionList.length == 0) {
          this.$message({
            message: '请选择修改项目',
            type: 'warning'
          })
        } else {
          this.getAdTradeList()
          this.adForm = {
            ad_trade_id: '',
            ad_plan_id: '',
            cycle: '',
            sdate: '',
            edate: ''
          }
          this.aoids = []
          let optionModify = this.editCondition.conditionList
          for (let i = 0; i < this.selectAll.length; i++) {
            let id = this.selectAll[i].id
            this.aoids.push(id)
          }
          this.modifyOptionFlag.ad_trade_id = false
          this.modifyOptionFlag.ad_plan_id = false
          this.modifyOptionFlag.cycle = false
          this.modifyOptionFlag.sdate = false
          this.modifyOptionFlag.edate = false
          for (let k = 0; k < optionModify.length; k++) {
            let type = optionModify[k]
            switch (type) {
              case '广告行业':
                this.modifyOptionFlag.ad_trade_id = true
                this.modifyOptionFlag.ad_plan_id = true
                break
              case '广告方案':
                this.modifyOptionFlag.ad_trade_id = true
                this.modifyOptionFlag.ad_plan_id = true
                break
              case '周期':
                this.modifyOptionFlag.cycle = true
                break
              case '开始时间':
                this.modifyOptionFlag.sdate = true
                break
              case '结束时间':
                this.modifyOptionFlag.edate = true
                break
            }
          }
          this.editVisible = true
        }
      }
    },
    submitModify(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.loading = true
          let edate =
            (new Date(this.adForm.edate).getTime() +
              ((23 * 60 + 59) * 60 + 59) * 1000) /
            1000
          let args = {
            sdate: new Date(this.adForm.sdate).getTime() / 1000,
            edate: edate,
            atid: this.adForm.ad_trade_id,
            atiid: this.adForm.ad_plan_id,
            ktime: parseInt(this.adForm.cycle),
            aoids: this.aoids
          }
          this.modifyOptionFlag.ad_trade_id ? args : delete args.atid
          this.modifyOptionFlag.ad_plan_id ? args : delete args.atiid
          this.modifyOptionFlag.cycle ? args : delete args.ktime
          this.modifyOptionFlag.sdate ? args : delete args.sdate
          this.modifyOptionFlag.edate ? args : delete args.edate
          return modifyAdLaunch(this, args)
            .then(response => {
              this.loading = false
              this.$message({
                message: '修改成功',
                type: 'success'
              })
              this.getAdList()
              this.editVisible = false
              this.editCondition.conditionList = []
            })
            .catch(err => {
              this.editVisible = false
              this.editCondition.conditionList = []
              this.loading = false
              console.log(err)
            })
        } else {
          this.loading = false
          return
        }
      })
    },
    linkToAddItem() {
      this.$router.push({
        path: '/ad/item/add'
      })
    }
  }
}
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;
  .item-list-wrap {
    background: #fff;
    padding: 30px;
    .el-select,
    .item-input,
    .el-input {
      width: 380px;
    }

    .el-form-item {
      margin-bottom: 20px;
    }
    .el-table__body-wrapper {
      overflow-x: auto;
      overflow-y: overlay;
      position: relative;
    }
    .demo-table-expand {
      font-size: 0;
    }

    .demo-table-expand label {
      width: 90px;
      color: #99a9bf;
    }
    .demo-table-expand .el-form-item {
      margin-right: 0;
      margin-bottom: 0;
      width: 50%;
    }
    .item-content-wrap {
      .icon-item {
        padding: 10px;
        width: 50%;
      }
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
      .pagination-wrap {
        margin: 10px auto;
        text-align: right;
      }
    }
  }
}
</style>
