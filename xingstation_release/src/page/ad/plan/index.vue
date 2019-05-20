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
                  prop="type">
                  <el-select
                    v-model="adSearchForm.type"
                    :loading="searchLoading"
                    placeholder="请选择类型"
                    clearable
                    @change="typeChangeHandle()">
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
                :span="8">
                <el-form-item
                  label=""
                  prop="adTrade">
                  <el-select
                    v-model="adSearchForm.ad_trade_id"
                    filterable
                    placeholder="请搜索广告行业"
                    clearable
                    @change="adTradeChangeHandle()">
                    <el-option
                      v-for="item in searchAdTradeList"
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
                      v-for="item in searchAdPlanList"
                      :key="item.id"
                      :label="item.name+'('+item.type_text+')'"
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
                  prop="type">
                  <el-input 
                    v-model="adSearchForm.ad_plan_name" 
                    placeholder="模糊查询广告方案"/>
                </el-form-item>
              </el-col>
              <el-col
                :span="8">
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
          class="actions-wrap">
          <span
            class="label">
            广告方案数量: {{ pagination.total }}
          </span>
          <el-button
            size="small"
            type="success"
            @click="linkToAddPlan">新增广告方案
          </el-button>
        </div>
        <el-table
          ref="multipleTable"
          :data="adPlanList"
          style="width: 100%"
          highlight-current-row
        >
          <el-table-column
            type="expand">
            <template
              slot-scope="scope">
              <el-form
                label-position="left"
                inline
                class="demo-table-expand">
                <el-form-item
                  label="类型">
                  <span>{{ scope.row.type_text }}</span>
                </el-form-item>
                <el-form-item
                  label="广告行业">
                  <span>{{ scope.row.ad_trade }}</span>
                </el-form-item>
                <el-form-item
                  label="图标">
                    <a
                      :href="scope.row.icon"
                      target="_blank"
                      style="color: blue">查看</a>
                </el-form-item>
                <el-form-item
                  label="广告方案">
                  <span>{{ scope.row.name }}</span>
                </el-form-item>
                <el-form-item
                  label="硬件加速">
                  <span>{{ scope.row.hardware ? '是' : '否' }}</span>
                </el-form-item>
                <el-form-item
                  label="小时/自定义">
                  <span>{{ scope.row.tmode_text}}</span>
                </el-form-item>
                <el-form-item
                  label="创建时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
                <el-form-item
                  label="修改时间">
                  <span>{{ scope.row.updated_at }}</span>
                </el-form-item>
              </el-form>
              <template>
                <el-table
                  :data="scope.row.advertisements.data"
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
                        <img 
                          :src="ad_scope.row.img" 
                          width="40px">
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
                        style="color: blue">{{ ad_scope.row.size }}K</a>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="广告标记"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.isad_text }}</span>
                    </template>
                  </el-table-column>
                  <template v-if="scope.row.type==='program'">
                    <el-table-column
                      label="显示"
                      min-width="50">
                      <template slot-scope="ad_scope">
                        <span v-if="ad_scope.row.pivot">
                          {{ ad_scope.row.pivot.mode }}
                        </span>
                      </template>
                    </el-table-column>
                    <el-table-column
                      label="屏幕"
                      min-width="50">
                      <template slot-scope="ad_scope">
                        <span v-if="ad_scope.row.pivot">
                          {{ ad_scope.row.pivot.ori }} <br>
                          {{ ad_scope.row.pivot.screen }}%
                        </span>
                      </template>
                    </el-table-column>
                  </template>
                  <el-table-column
                    label="倒计时"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span v-if="ad_scope.row.pivot">
                        {{ ad_scope.row.pivot.cdshow ?'开启':'关闭' }}<br>
                        {{ ad_scope.row.pivot.ktime }}s
                      </span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="状态"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span>{{ ad_scope.row.pivot.visiable === 1 ? '运营中' : '下架' }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    label="开始时间"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span v-if="ad_scope.row.pivot">
                        {{ ( (Array(4).join('0') + ad_scope.row.pivot.shm).slice(-4)).substring(0,2) + ":"
                        + ( (Array(4).join('0') + ad_scope.row.pivot.shm).slice(-4)).substring(2) }}
                      </span>
                    </template>
                  </el-table-column>

                  <el-table-column
                    label="结束时间"
                    min-width="50">
                    <template slot-scope="ad_scope">
                      <span v-if="ad_scope.row.pivot">
                        {{ ( (Array(4).join('0') + ad_scope.row.pivot.ehm).slice(-4)).substring(0,2) + ":"
                        + ( (Array(4).join('0') + ad_scope.row.pivot.ehm).slice(-4)).substring(2) }}
                      </span>
                    </template>
                  </el-table-column>

                  <el-table-column
                    label="操作"
                    min-width="150"
                  >
                    <template slot-scope="ad_scope">
                      <el-button
                        size="small"
                        type="default"
                        @click="linkToEditPlanTime(ad_scope.row.pivot.id)">编辑
                      </el-button>
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
            prop="type_text"
            label="类型"
            min-width="60"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="ad_trade"
            label="广告行业"
            min-width="60"
          />
          <el-table-column
            label="图片"
            min-width="50">
            <template slot-scope="scope">
              <span>
                <img 
                  :src="scope.row.icon" 
                  width="40px">
              </span>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="name"
            label="广告方案"
            min-width="130"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="hardware"
            label="硬件加速"
            min-width="80">
            <template slot-scope="scope">
              {{ scope.row.hardware ? '是' : '否' }}
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="tmode_text"
            label="小时/自定义"
            min-width="80"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="created_at"
            label="创建时间"
            min-width="120"
          />
          <el-table-column
            label="操作"
            min-width="150"
          >
            <template slot-scope="scope">
              <el-button
                size="small"
                type="danger"
                @click="linkToEditPlanBatch(scope.row.id)">编辑方案排期
              </el-button>
              <el-button
                size="small"
                type="success"
                @click="linkToEditPlan(scope.row.id)">编辑方案
              </el-button>
              <el-button
                size="small"
                type="default"
                @click="linkToAddPlanTime(scope.row.id)">新增素材
              </el-button>
            </template>
          </el-table-column>
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
    </div>
  </div>
</template>

<script>
  import {
    getAdPlanList,
    getSearchAdTradeList,
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
    DatePicker,
    Checkbox,
    CheckboxGroup,
    Dialog,
    Row,
    MessageBox,
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
        setting: {
          loading: false,
          loadingText: '拼命加载中'
        },
        loading: false,

        searchAdTradeList: [],
        searchLoading: false,
        searchAdPlanList: [],
        adSearchForm: {
          type: '',
          ad_trade_id: '',
          ad_plan_id: '',
          ad_plan_name: '',
        },
        pagination: {
          total: 0,
          pageSize: 10,
          currentPage: 1
        },
        adPlanList: [],
      }
    },
    created() {
      this.setting.loading = true
      let getSearchAdTradeList = this.getSearchAdTradeList()
      let adPlanList = this.getAdPlanList()
      Promise.all([getSearchAdTradeList, adPlanList])
        .then(() => {
          this.setting.loading = false
        })
        .catch(err => {
          console.log(err)
          this.setting.loading = false
        })
    },
    methods: {

      getSearchAdTradeList() {
        return getSearchAdTradeList(this)
          .then(response => {
            let data = response.data
            this.searchAdTradeList = data
          })
          .catch(error => {
            console.log(error)
            this.setting.loading = false
          })
      },
      typeChangeHandle() {
        this.adSearchForm.ad_plan_id = ''
        this.getSearchAdPlanList()
      },
      adTradeChangeHandle() {
        this.adSearchForm.ad_plan_id = ''
        this.getSearchAdPlanList()
      },
      getSearchAdPlanList() {
        let args = {}
        args = {
          ad_trade_id: this.adSearchForm.ad_trade_id,
          type: this.adSearchForm.type,
        }
        this.searchLoading = true
        return getSearchAdPlanList(this, args)
          .then(response => {
            this.searchAdPlanList = response.data
            this.searchLoading = false
          })
          .catch(error => {
            console.log(error)
            this.searchLoading = false
          })
      },

      getAdPlanList() {
        this.setting.loadingText = '拼命加载中'
        this.setting.loading = true
        let searchArgs = {
          page: this.pagination.currentPage,
          include: 'advertisements',
          type: this.adSearchForm.type,
          ad_trade_id: this.adSearchForm.ad_trade_id,
          ad_plan_name: this.adSearchForm.ad_plan_name,
          ad_plan_id: this.adSearchForm.ad_plan_id,
        }
        return getAdPlanList(this, searchArgs)
          .then(response => {
            this.adPlanList = response.data
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
        this.getAdPlanList()
      },
      resetSearch(formName) {
        this.adSearchForm.type = ''
        this.adSearchForm.ad_trade_id = ''
        this.adSearchForm.ad_plan_name = ''
        this.adSearchForm.ad_plan_id = ''
        this.pagination.currentPage = 1
        this.getAdPlanList()
      },
      changePage(currentPage) {
        this.pagination.currentPage = currentPage
        this.getAdPlanList()
      },
      linkToAddPlan() {
        this.$router.push({
          path: '/ad/plan/add'
        })
      },
      linkToEditPlan(plan_id) {
        this.$router.push({
          path: '/ad/plan/edit/' + plan_id + '/item/true'
        })
      },
      linkToEditPlanBatch(plan_id) {
        this.$router.push({
          path: '/ad/plan/edit/' + plan_id + '/batch'
        })
      },
      linkToEditPlanTime(plan_time_id) {
        this.$router.push({
          path: '/ad/plan/edit/plan_time/' + plan_time_id
        })
      },
      linkToAddPlanTime(plan_id) {
        this.$router.push({
          path: '/ad/plan/' + plan_id + '/add/plan_time/'
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
        width: 200px;
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
