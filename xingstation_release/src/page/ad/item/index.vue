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
                :span="6">
                <el-form-item
                  label=""
                  prop="area_id">
                  <el-select
                    v-model="adSearchForm.area_id"
                    placeholder="请选择区域"
                    filterable
                    clearable
                    @change="areaChangeHandle">
                    <el-option
                      v-for="item in areaList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col
                :span="6">
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
                    @change="marketChangeHandle">
                    <el-option
                      v-for="item in marketList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col
                :span="6">
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
                :span="6">
                <el-form-item
                  label=""
                  prop="adTrade">
                  <el-select
                    v-model="adSearchForm.ad_trade_id"
                    filterable
                    placeholder="请搜索广告行业"
                    clearable
                    @change="adTradeChangeHandle('search')">
                    <el-option
                      v-for="item in adTradeList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col
                :span="6">
                <el-form-item
                  label=""
                  prop="ad_plan_id">
                  <el-select
                    v-model="adSearchForm.ad_plan_id"
                    :loading="searchLoading"
                    filterable
                    placeholder="请搜索广告模版"
                    clearable>
                    <el-option
                      v-for="item in adPlanList"
                      :key="item.id"
                      :label="item.name+'('+item.type_text+')'"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col
                :span="6">
                <el-form-item
                  prop="type">
                  <el-select
                    v-model="adSearchForm.type"
                    :loading="searchLoading"
                    placeholder="请选择类型"
                    clearable>
                    <el-option 
                      key="program"
                      label="节目广告" 
                      value="program"/>
                    <el-option 
                      key="ads"
                      label="小屏广告" 
                      value="ads"/>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col
                :span="6">
                <el-form-item>
                  <el-button
                    type="primary"
                    @click="search('adSearchForm')">搜索
                  </el-button>
                  <el-button
                    @click="resetSearch('adSearchForm')">重置
                  </el-button>
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
              @click="modifyEdit">修改
            </el-button>
          </el-form>
        </div>
        <div
          class="actions-wrap">
          <span
            class="label">
            模版投放数量: {{ pagination.total }}
          </span>
          <el-button
            size="small"
            type="success"
            @click="linkToAddItem">投放广告模版
          </el-button>
        </div>
        <el-table
          ref="multipleTable"
          :data="adLaunchList"
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
                  label="广告模版">
                  <span>{{ scope.row.ad_plan_name }}</span>
                </el-form-item>
                <el-form-item
                  label="类型">
                  <span>{{ scope.row.ad_plan.type_text }}</span>
                </el-form-item>
                <el-form-item
                  label="状态">
                  <span>{{ scope.row.visiable_text }}</span>
                </el-form-item>
                <el-form-item
                  label="唯一性">
                  <span>{{ scope.row.only ? '是' : '否' }}</span>
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
                    :show-overflow-tooltip="true"
                    label="广告行业"
                    min-width="80">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.ad_trade_name }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    :show-overflow-tooltip="true"
                    label="创建人"
                    min-width="60">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.create_user_name }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="类型"
                    min-width="60">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.type_text }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    :show-overflow-tooltip="true"
                    label="素材名称"
                    min-width="100">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.name }}</span>
                      <br/>
                      <span>
                        <img
                          :src="(ad_scope.row.type === 'static' || ad_scope.row.type === 'gif' ) ? ad_scope.row.link : ad_scope.row.img"
                          width="40px">
                      </span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    :show-overflow-tooltip="true"
                    label="附件"
                    min-width="80">
                    <template slot-scope="ad_scope">
                      <a
                        :href="ad_scope.row.link"
                        target="_blank"
                        style="color: blue">
                        <i class="el-icon-download"></i>
                        {{ ad_scope.row.size }}M
                      </a>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="广告标记"
                    min-width="80">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.isad_text }}</span>
                    </template>
                  </el-table-column>
                  <template v-if="scope.row.ad_plan.type==='program'">
                    <el-table-column
                      :show-overflow-tooltip="true"
                      label="素材显示格式"
                      min-width="130">
                      <template slot-scope="ad_scope">
                        <span v-if="ad_scope.row.pivot">
                          模式：{{ modeOptions[ad_scope.row.pivot.mode] }}<br/>
                          位置：{{ oriOptions[ad_scope.row.pivot.ori] }} <br/>
                          尺寸：{{ ad_scope.row.pivot.screen }}%
                        </span>
                      </template>
                    </el-table-column>
                  </template>

                  <el-table-column
                    label="素材投放时间"
                    v-if="scope.row.ad_plan.tmode === 'hours'"
                    min-width="100">
                    <template slot-scope="ad_scope">
                      <span style="color: #67C23A"><i class="el-icon-rank"></i></span>
                      <span v-if="ad_scope.row.pivot">
                        {{ (ad_scope.row.pivot.shm).toString().substring(ad_scope.row.pivot.shm.toString().length-2) }}
                      </span>
                      至
                      <span v-if="ad_scope.row.pivot">
                        {{ (ad_scope.row.pivot.ehm).toString().substring(ad_scope.row.pivot.ehm.toString().length-2) }}
                      </span>
                      分
                    </template>
                  </el-table-column>

                  <el-table-column
                    label="素材投放时间"
                    v-else
                    min-width="130">
                    <template
                      slot-scope="ad_scope">
                      <span style="color: #67C23A"><i class="el-icon-time"></i></span>
                      <span v-if="ad_scope.row.pivot">
                        {{ ( (Array(4).join('0') + ad_scope.row.pivot.shm).slice(-4)).substring(0,2) + ":"
                        + ( (Array(4).join('0') + ad_scope.row.pivot.shm).slice(-4)).substring(2) }}
                      </span>
                      至
                      <span v-if="ad_scope.row.pivot">
                        {{ ( (Array(4).join('0') + ad_scope.row.pivot.ehm).slice(-4)).substring(0,2) + ":"
                        + ( (Array(4).join('0') + ad_scope.row.pivot.ehm).slice(-4)).substring(2) }}
                      </span>
                    </template>
                  </el-table-column>

                  <el-table-column
                    label="倒计时"
                    min-width="80">
                    <template slot-scope="ad_scope">
                      <span v-if="ad_scope.row.pivot">
                        {{ ad_scope.row.pivot.cdshow ?'开启':'关闭' }}<br>
                        {{ ad_scope.row.pivot.ktime ? ad_scope.row.pivot.ktime + '秒' : '默认时长'}}
                      </span>
                    </template>
                  </el-table-column>

                  <el-table-column
                    label="状态"
                    min-width="65">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.pivot.visiable === 1 ? '运营中' : '下架' }}</span>
                    </template>
                  </el-table-column>
                </el-table>
              </template>
            </template>
          </el-table-column>
          <el-table-column
            prop="id"
            label="ID"
            min-width="50"
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
            min-width="80"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="ad_plan_name"
            label="广告模版"
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
              @change="adTradeChangeHandle('edit')">
              <el-option
                v-for="item in adTradeList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item
            v-if="modifyOptionFlag.ad_plan_id"
            :rules="[{ type: 'number', required: true, message: '请选择广告模版', trigger: 'submit' }]"
            label="广告模版"
            prop="ad_plan_id">
            <el-select
              v-model="adForm.ad_plan_id"
              :loading="searchLoading"
              placeholder="请选择"
              filterable
              clearable
            >
              <el-option
                v-for="item in advertiserFormList"
                :key="item.id"
                :label="item.name+'('+item.type_text+')'"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item
            v-if="modifyOptionFlag.sdate"
            :rules="[{ type: 'date', required: true, message: '请输入开始时间', trigger: 'submit' }]"
            label="开始时间"
            prop="sdate">
            <el-date-picker
              v-model="adForm.sdate"
              :editable="false"
              type="datetime"
              placeholder="选择开始时间"
            />
          </el-form-item>
          <el-form-item
            v-if="modifyOptionFlag.edate"
            :rules="[{ type: 'date', required: true, message: '请输入结束时间', trigger: 'submit' }]"
            label="结束时间"
            prop="edate">
            <el-date-picker
              v-model="adForm.edate"
              :editable="false"
              type="datetime"
              placeholder="选择结束时间"
            />
          </el-form-item>
          <el-form-item
            v-if="modifyOptionFlag.visiable"
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
            v-if="modifyOptionFlag.only"
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
              @click="submitModify('adForm')">完成
            </el-button>
          </el-form-item>
        </el-form>
      </el-dialog>
    </div>
  </div>
