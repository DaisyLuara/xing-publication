<template>
  <div class="schedule-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <!-- 搜索 -->
    <div class="search-wrap">
      <el-form :model="searchForm" :inline="true" ref="searchForm" >
        <el-form-item label="" prop="area_id">
          <el-select v-model="searchForm.area_id" placeholder="请选择区域" filterable clearable @change="areaChangeHandle">
            <el-option
              v-for="item in areaList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="" prop="market_id">
          <el-select v-model="searchForm.market_id" placeholder="请搜索商场" filterable :loading="searchLoading" remote :remote-method="getMarket"  @change="marketChangeHandle" clearable>
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="" prop="point_id">
          <el-select v-model="searchForm.point_id" placeholder="请选择点位" filterable :loading="searchLoading" clearable>
            <el-option
              v-for="item in pointList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="" prop="name">
          <el-input v-model="searchForm.name" placeholder="请输入模板名称" class="item-input" clearable></el-input>
        </el-form-item>
        <el-button @click="search('searchForm')" type="primary" size="small">搜索</el-button>
      </el-form>
    </div>
    <div class="actions-wrap">
      <span class="label">
        模板排期数量: {{pagination.total}}
      </span>
      <div>
        <el-button size="small" type="success" @click="addSchedule">新增</el-button>
      </div>
    </div>
    <!-- 模板排期列表 -->
    <el-table :data="tableData" style="width: 100%">
      <el-table-column
        prop="id"
        label="ID"
        min-width="80"
        >
      </el-table-column>
      <el-table-column
        prop="name"
        label="名称"
        min-width="150"
        :show-overflow-tooltip="true"
        >
      </el-table-column>
      <el-table-column
        prop=""
        label="节目名"
        min-width="100"
        >
        <template slot-scope="scope">
          <el-table :data="scope.row.schedules.data" :show-header="false">
            <el-table-column>
              <template slot-scope="scope">
                 {{scope.row.project.name}}
              </template>
            </el-table-column>
          </el-table>
        </template>
      </el-table-column>
      <el-table-column
        prop=""
        label="节目图标"
        min-width="100"
        >
        <template slot-scope="scope">
          <el-table :data="scope.row.schedules.data" :show-header="false">
            <el-table-column>
              <template slot-scope="scope">
                 <img :src="scope.row.project.icon" style="width: 40%"/>
              </template>
            </el-table-column>
          </el-table>
        </template>
      </el-table-column>
      <el-table-column
        prop=""
        label="归属"
        min-width="100"
        >
        <template slot-scope="scope">
          <el-table :data="scope.row.schedules.data" :show-header="false">
            <el-table-column>
              <template slot-scope="scope">
                 {{scope.row.project.info}}
              </template>
            </el-table-column>
          </el-table>
        </template>
      </el-table-column>
      <el-table-column
        prop=""
        label="开始时间"
        min-width="100"
        >
        <template slot-scope="scope">
          <el-table :data="scope.row.schedules.data" :show-header="false">
            <el-table-column>
              <template slot-scope="scope">
                 {{scope.row.project.created_at}}
              </template>
            </el-table-column>
          </el-table>
        </template>
      </el-table-column>
      <el-table-column
        prop=""
        label="结束时间"
        min-width="100"
        >
        <template slot-scope="scope">
          <el-table :data="scope.row.schedules.data" :show-header="false">
            <el-table-column>
              <template slot-scope="scope">
                 {{scope.row.project.updated_at}}
              </template>
            </el-table-column>
          </el-table>
        </template>
      </el-table-column>
      
      <el-table-column label="操作" width="100">
        <template slot-scope="scope">
          <el-button size="small" type="warning" >修改</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-wrap">
      <el-pagination
      layout="prev, pager, next, jumper, total"
      :total="pagination.total"
      :page-size="pagination.pageSize"
      :current-page="pagination.currentPage"
      @current-change="changePage"
      >
      </el-pagination>
    </div>
  </div>
</template>
<script>
import {
  Form,
  FormItem,
  Button,
  Select,
  Option,
  Pagination,
  Table,
  TableColumn,
  Input
} from 'element-ui'
import search from 'service/search'

