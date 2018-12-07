<template>
  <div 
    class="root">
    <div 
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText" 
      class="item-list-wrap" >
      <div 
        class="item-content-wrap">
        <!-- 搜索 -->
        <div 
          class="search-wrap">
          <el-form 
            ref="searchForm"
            :model="filters" 
            :inline="true">
            <el-row 
              :gutter="20">
              <el-col 
                :span="8">
                <el-form-item 
                  label="" 
                  prop="name">
                  <el-input 
                    v-model="filters.name" 
                    placeholder="请输入节目名称" 
                    style="width: 180px;" 
                    clearable/>
                </el-form-item>
              </el-col>
              <el-col 
                :span="8">
                <el-form-item
                  label="" 
                  prop="scene">
                  <el-select 
                    v-model="filters.scene" 
                    placeholder="请选择场景" 
                    filterable 
                    clearable>
                    <el-option
                      v-for="item in sceneList"
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
                  prop="area">
                  <el-select 
                    v-model="filters.area" 
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
            </el-row>
            <el-row 
              :gutter="20">
              <el-col 
                :span="8">
                <el-form-item 
                  label=""
                  prop="market">
                  <el-select 
                    v-model="filters.market" 
                    :loading="marketLoading"
                    :remote-method="getMarket"
                    :multiple-limit="1"
                    multiple 
                    placeholder="请选择商场" 
                    filterable 
                    remote 
                    clearable>
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
                  prop="tpl_name">
                  <el-select 
                    v-model="filters.tpl_name" 
                    :loading="marketLoading" 
                    placeholder="请选择模板" 
                    filterable
                    clearable>
                    <el-option
                      v-for="item in templateList"
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
                  prop="tpl_id">
                  <el-select 
                    v-model="filters.tpl_id" 
                    :loading="marketLoading"
                    placeholder="请选择模板名" 
                    filterable 
                    clearable>
                    <el-option
                      v-for="item in templateNameList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
            </el-row>
            <el-button 
              type="primary" 
              size="small"
              @click="search('searchForm')" >搜索</el-button>
            <el-button 
              type="default" 
              size="small"
              @click="resetSearch">重置</el-button>
          </el-form>
        </div>
        <!-- 批量修改选项 -->
        <div 
          class="editCondition-wrap" 
          style="padding: 0 0 15px;">
          <el-form 
            ref="editForm" 
            :model="editCondition" 
            :inline="true" 
          >
            <el-form-item 
              label="修改投放选项" 
              style="margin-bottom: 0;">
              <el-checkbox-group 
                v-model="editCondition.conditionList">
                <el-checkbox 
                  label="节目名称"/>
                <el-checkbox 
                  label="非自定义模板"/>
                <el-checkbox 
                  label="自定义模板"/>
                <!-- <el-checkbox 
                  label="自定义开始时间"/>
                <el-checkbox 
                  label="自定义结束时间" /> -->
              </el-checkbox-group>
            </el-form-item>
            <el-button 
              type="danger"
              size="small"
              @click="modifyEdit" >修改</el-button>
          </el-form>
        </div>
        <div 
          class="actions-wrap">
          <span 
            class="label">
            节目数量: {{ pagination.total }}
          </span>
          <div>
            <el-button 
              size="small" 
              type="success" 
              @click="linkToAddItem">新增投放</el-button>
          </div>
        </div>
        <!-- 节目投放列表 -->
        <el-table 
          ref="multipleTable" 
          :data="tableData" 
          style="width: 100%" 
          highlight-current-row  
          type="expand"
          @selection-change="handleSelectionChange">
          <el-table-column 
            type="selection"
            width="45" />
          <el-table-column 
            type="expand">
            <template 
              slot-scope="scope">
              <el-form 
                label-position="left" 
                inline
                class="demo-table-expand">
                <el-form-item 
                  label="节目名称">
                  <span>{{ scope.row.project.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="节目icon">
                  <a 
                    :href="scope.row.project.icon" 
                    target="_blank" 
                    style="color: blue">查看</a>
                </el-form-item>
                <el-form-item 
                  label="场景">
                  <span>{{ scope.row.point.scene.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="区域">
                  <span>{{ scope.row.point.area.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="商场">
                  <span>{{ scope.row.point.market.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="点位">
                  <span>{{ scope.row.point.name }}</span>
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
                  label="自定义开始时间">
                  <span>{{ scope.row.start_date }}</span>
                </el-form-item>
                <el-form-item 
                  label="自定义结束时间">
                  <span>{{ scope.row.end_date }}</span>
                </el-form-item>
                <el-form-item 
                  label="自定义模版">
                  <span>{{ scope.row.divtemplate.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="星期一模板">
                  <span>{{ scope.row.day1template.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="星期二模板">
                  <span>{{ scope.row.day2template.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="星期三模板">
                  <span>{{ scope.row.day3template.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="星期四模板">
                  <span>{{ scope.row.day4template.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="星期五模板">
                  <span>{{ scope.row.day5template.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="星期六模板">
                  <span>{{ scope.row.day6template.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="星期日模板">
                  <span>{{ scope.row.day7template.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="工作日模板">
                  <span>{{ scope.row.weekdaytemplate.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="周末模板">
                  <span>{{ scope.row.weekendtemplate.name }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            prop="id"
            label="ID"
            width="80"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="name"
            label="节目名称"
            min-width="150"
          >
            <template
              slot-scope="scope">
              {{ scope.row.project.name }}
            </template>
          </el-table-column>
          <el-table-column
            prop="icon"
            label="节目icon"
            min-width="100"
          >
            <template 
              slot-scope="scope">
              <img 
                :src="scope.row.project.icon"
                alt=""
                class="icon-item">
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="scene"
            label="区域"
            min-width="100"
          >
            <template 
              slot-scope="scope">
              {{ scope.row.point.area.name }}
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="scene"
            label="场景"
            min-width="100"
          >
            <template 
              slot-scope="scope">
              {{ scope.row.point.scene.name }}
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="market_name"
            label="商场"
            min-width="100"
          >
            <template 
              slot-scope="scope">
              {{ scope.row.point.market.name }}
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="point_name"
            label="点位"
            min-width="100"
          >
            <template 
              slot-scope="scope">
              {{ scope.row.point.name }}
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="define"
            label="自定义模版"
            min-width="100"
          >
            <template 
              slot-scope="scope">
              {{ scope.row.divtemplate.name }}
            </template>
          </el-table-column>
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
      <!-- 批量修改 -->
      <el-dialog 
        v-loading="loading"
        :visible.sync="editVisible" 
        title="批量修改" 
        @close="dialogClose">
        <el-form
          ref="projectForm"
          :model="projectForm"
          label-width="150px">
          <el-form-item 
            v-if="modifyOptionFlag.project"
            :rules="[{ required: true, message: '请输入节目', trigger: 'submit'}]"
            label="节目名称" 
            prop="project" >
            <el-select 
              v-model="projectForm.project" 
              :remote-method="getProject"
              :multiple-limit="1"
              multiple 
              filterable 
              placeholder="请搜索" 
              remote 
              clearable>
              <el-option
                v-for="item in projectList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.template" 
            label="星期一模版"  
            prop="day1_tvid">
            <el-select 
              v-model="projectForm.day1_tvid" 
              placeholder="请选择" 
              filterable 
              clearable>
              <el-option
                v-for="item in monList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.template" 
            label="星期二模版" 
            prop="day2_tvid">
            <el-select 
              v-model="projectForm.day2_tvid"
              placeholder="请选择" 
              filterable 
              clearable>
              <el-option
                v-for="item in tueList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.template" 
            label="星期三模版" 
            prop="day3_tvid">
            <el-select 
              v-model="projectForm.day3_tvid" 
              placeholder="请选择"
              filterable 
              clearable>
              <el-option
                v-for="item in wedList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.template" 
            label="星期四模版" 
            prop="day4_tvid">
            <el-select 
              v-model="projectForm.day4_tvid" 
              placeholder="请选择" 
              filterable 
              clearable>
              <el-option
                v-for="item in thursList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.template" 
            label="星期五模版" 
            prop="day5_tvid">
            <el-select 
              v-model="projectForm.day5_tvid"
              placeholder="请选择" 
              filterable 
              clearable>
              <el-option
                v-for="item in friList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.template" 
            label="星期六模版"
            prop="day6_tvid">
            <el-select 
              v-model="projectForm.day6_tvid" 
              placeholder="请选择" 
              filterable 
              clearable>
              <el-option
                v-for="item in satList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.template" 
            label="星期日模版" 
            prop="day7_tvid">
            <el-select 
              v-model="projectForm.day7_tvid"
              placeholder="请选择" 
              filterable 
              clearable>
              <el-option
                v-for="item in sunList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.template"
            label="工作日模版"
            prop="weekday" >
            <el-select 
              v-model="projectForm.weekday" 
              placeholder="请选择" 
              filterable 
              clearable>
              <el-option
                v-for="item in weekdayList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.template"
            label="周末模版" 
            prop="weekend">
            <el-select 
              v-model="projectForm.weekend" 
              placeholder="请选择" 
              filterable 
              clearable>
              <el-option
                v-for="item in weekendList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.defineTemplate"
            label="自定义模版" 
            prop="define" >
            <el-select
              v-model="projectForm.define" 
              placeholder="请选择" 
              filterable 
              clearable>
              <el-option
                v-for="item in defineList"
                :key="item.id"
                :label="item.name"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item  
            v-if="modifyOptionFlag.defineTemplate" 
            label="自定义开始时间" 
            prop="sdate">
            <el-date-picker
              v-model="projectForm.sdate"
              :editable="false"
              type="date"
              placeholder="选择自定义开始时间" />
          </el-form-item>
          <el-form-item 
            v-if="modifyOptionFlag.defineTemplate" 
            label="自定义结束时间" 
            prop="edate">
            <el-date-picker
              v-model="projectForm.edate"
              :editable="false"
              type="date"
              placeholder="选择自定义结束时间"
            />
          </el-form-item>
          <el-form-item>
            <el-button 
              type="primary" 
              @click="submitModify('projectForm')">完成</el-button>
          </el-form-item>
        </el-form>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import {
  modifyProjectLaunch,
  getPutProjectList,
  getSearchMarketList,
  getSearchSceneList,
  getSearchAeraList,
  getSearchModuleList,
  getSearchProjectList
} from 'service'

import {
  Button,
  Input,
  Table,
  Row,
  Col,
  TableColumn,
  Pagination,
  Dialog,
  Form,
  FormItem,
  MessageBox,
  DatePicker,
  Select,
  Option,
  CheckboxGroup,
  Checkbox
} from 'element-ui'

export default {
  components: {
    'el-row': Row,
    'el-col': Col,
    'el-table': Table,
    'el-date-picker': DatePicker,
    'el-table-column': TableColumn,
    'el-button': Button,
    'el-input': Input,
    'el-pagination': Pagination,
    'el-form': Form,
    'el-form-item': FormItem,
    'el-select': Select,
    'el-option': Option,
    'el-checkbox-group': CheckboxGroup,
    'el-checkbox': Checkbox,
    'el-dialog': Dialog
  },
  data() {
    return {
      editVisible: false,
      eidtkList: [],
      filters: {
        name: '',
        market: [],
        area: '',
        scene: '',
        tpl_name: '',
        tpl_id: ''
      },
      templateList: [
        {
          id: 'day1_tvid',
          name: '星期一模板'
        },
        {
          id: 'day2_tvid',
          name: '星期二模板'
        },
        {
          id: 'day3_tvid',
          name: '星期三模板'
        },
        {
          id: 'day4_tvid',
          name: '星期四模板'
        },
        {
          id: 'day5_tvid',
          name: '星期五模板'
        },
        {
          id: 'day6_tvid',
          name: '星期六模板'
        },
        {
          id: 'day7_tvid',
          name: '星期日模板'
        },
        {
          id: 'weekday_tvid',
          name: '工作日模板'
        },
        {
          id: 'weekend_tvid',
          name: '周末模板'
        },
        {
          id: 'div_tvid',
          name: '自定义模版'
        }
      ],
      monList: [],
      tueList: [],
      wedList: [],
      thursList: [],
      friList: [],
      satList: [],
      sunList: [],
      templateNameList: [],
      editCondition: {
        conditionList: []
      },
      sceneList: '',
      marketLoading: false,
      marketList: [],
      areaList: [],
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      dataValue: '',
      loading: true,
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      weekdayList: [],
      weekendList: [],
      defineList: [],
      projectList: [],
      searchLoading: false,
      projectForm: {
        project: [],
        weekday: '',
        weekend: '',
        define: '',
        sdate: '',
        edate: '',
        day1_tvid: '',
        day2_tvid: '',
        day3_tvid: '',
        day4_tvid: '',
        day5_tvid: '',
        day6_tvid: '',
        day7_tvid: ''
      },
      modifyOptionFlag: {
        project: false,
        template: false,
        defineTemplate: false
        // edate: false
      },
      tvoids: [],
      tableData: [],
      selectAll: []
    }
  },
  created() {
    this.getProjectList()
    this.getAreaList()
    this.getSceneList()
    this.getModuleList()
  },
  methods: {
    getSceneList() {
      return getSearchSceneList(this)
        .then(response => {
          this.sceneList = response.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    dialogClose() {
      if (!this.editVisible) {
        this.editCondition.conditionList = []
        this.$refs.multipleTable.clearSelection()
      }
    },
    handleSelectionChange(val) {
      this.selectAll = val
    },
    modifyEdit() {
      if (this.selectAll.length == 0) {
        this.$message({
          message: '请选择节目',
          type: 'warning'
        })
      } else {
        if (this.editCondition.conditionList.length == 0) {
          this.$message({
            message: '请选择修改项目',
            type: 'warning'
          })
        } else {
          this.projectForm = {
            project: [],
            weekday: '',
            weekend: '',
            define: '',
            sdate: '',
            edate: '',
            day1_tvid: '',
            day2_tvid: '',
            day3_tvid: '',
            day4_tvid: '',
            day5_tvid: '',
            day6_tvid: '',
            day7_tvid: ''
          }
          this.tvoids = []
          let optionModify = this.editCondition.conditionList
          for (let i = 0; i < this.selectAll.length; i++) {
            let id = this.selectAll[i].id
            this.tvoids.push(id)
          }
          this.modifyOptionFlag.project = false
          this.modifyOptionFlag.template = false
          this.modifyOptionFlag.defineTemplate = false
          // this.modifyOptionFlag.edate = false
          for (let k = 0; k < optionModify.length; k++) {
            let type = optionModify[k]
            switch (type) {
              case '节目名称':
                this.modifyOptionFlag.project = true
                break
              case '非自定义模板':
                this.modifyOptionFlag.template = true
                break
              case '自定义模板':
                this.modifyOptionFlag.defineTemplate = true
                break
            }
          }
          this.editVisible = true
        }
      }
    },
    resetSearch() {
      this.filters.market = []
      this.filters.area = ''
      this.filters.name = ''
      this.filters.scene = ''
      this.filters.tpl_name = ''
      this.filters.tpl_id = ''
      this.pagination.currentPage = 1
      this.editCondition.conditionList = []
      this.getProjectList()
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
              this.projectForm.project = []
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
    submitModify(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let edate =
            (new Date(this.projectForm.edate).getTime() +
              ((23 * 60 + 59) * 60 + 59) * 1000) /
            1000
          this.loading = true
          let args = {
            tvoids: this.tvoids,
            default_plid: this.projectForm.project[0],
            sdate: new Date(this.projectForm.sdate).getTime() / 1000,
            edate: edate,
            weekday_tvid: this.projectForm.weekday,
            weekend_tvid: this.projectForm.weekend,
            div_tvid: this.projectForm.define,
            day1_tvid: this.projectForm.day1_tvid,
            day2_tvid: this.projectForm.day2_tvid,
            day3_tvid: this.projectForm.day3_tvid,
            day4_tvid: this.projectForm.day4_tvid,
            day5_tvid: this.projectForm.day5_tvid,
            day6_tvid: this.projectForm.day6_tvid,
            day7_tvid: this.projectForm.day7_tvid
          }
          this.modifyOptionFlag.project ? args : delete args.default_plid

          if (!this.modifyOptionFlag.defineTemplate) {
            delete args.sdate
            delete args.edate
            delete args.div_tvid
          }
          this.projectForm.day1_tvid ? args : delete args.day1_tvid
          this.projectForm.day2_tvid ? args : delete args.day2_tvid
          this.projectForm.day3_tvid ? args : delete args.day3_tvid
          this.projectForm.day4_tvid ? args : delete args.day4_tvid
          this.projectForm.day5_tvid ? args : delete args.day5_tvid
          this.projectForm.day6_tvid ? args : delete args.day6_tvid
          this.projectForm.day7_tvid ? args : delete args.day7_tvid
          this.projectForm.weekday ? args : delete args.weekday_tvid
          this.projectForm.weekend ? args : delete args.weekend_tvid
          this.loading = false
          return modifyProjectLaunch(this, args)
            .then(response => {
              this.setting.loading = false
              this.$message({
                message: '修改成功',
                type: 'success'
              })
              this.getProjectList()
              this.editVisible = false
              this.editCondition.conditionList = []
            })
            .catch(err => {
              this.loading = false
              this.editVisible = false
              this.editCondition.conditionList = []
              console.log(err)
            })
        } else {
          this.loading = false
          return
        }
      })
    },
    getModuleList() {
      return getSearchModuleList(this)
        .then(response => {
          let data = response.data
          this.templateNameList = data
          this.weekdayList = data
          this.weekendList = data
          this.defineList = data
          this.monList = data
          this.tueList = data
          this.wedList = data
          this.thursList = data
          this.friList = data
          this.satList = data
          this.sunList = data
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    getProjectList() {
      this.setting.loadingText = '拼命加载中'
      this.setting.loading = true
      let searchArgs = {
        page: this.pagination.currentPage,
        include:
          'point.scene,point.market,point.area,project,divtemplate,day1template,day2template,day3template,day4template,day5template,day6template,day7template,weekdaytemplate,weekendtemplate',
        project_name: this.filters.name,
        area_id: this.filters.area,
        market_id: this.filters.market[0],
        scene_id: this.filters.scene,
        tpl_name: this.filters.tpl_name,
        tpl_id: this.filters.tpl_id
      }
      getPutProjectList(this, searchArgs)
        .then(response => {
          let data = response.data
          this.tableData = data
          this.pagination.total = response.meta.pagination.total
          this.setting.loading = false
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    areaChangeHandle() {
      this.filters.market = []
      this.getMarket(this.filters.market)
    },
    getMarket(query) {
      if (query !== '') {
        this.marketLoading = true
        let args = {
          name: query,
          include: 'area',
          area_id: this.filters.area
        }
        return getSearchMarketList(this, args)
          .then(response => {
            this.marketList = response.data
            if (this.marketList.length == 0) {
              this.filters.market = []
              this.marketList = []
            }
            this.marketLoading = false
          })
          .catch(err => {
            console.log(err)
            this.marketLoading = false
          })
      } else {
        this.marketList = []
      }
    },
    search(formName) {
      this.pagination.currentPage = 1
      this.editCondition.conditionList = []
      this.getProjectList()
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.editCondition.conditionList = []
      this.getProjectList()
    },
    linkToAddItem() {
      this.$router.push({
        path: '/project/item/add'
      })
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
    linkToEdit(item) {
      let pid = item.project.id
      let pname = item.project.name
      this.$router.push({
        path: '/project/item/edit'
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
    .el-select,
    .item-input,
    .el-input {
      width: 380px;
    }
    background: #fff;
    padding: 30px;
    .item-content-wrap {
      .icon-item {
        padding: 10px;
        width: 60%;
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
  }
}
</style>
