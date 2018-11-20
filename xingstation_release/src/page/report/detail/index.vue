<template>
  <div
    class="point-data-wrapper" >
    <el-tabs 
      v-model="activeName" 
      type="card"
      @tab-click="handleTab">
      <!-- 搜索 -->
      <div 
        class="search-wrap">
        <!-- <el-button
          class="more-pic"
          @click="handlePicShow">
          漏斗图
        </el-button> -->
        <el-form 
          ref="searchForm"
          :model="searchForm"
          class="search-form">
          <el-row 
            :gutter="20">
            <el-col
              v-if="showUser"
              :span="6">
              <el-form-item 
                label="" 
                prop="user" >
                <el-select
                  v-model="searchForm.userSelect"
                  :remote-method="getUser" 
                  :loading="searchLoading" 
                  :multiple-limit="1"
                  multiple 
                  filterable 
                  placeholder="请选择用户(可搜索)" 
                  remote
                  clearable
                  @change="userChangeHandle">
                  <el-option
                    v-for="item in userList"
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
                prop="project" >
                <el-select 
                  v-model="searchForm.projectSelect" 
                  :remote-method="getProject"
                  :loading="searchLoading"
                  :multiple-limit="1"
                  filterable 
                  placeholder="请选择节目(可搜索)" 
                  remote
                  multiple 
                  clearable
                  @change="projectChangeHandle">
                  <el-option
                    v-for="item in projectList"
                    :key="item.id"
                    :label="item.name"
                    :value="item.alias"/>
                </el-select>
              </el-form-item>
            </el-col>
            <el-col 
              :span="6">
              <el-form-item 
                label="" 
                prop="scene" >
                <el-select
                  v-model="searchForm.sceneSelect" 
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
          </el-row>
          <el-row 
            :gutter="20">
            <el-col 
              :span="6">
              <el-form-item 
                label="" 
                prop="area_id" >
                <el-select 
                  v-model="searchForm.area_id"
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
                prop="market_id" >
                <el-select 
                  v-model="searchForm.market_id"
                  :multiple-limit="1"
                  :loading="searchLoading"
                  :remote-method="getMarket" 
                  placeholder="请搜索商场" 
                  filterable 
                  multiple
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
              :span="6">
              <el-form-item 
                label=""
                prop="point_id" >
                <el-select 
                  v-model="searchForm.point_id" 
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
            :gutter="20">
            <el-col 
              :span="10">
              <el-form-item 
                label="" 
                prop="date" >
                <el-date-picker
                  v-model="searchForm.dateTime"
                  :default-value="searchForm.dateTime"
                  :clearable="false"
                  :picker-options="pickerOptions2"
                  type="daterange"
                  align="right"
                  unlink-panels
                  start-placeholder="开始日期"
                  end-placeholder="结束日期"/>
              </el-form-item>
            </el-col>
            <el-col
              :span="8">
              <el-form-item 
                label=""
                prop="timeFrame">
                <el-select 
                  v-model="searchForm.timeFrame" 
                  :loading="searchLoading"
                  placeholder="请选择时段" 
                  multiple  
                  filterable 
                  clearable
                  style="width: 100%">
                  <el-option
                    v-for="item in festivalList"
                    :key="item.id"
                    :label="item.name"
                    :value="item.name"/>
                </el-select>
              </el-form-item>
            </el-col>
            <el-col 
              :span="4">
              <el-form-item>
                <el-button 
                  type="primary" 
                  size="small"
                  @click="searchHandle">搜索</el-button>
                <el-button 
                  size="small"
                  @click="resetSearch">重置</el-button>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
      </div>
      <el-tab-pane label="按人次计" name="first">
        <PersonTimes ref="personTimes" :searchForm="searchForm"/>
      </el-tab-pane>
      <el-tab-pane label="按人数计" name="second">
        <PeopleNum ref="peopleCount" :searchForm="searchForm"/>
      </el-tab-pane>
    </el-tabs>
   
  </div>
</template>
<script>
import search from 'service/search'
import PersonTimes from './com/person_times'
import PeopleNum from './com/people_num'
import {
  Tabs,
  TabPane,
  Row,
  Col,
  DatePicker,
  Select,
  Option,
  Button,
  Form,
  FormItem
} from 'element-ui'