export default {
  components: {
    ElPagination: Pagination,
    ElInput: Input,
    ElForm: Form,
    ElFormItem: FormItem,
    ElButton: Button,
    ElSelect: Select,
    ElOption: Option,
    ElTable: Table,
    ElTableColumn: TableColumn
  },
  data() {
    return {
      iconList: [],
      templateForm: {
        name: '',
        icon: ''
      },
      tableData: [
        {
          id: 1,
          name: '线上模板',
          schedules: {
            data: [
              {
                id: 1,
                date_start: 1,
                date_end: 2359,
                project: {
                  id: 5,
                  name: '镜视界',
                  info: '线下店的FaceU版本',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/234_istar2_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/460_istar2_bg.jpg',
                  alias: 'istar2',
                  version_code: '2018032015',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/istar2_2018032015.apk',
                  created_at: '2018-03-20 14:37:39',
                  updated_at: '2018-03-20 14:37:39'
                }
              },
              {
                id: 1,
                date_start: 900,
                date_end: 1130,
                project: {
                  id: 15,
                  name: '星辉游戏',
                  info: '星辉游戏',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/100_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1506047473.png',
                  alias: 'gamexh',
                  version_code: '2017092915',
                  link:
                    'http://o9xrgfdni.bkt.clouddn.com/1007/other/EXEIStar_xinghui2017092915.apk',
                  created_at: '2017-09-29 15:53:02',
                  updated_at: '2017-09-29 15:53:02'
                }
              },
              {
                id: 1,
                date_start: 1131,
                date_end: 1330,
                project: {
                  id: 5,
                  name: '镜视界',
                  info: '线下店的FaceU版本',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/234_istar2_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/460_istar2_bg.jpg',
                  alias: 'istar2',
                  version_code: '2018032015',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/istar2_2018032015.apk',
                  created_at: '2018-03-20 14:37:39',
                  updated_at: '2018-03-20 14:37:39'
                }
              },
              {
                id: 1,
                date_start: 1331,
                date_end: 1630,
                project: {
                  id: 9,
                  name: '小泰良品',
                  info: '小泰良品',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/834_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1504597171.png',
                  alias: 'xt',
                  version_code: '2017102716',
                  link:
                    'http://o9xrgfdni.bkt.clouddn.com/1007/other/XiaoTaiLiangPinNew_2017102716.apk',
                  created_at: '2017-10-27 16:24:38',
                  updated_at: '2017-10-27 16:24:38'
                }
              },
              {
                id: 1,
                date_start: 1631,
                date_end: 1830,
                project: {
                  id: 6,
                  name: '蒙奇奇',
                  info: '蒙奇奇活动',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/243_kikiicon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/829_kikibg.png',
                  alias: 'kiki',
                  version_code: '2018050417',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/MengQuqu2018050417.apk',
                  created_at: '2018-05-04 19:18:31',
                  updated_at: '2018-05-04 19:18:31'
                }
              },
              {
                id: 1,
                date_start: 1831,
                date_end: 2130,
                project: {
                  id: 10,
                  name: '漫威主题',
                  info: '漫威主题',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/44_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/276_manhua_capture.png',
                  alias: 'manhua',
                  version_code: '2018012013',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/EXEIStar_Manhua2018012013.apk',
                  created_at: '2018-01-20 14:22:56',
                  updated_at: '2018-01-20 14:22:56'
                }
              },
              {
                id: 1,
                date_start: 2131,
                date_end: 2230,
                project: {
                  id: 10,
                  name: '漫威主题',
                  info: '漫威主题',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/44_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/276_manhua_capture.png',
                  alias: 'manhua',
                  version_code: '2018012013',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/EXEIStar_Manhua2018012013.apk',
                  created_at: '2018-01-20 14:22:56',
                  updated_at: '2018-01-20 14:22:56'
                }
              },
              {
                id: 1,
                date_start: 1000,
                date_end: 2200,
                project: {
                  id: 18,
                  name: '苏州中心',
                  info: '苏州中心颜值验钞机',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/846_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1508405860.png',
                  alias: 'szzx',
                  version_code: '2018012015',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/ColorPrintMachine_Parent2018012015.apk',
                  created_at: '2018-01-20 15:56:48',
                  updated_at: '2018-01-20 15:56:48'
                }
              }
            ]
          }
        },
        {
          id: 7,
          name: '星辉+漫威+小泰',
          schedules: {
            data: [
              {
                id: 7,
                date_start: 901,
                date_end: 1300,
                project: {
                  id: 15,
                  name: '星辉游戏',
                  info: '星辉游戏',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/100_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1506047473.png',
                  alias: 'gamexh',
                  version_code: '2017092915',
                  link:
                    'http://o9xrgfdni.bkt.clouddn.com/1007/other/EXEIStar_xinghui2017092915.apk',
                  created_at: '2017-09-29 15:53:02',
                  updated_at: '2017-09-29 15:53:02'
                }
              },
              {
                id: 7,
                date_start: 1301,
                date_end: 1700,
                project: {
                  id: 10,
                  name: '漫威主题',
                  info: '漫威主题',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/44_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/276_manhua_capture.png',
                  alias: 'manhua',
                  version_code: '2018012013',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/EXEIStar_Manhua2018012013.apk',
                  created_at: '2018-01-20 14:22:56',
                  updated_at: '2018-01-20 14:22:56'
                }
              },
              {
                id: 7,
                date_start: 1701,
                date_end: 2200,
                project: {
                  id: 9,
                  name: '小泰良品',
                  info: '小泰良品',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/834_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1504597171.png',
                  alias: 'xt',
                  version_code: '2017102716',
                  link:
                    'http://o9xrgfdni.bkt.clouddn.com/1007/other/XiaoTaiLiangPinNew_2017102716.apk',
                  created_at: '2017-10-27 16:24:38',
                  updated_at: '2017-10-27 16:24:38'
                }
              }
            ]
          }
        },
        {
          id: 6,
          name: '漫威+小泰+星辉',
          schedules: {
            data: [
              {
                id: 6,
                date_start: 1701,
                date_end: 2200,
                project: {
                  id: 15,
                  name: '星辉游戏',
                  info: '星辉游戏',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/100_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1506047473.png',
                  alias: 'gamexh',
                  version_code: '2017092915',
                  link:
                    'http://o9xrgfdni.bkt.clouddn.com/1007/other/EXEIStar_xinghui2017092915.apk',
                  created_at: '2017-09-29 15:53:02',
                  updated_at: '2017-09-29 15:53:02'
                }
              },
              {
                id: 6,
                date_start: 901,
                date_end: 1300,
                project: {
                  id: 10,
                  name: '漫威主题',
                  info: '漫威主题',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/44_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/276_manhua_capture.png',
                  alias: 'manhua',
                  version_code: '2018012013',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/EXEIStar_Manhua2018012013.apk',
                  created_at: '2018-01-20 14:22:56',
                  updated_at: '2018-01-20 14:22:56'
                }
              },
              {
                id: 6,
                date_start: 1301,
                date_end: 1700,
                project: {
                  id: 9,
                  name: '小泰良品',
                  info: '小泰良品',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/834_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1504597171.png',
                  alias: 'xt',
                  version_code: '2017102716',
                  link:
                    'http://o9xrgfdni.bkt.clouddn.com/1007/other/XiaoTaiLiangPinNew_2017102716.apk',
                  created_at: '2017-10-27 16:24:38',
                  updated_at: '2017-10-27 16:24:38'
                }
              }
            ]
          }
        },
        {
          id: 5,
          name: '七宝模板',
          schedules: {
            data: [
              {
                id: 5,
                date_start: 1,
                date_end: 1600,
                project: {
                  id: 1,
                  name: '颜值验钞机',
                  info: '安装完毕后一定要重启。。。',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/674_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/14_ColorPrint.jpg',
                  alias: 'ColorPrint',
                  version_code: '2018011220',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/ColorPrintMachine2018011220.apk',
                  created_at: '2018-01-12 20:51:46',
                  updated_at: '2018-01-12 20:51:46'
                }
              },
              {
                id: 5,
                date_start: 1601,
                date_end: 2359,
                project: {
                  id: 5,
                  name: '镜视界',
                  info: '线下店的FaceU版本',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/234_istar2_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/460_istar2_bg.jpg',
                  alias: 'istar2',
                  version_code: '2018032015',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/istar2_2018032015.apk',
                  created_at: '2018-03-20 14:37:39',
                  updated_at: '2018-03-20 14:37:39'
                }
              }
            ]
          }
        },
        {
          id: 8,
          name: '小泰+星辉+漫威',
          schedules: {
            data: [
              {
                id: 8,
                date_start: 901,
                date_end: 1300,
                project: {
                  id: 9,
                  name: '小泰良品',
                  info: '小泰良品',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/834_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1504597171.png',
                  alias: 'xt',
                  version_code: '2017102716',
                  link:
                    'http://o9xrgfdni.bkt.clouddn.com/1007/other/XiaoTaiLiangPinNew_2017102716.apk',
                  created_at: '2017-10-27 16:24:38',
                  updated_at: '2017-10-27 16:24:38'
                }
              },
              {
                id: 8,
                date_start: 1301,
                date_end: 1700,
                project: {
                  id: 15,
                  name: '星辉游戏',
                  info: '星辉游戏',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/100_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1506047473.png',
                  alias: 'gamexh',
                  version_code: '2017092915',
                  link:
                    'http://o9xrgfdni.bkt.clouddn.com/1007/other/EXEIStar_xinghui2017092915.apk',
                  created_at: '2017-09-29 15:53:02',
                  updated_at: '2017-09-29 15:53:02'
                }
              },
              {
                id: 8,
                date_start: 1701,
                date_end: 2200,
                project: {
                  id: 10,
                  name: '漫威主题',
                  info: '漫威主题',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/44_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/276_manhua_capture.png',
                  alias: 'manhua',
                  version_code: '2018012013',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/EXEIStar_Manhua2018012013.apk',
                  created_at: '2018-01-20 14:22:56',
                  updated_at: '2018-01-20 14:22:56'
                }
              }
            ]
          }
        },
        {
          id: 9,
          name: '小泰+星辉',
          schedules: {
            data: [
              {
                id: 9,
                date_start: 901,
                date_end: 1700,
                project: {
                  id: 9,
                  name: '小泰良品',
                  info: '小泰良品',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/834_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1504597171.png',
                  alias: 'xt',
                  version_code: '2017102716',
                  link:
                    'http://o9xrgfdni.bkt.clouddn.com/1007/other/XiaoTaiLiangPinNew_2017102716.apk',
                  created_at: '2017-10-27 16:24:38',
                  updated_at: '2017-10-27 16:24:38'
                }
              },
              {
                id: 9,
                date_start: 1701,
                date_end: 2200,
                project: {
                  id: 15,
                  name: '星辉游戏',
                  info: '星辉游戏',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/100_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1506047473.png',
                  alias: 'gamexh',
                  version_code: '2017092915',
                  link:
                    'http://o9xrgfdni.bkt.clouddn.com/1007/other/EXEIStar_xinghui2017092915.apk',
                  created_at: '2017-09-29 15:53:02',
                  updated_at: '2017-09-29 15:53:02'
                }
              }
            ]
          }
        },
        {
          id: 10,
          name: '蘑菇街',
          schedules: {
            data: [
              {
                id: 10,
                date_start: 1500,
                date_end: 1700,
                project: {
                  id: 76,
                  name: '静音蘑菇街女装',
                  info: '静音蘑菇街女装',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/435_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/876_mushroom_street.png',
                  alias: 'muteMSGirls',
                  version_code: '2018032015',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/muteMSGirls_2018032015.apk',
                  created_at: '2018-03-20 15:36:37',
                  updated_at: '2018-03-20 15:36:37'
                }
              },
              {
                id: 10,
                date_start: 1000,
                date_end: 1200,
                project: {
                  id: 76,
                  name: '静音蘑菇街女装',
                  info: '静音蘑菇街女装',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/435_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/876_mushroom_street.png',
                  alias: 'muteMSGirls',
                  version_code: '2018032015',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/muteMSGirls_2018032015.apk',
                  created_at: '2018-03-20 15:36:37',
                  updated_at: '2018-03-20 15:36:37'
                }
              },
              {
                id: 10,
                date_start: 1200,
                date_end: 1500,
                project: {
                  id: 74,
                  name: '蘑菇街拼团',
                  info: '蘑菇街拼团',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1521094884.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/108_qq.png',
                  alias: 'cvMushroom',
                  version_code: '2018050210',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/cvMushroom_2018050210.apk',
                  created_at: '2018-05-02 10:40:59',
                  updated_at: '2018-05-02 10:40:59'
                }
              },
              {
                id: 10,
                date_start: 1700,
                date_end: 2200,
                project: {
                  id: 74,
                  name: '蘑菇街拼团',
                  info: '蘑菇街拼团',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/1521094884.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/108_qq.png',
                  alias: 'cvMushroom',
                  version_code: '2018050210',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/cvMushroom_2018050210.apk',
                  created_at: '2018-05-02 10:40:59',
                  updated_at: '2018-05-02 10:40:59'
                }
              }
            ]
          }
        },
        {
          id: 11,
          name: '测试--mogu+东方商厦',
          schedules: {
            data: [
              {
                id: 11,
                date_start: 1330,
                date_end: 1400,
                project: {
                  id: 76,
                  name: '静音蘑菇街女装',
                  info: '静音蘑菇街女装',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/435_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/876_mushroom_street.png',
                  alias: 'muteMSGirls',
                  version_code: '2018032015',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/muteMSGirls_2018032015.apk',
                  created_at: '2018-03-20 15:36:37',
                  updated_at: '2018-03-20 15:36:37'
                }
              },
              {
                id: 11,
                date_start: 1400,
                date_end: 1430,
                project: {
                  id: 82,
                  name: '王者嘻哈夜',
                  info: '王者嘻哈夜 通用',
                  icon:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/647_icon.png',
                  image:
                    'http://o9xrbl1oc.bkt.clouddn.com/1007/image/448_preview.png',
                  alias: 'gamemwzhxhy',
                  version_code: '2018050412',
                  link:
                    'http://o9xrzyznl.bkt.clouddn.com/1007/other/EXEIStar_WangZheXiHaYe2018050412.apk',
                  created_at: '2018-05-04 12:36:00',
                  updated_at: '2018-05-04 12:36:00'
                }
              }
            ]
          }
        }
      ],
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      areaList: [],
      marketList: [],
      pointList: [],
      searchForm: {
        area_id: '',
        market_id: '',
        point_id: ''
      },
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      searchLoading: false
    }
  },
  created() {
    this.getAreaList()
  },
  methods: {
    objectSpanMethod({ row, column, rowIndex, columnIndex }) {
      console.log(row)
      // console.log(rowIndex)
      // if (columnIndex === 0) {
      //   if (rowIndex % 2 === 0) {
      //     return {
      //       rowspan: 2,
      //       colspan: 1
      //     };
      //   } else {
      //     return {
      //       rowspan: 0,
      //       colspan: 0
      //     };
      //   }
      // }
    },
    addSchedule() {
      this.$router.push({
        path: '/project/schedule/add'
      })
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
            this.searchForm.market_id = ''
            this.marketList = []
          }
          this.searchLoading = false
        })
        .catch(err => {
          console.log(err)
          this.searchLoading = false
        })
    },
    getPoint() {
      let args = {
        include: 'market',
        market_id: this.searchForm.market_id
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
    areaChangeHandle() {
      this.searchForm.market_id = ''
      this.searchForm.point_id = ''
      this.getMarket()
    },
    marketChangeHandle() {
      this.searchForm.point_id = ''
      this.getPoint()
    },
    search() {},
    changePage() {}
  }
}
</script>
<style lang="less" scoped>
.schedule-wrap {
  background: #fff;
  padding: 30px;
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
  .item-input {
    width: 200px;
  }
  .item-select {
    width: 200px;
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
</style>