</template>

<script>
  import {
    modifyAdLaunch,
    getAdLaunchList,
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
    Row,
    Radio
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
      'el-row': Row,
      'el-radio': Radio
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
          '广告模版',
          '开始时间',
          '结束时间',
          '状态',
          '唯一性'
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
          point_id: '',
          type: '',
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
          sdate: false,
          edate: false,
          visiable: false,
          only: false,
        },
        adForm: {
          ad_trade_id: '',
          ad_plan_id: '',
          sdate: '',
          edate: '',
          visiable: 1,
          only: 1
        },
        aoids: [],
        adLaunchList: [],
        selectAll: [],
        editVisible: false,
        slectedLength: 0,

        modeOptions: {
          'fullscreen': '全屏显示',
          'unmanned': '无人互动',
          'qrcode': '二维码页面',
          'qrcode': '二维码页',
          'floating': '浮窗显示',
        },

        oriOptions: {
          'center': '居中',
          'top': '顶部居中',
          'bottom': '底部居中',
          'left_top': '左上角',
          'left': '左侧居中',
          'left_bottom': '左下角',
          'right_top': '右上角',
          'right': '右侧居中',
          'right_bottom': '右下角',
          'center': '居中',
        }

      }
    },
    created() {
      this.setting.loading = true
      let areaList = this.getAreaList()
      let adTradeList = this.getAdTradeList()
      let adLaunchList = this.getAdLaunchList()
      Promise.all([areaList, adTradeList, adLaunchList])
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
      getAdLaunchList() {
        this.setting.loadingText = '拼命加载中'
        this.setting.loading = true
        let searchArgs = {
          page: this.pagination.currentPage,
          ad_trade_id: this.adSearchForm.ad_trade_id,
          ad_plan_id: this.adSearchForm.ad_plan_id,
          market_id: this.adSearchForm.market_id[0],
          point_id: this.adSearchForm.point_id,
          type: this.adSearchForm.type,
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
        return getAdLaunchList(this, searchArgs)
          .then(response => {
            let data = response.data
            this.adLaunchList = data
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
        this.getAdLaunchList()
      },
      resetSearch(formName) {
        this.adSearchForm.ad_trade_id = ''
        this.adSearchForm.ad_plan_id = ''
        this.adSearchForm.area_id = ''
        this.adSearchForm.market_id = []
        this.adSearchForm.point_id = ''
        this.pagination.currentPage = 1
        this.editCondition.conditionList = []
        this.getAdLaunchList()
      },
      changePage(currentPage) {
        this.pagination.currentPage = currentPage
        this.editCondition.conditionList = []
        this.getAdLaunchList()
      },
      modifyEdit() {
        if (this.selectAll.length === 0) {
          this.$message({
            message: '请选择广告模版投放',
            type: 'warning'
          })
        } else {
          if (this.editCondition.conditionList.length === 0) {
            this.$message({
              message: '请选择修改项目',
              type: 'warning'
            })
          } else {
            this.getAdTradeList()
            this.adForm = {
              ad_trade_id: '',
              ad_plan_id: '',
              sdate: '',
              edate: '',
              visiable: '',
              only: ''
            }
            this.aoids = []
            let optionModify = this.editCondition.conditionList
            for (let i = 0; i < this.selectAll.length; i++) {
              let id = this.selectAll[i].id
              this.aoids.push(id)
            }

            this.modifyOptionFlag.ad_trade_id = false
            this.modifyOptionFlag.ad_plan_id = false
            this.modifyOptionFlag.sdate = false
            this.modifyOptionFlag.edate = false
            this.modifyOptionFlag.visiable = false
            this.modifyOptionFlag.only = false

            for (let k = 0; k < optionModify.length; k++) {
              let type = optionModify[k]
              switch (type) {
                case '广告模版':
                  this.modifyOptionFlag.ad_trade_id = true
                  this.modifyOptionFlag.ad_plan_id = true
                  break
                case '开始时间':
                  this.modifyOptionFlag.sdate = true
                  break
                case '结束时间':
                  this.modifyOptionFlag.edate = true
                  break
                case '状态':
                  this.modifyOptionFlag.visiable = true
                  break
                case '唯一性':
                  this.modifyOptionFlag.only = true
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

            let args = {
              aoids: this.aoids,
              keys: []
            }

            if (this.modifyOptionFlag.ad_plan_id) {
              args.keys.push('atiid');
              args.atiid = this.adForm.ad_plan_id;
            }
            if (this.modifyOptionFlag.sdate) {
              args.keys.push('sdate');
              args.sdate = this.adForm.sdate;
            }
            if (this.modifyOptionFlag.edate) {
              args.keys.push('edate');
              args.edate = new Date(this.adForm.edate);
            }
            if (this.modifyOptionFlag.visiable) {
              args.keys.push('visiable');
              args.visiable = this.adForm.visiable;
            }
            if (this.modifyOptionFlag.only) {
              args.keys.push('only');
              args.only = this.adForm.only;
            }

            return modifyAdLaunch(this, args)
              .then(response => {
                this.loading = false
                this.$message({
                  message: '修改成功',
                  type: 'success'
                })
                this.getAdLaunchList()
                this.editVisible = false
                this.editCondition.conditionList = []
              })
              .catch(err => {
                this.loading = false
                console.log(err)
                this.$message({
                  message: err.response.data.message,
                  type: 'error'
                })
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