export default {
  components: {
    'el-row': Row,
    'el-col': Col,
    'el-date-picker': DatePicker,
    'el-select': Select,
    'el-button': Button,
    'el-option': Option,
    'el-form-item': FormItem,
    'el-form': Form,
    'el-tabs': Tabs,
    'el-tab-pane': TabPane,
    PersonTimes,
    PeopleNum
  },
  data() {
    return {
      searchForm: {
        userSelect: [],
        projectSelect: [],
        sceneSelect: '',
        area_id: '',
        market_id: [],
        point_id: '',
        projectAlias: '',
        dateTime: [
          new Date().getTime() - 3600 * 1000 * 24 * 7,
          new Date().getTime() - 3600 * 1000 * 24
        ],
        timeFrame: [],
        arUserId: ''
      },
      activeName: 'first',
      festivalList: [
        {
          id: 'workday',
          name: '工作日'
        },
        {
          id: 'weekend',
          name: '周末'
        },
        {
          id: 'holiday',
          name: '假日'
        }
      ],
      pickerOptions2: {
        shortcuts: [
          {
            text: '昨天',
            onClick(picker) {
              const end = new Date()
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24)
              end.setTime(end.getTime() - 3600 * 1000 * 24)
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '最近一周',
            onClick(picker) {
              const end = new Date().getTime() - 3600 * 1000 * 24
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '最近一个月',
            onClick(picker) {
              const end = new Date() - 3600 * 1000 * 24
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
              picker.$emit('pick', [start, end])
            }
          },
          {
            text: '最近三个月',
            onClick(picker) {
              const end = new Date() - 3600 * 1000 * 24
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90)
              picker.$emit('pick', [start, end])
            }
          }
        ],
        disabledDate: time => {
          return (
            time.getTime() > Date.now() - 8.64e7 ||
            time.getTime() < new Date('2017/04/21').getTime()
          )
        }
      },
      projectList: [],
      areaList: [],
      marketList: [],
      pointList: [],
      sceneList: [],
      userList: [],
      searchLoading: false
    }
  },
  computed: {
    showUser() {
      let user_info = JSON.parse(this.$cookie.get('user_info'))
      let roles = user_info.roles.data[0].name
      return roles == 'user' ? false : true
    }
  },
  mounted() {
    if (this.activeName === 'first') {
      this.$refs.personTimes.allPromise()
    } else {
      this.$refs.peopleCount.allPromise()
    }
  },
  created() {
    this.getSceneList()
    this.getAreaList()
  },
  methods: {
    handleTab(tab, event) {
      if (tab.name === 'first') {
        this.$refs.personTimes.handleChange()
        this.$refs.personTimes.allPromise()
      } else {
        this.$refs.peopleCount.handleChange()
        this.$refs.peopleCount.allPromise()
      }
    },
    searchHandle() {
      this.searchForm.projectAlias = this.searchForm.projectSelect[0]
      if (this.activeName === 'first') {
        this.$refs.personTimes.searchHandle()
      } else {
        this.$refs.peopleCount.searchHandle()
      }
    },
    resetSearch() {
      if (this.searchForm.showUser) {
        this.searchForm.userSelect = []
        this.searchForm.arUserId = this.userSelect[0]
        this.searchForm.projectSelect = ''
        this.searchForm.area_id = ''
        this.searchForm.market_id = []
        this.searchForm.point_id = ''
        this.searchForm.sceneSelect = ''
      } else {
        this.searchForm.projectSelect = ''
      }
      if (this.activeName === 'first') {
        this.$refs.personTimes.resetSearch()
      } else {
        this.$refs.peopleCount.resetSearch()
      }
    },
    areaChangeHandle() {
      this.searchForm.market_id = []
      this.searchForm.point_id = ''
      this.getMarket()
    },
    marketChangeHandle() {
      this.searchForm.point_id = ''
      this.getPoint()
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
    getMarket(query) {
      if (query !== '') {
        this.searchLoading = true
        let args = {
          name: query,
          include: 'area',
          area_id: this.searchForm.area_id
        }
        return search
          .getMarketList(this, args)
          .then(response => {
            this.marketList = response.data
            if (this.marketList.length == 0) {
              this.searchForm.market_id = []
              this.searchForm.marketList = []
            }
            this.searchLoading = false
          })
          .catch(err => {
            console.log(err)
            this.searchLoading = false
          })
      }
    },
    getPoint() {
      let args = {
        include: 'market',
        market_id: this.searchForm.market_id[0]
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
    getUser(query) {
      let args = {
        name: query
      }
      if (query !== '') {
        this.searchLoading = true
        return search
          .getUserList(this, args)
          .then(response => {
            this.userList = response.data
            if (this.userList.length == 0) {
              this.projectList = []
              this.projectSelect = []
            }
            this.searchLoading = false
          })
          .catch(err => {
            console.log(err)
            this.searchLoading = false
          })
      } else {
        this.userList = []
        return false
      }
    },
    getProject(query) {
      if (query !== '') {
        let args = {
          ar_user_id: this.searchForm.arUserId,
          name: query
        }
        if (this.showUser) {
          this.searchLoading = true
          if (!this.searchForm.arUserId) {
            delete args.ar_user_id
          }
          return search
            .getProjectList(this, args)
            .then(response => {
              this.projectList = response.data
              this.searchLoading = false
            })
            .catch(err => {
              console.log(err)
              this.searchLoading = false
            })
        } else {
          let user_info = JSON.parse(this.$cookie.get('user_info'))
          this.searchForm.arUserId = user_info.ar_user_id
          args.ar_user_id = this.searchForm.arUserId
          this.searchLoading = true
          return search
            .getProjectList(this, args)
            .then(response => {
              this.projectList = response.data
              this.searchLoading = false
            })
            .catch(err => {
              console.log(err)
              this.searchLoading = false
            })
        }
      }
    },
    getSceneList() {
      return search
        .getSceneList(this)
        .then(response => {
          this.sceneList = response.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    projectChangeHandle() {
      this.searchForm.projectAlias = this.searchForm.projectSelect[0]
    },
    userChangeHandle() {
      this.searchForm.arUserId = this.searchForm.userSelect[0]
      this.searchForm.projectSelect = []
      if (this.searchForm.arUserId) {
        this.getProject('')
      }
    }
  }
}
</script>
<style lang="less" scoped>
.point-data-wrapper {
  background: #fff;
  .search-wrap {
    padding: 30px;
    background: #fff;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    font-size: 16px;
    align-items: center;
    position: relative;
    .search-form {
      width: 865px;
    }
    .more-pic {
      position: absolute;
      top: 10px;
      right: 10px;
    }
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
}
</style>

