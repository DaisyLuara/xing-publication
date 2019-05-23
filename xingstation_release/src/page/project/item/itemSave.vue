<template>
  <div class="item-wrap-template">
    <div 
      v-loading="setting.loading" 
      :element-loading-text="setting.loadingText" 
      class="pane">
      <div class="pane-title">新增节目投放</div>
      <el-form 
        ref="projectForm" 
        :model="projectForm" 
        label-width="150px">
        <el-form-item
          :rules="[{ required: true, message: '请输入节目名称', trigger: 'submit'}]"
          label="节目名称"
          prop="project"
        >
          <el-select
            v-model="projectForm.project"
            :loading="searchLoading"
            :remote-method="getProject"
            :multiple-limit="1"
            multiple
            filterable
            placeholder="请搜索"
            remote
            clearable
            @change="projectChangeHandle"
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
          :rules="[{ required: true, message: '请选择皮肤', trigger: 'submit'}]"
          label="皮肤"
          prop="default_bid"
        >
          <el-select
            v-model="projectForm.default_bid"
            :loading="searchLoading"
            placeholder="请选择皮肤"
            filterable
            clearable
          >
            <el-option
              v-for="item in skinList"
              :key="item.bid"
              :label="item.name"
              :value="item.bid"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请输入区域', trigger: 'submit' ,type: 'number'}]"
          label="区域"
          prop="area"
        >
          <el-select
            v-model="projectForm.area"
            placeholder="请选择"
            filterable
            clearable
            @change="areaChangeHandle"
          >
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
          prop="market"
        >
          <el-select
            v-model="projectForm.market"
            :remote-method="getMarket"
            :loading="searchLoading"
            :multiple-limit="1"
            multiple
            placeholder="请搜索"
            filterable
            remote
            clearable
            @change="marketChangeHandle"
          >
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请输入点位', trigger: 'submit',type: 'array'}]"
          label="点位"
          prop="point"
        >
          <el-select
            v-model="projectForm.point"
            :loading="searchLoading"
            :multiple-limit="10"
            placeholder="请选择"
            multiple
            filterable
            clearable
          >
            <el-option
              v-for="item in pointList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="星期一模版">
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
        <el-form-item label="星期二模版">
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
        <el-form-item label="星期三模版">
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
        <el-form-item label="星期四模版">
          <el-select 
            v-model="projectForm.day4_tvid" 
            placeholder="请选择" 
            filterable 
            clearable>
            <el-option
              v-for="item in thursList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="星期五模版">
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
        <el-form-item label="星期六模版">
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
        <el-form-item label="星期日模版">
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
        <el-form-item label="工作日模版">
          <el-select 
            v-model="projectForm.weekday" 
            placeholder="请选择" 
            filterable 
            clearable>
            <el-option
              v-for="item in weekdayList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="周末模版">
          <el-select 
            v-model="projectForm.weekend" 
            placeholder="请选择" 
            filterable 
            clearable>
            <el-option
              v-for="item in weekendList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="自定义模版">
          <el-select 
            v-model="projectForm.define" 
            placeholder="请选择" 
            filterable 
            clearable>
            <el-option
              v-for="item in defineList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item 
          label="自定义开始时间" 
          prop="sdate">
          <el-date-picker
            v-model="projectForm.sdate"
            :editable="false"
            type="date"
            placeholder="请选择自定义开始时间"
          />
        </el-form-item>
        <el-form-item 
          label="自定义结束时间" 
          prop="edate">
          <el-date-picker
            v-model="projectForm.edate"
            :editable="false"
            type="date"
            placeholder="请选择自定义结束时间"
            value-format="yyyy-MM-dd"
          />
        </el-form-item>
        <el-form-item>
          <el-button 
            type="primary" 
            @click="submit('projectForm')">完成</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import {
  savePorjectLaunch,
  getSearchModuleList,
  getSearchProjectList,
  getSearchAeraList,
  getSearchMarketList,
  getSearchPointList,
  getSearchSkin
} from "service";
import {
  Form,
  Select,
  Option,
  FormItem,
  Button,
  Input,
  DatePicker,
  MessageBox
} from "element-ui";

export default {
  components: {
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input,
    ElDatePicker: DatePicker
  },
  data() {
    return {
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      skinList: [],
      monList: [],
      tueList: [],
      wedList: [],
      thursList: [],
      friList: [],
      satList: [],
      sunList: [],
      marketList: [],
      weekdayList: [],
      weekendList: [],
      defineList: [],
      pointList: [],
      projectList: [],
      searchLoading: false,
      projectForm: {
        default_bid: null,
        day1_tvid: "",
        day2_tvid: "",
        day3_tvid: "",
        day4_tvid: "",
        day5_tvid: "",
        day6_tvid: "",
        day7_tvid: "",
        project: [],
        area: "",
        market: [],
        point: [],
        weekday: "",
        weekend: "",
        define: "",
        sdate: "",
        edate: ""
      },
      areaList: []
    };
  },
  mounted() {},
  created() {
    this.setting.loading = true;
    this.getModuleList();
    this.getAreaList();
    this.setting.loading = false;
  },
  methods: {
    getSkin(val) {
      let args = {
        project_id: val
      };
      getSearchSkin(this, args)
        .then(result => {
          this.skinList = result;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    projectChangeHandle(val) {
      this.getSkin(val[0]);
    },
    getProject(query) {
      if (query !== "") {
        this.searchLoading = true;
        let args = {
          name: query
        };
        return getSearchProjectList(this, args)
          .then(response => {
            this.projectList = response.data;
            if (this.projectList.length == 0) {
              this.projectForm.project = [];
              this.projectList = [];
            }
            this.searchLoading = false;
          })
          .catch(err => {
            console.log(err);
            this.searchLoading = false;
          });
      } else {
        this.projectList = [];
      }
    },
    getModuleList() {
      return getSearchModuleList(this)
        .then(response => {
          let data = response.data;
          this.weekdayList = data;
          this.weekendList = data;
          this.monList = data;
          this.tueList = data;
          this.wedList = data;
          this.thursList = data;
          this.friList = data;
          this.satList = data;
          this.sunList = data;
          this.defineList = data;
        })
        .catch(error => {
          console.log(error);
          this.setting.loading = false;
        });
    },
    areaChangeHandle() {
      this.projectForm.market = [];
      this.projectForm.point = [];
      this.getMarket(this.projectForm.market);
    },
    getAreaList() {
      return getSearchAeraList(this)
        .then(response => {
          let data = response.data;
          this.areaList = data;
        })
        .catch(error => {
          console.log(error);
          this.setting.loading = false;
        });
    },
    marketChangeHandle() {
      this.projectForm.point = [];
      this.getPoint();
    },
    getPoint() {
      let args = {
        include: "market",
        market_id: this.projectForm.market
      };
      this.searchLoading = true;
      return getSearchPointList(this, args)
        .then(response => {
          this.pointList = response.data;
          this.searchLoading = false;
        })
        .catch(err => {
          this.searchLoading = false;
          console.log(err);
        });
    },
    getMarket(query) {
      if (query !== "") {
        this.searchLoading = true;
        let args = {
          name: query,
          include: "area",
          area_id: this.projectForm.area
        };
        return getSearchMarketList(this, args)
          .then(response => {
            this.marketList = response.data;
            if (this.marketList.length == 0) {
              this.projectForm.market = [];
              this.projectForm.marketList = [];
            }
            this.searchLoading = false;
          })
          .catch(err => {
            console.log(err);
            this.searchLoading = false;
          });
      } else {
        this.marketList = [];
      }
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.setting.loading = true;
          let edate =
            (new Date(this.projectForm.edate).getTime() +
              ((23 * 60 + 59) * 60 + 59) * 1000) /
            1000;
          let args = {
            sdate: new Date(this.projectForm.sdate).getTime() / 1000,
            edate: edate,
            default_bid:this.projectForm.default_bid,
            default_plid: this.projectForm.project[0],
            weekday_tvid: this.projectForm.weekday,
            weekend_tvid: this.projectForm.weekend,
            div_tvid: this.projectForm.define,
            oids: this.projectForm.point,
            day1_tvid: this.projectForm.day1_tvid,
            day2_tvid: this.projectForm.day2_tvid,
            day3_tvid: this.projectForm.day3_tvid,
            day4_tvid: this.projectForm.day4_tvid,
            day5_tvid: this.projectForm.day5_tvid,
            day6_tvid: this.projectForm.day6_tvid,
            day7_tvid: this.projectForm.day7_tvid
          };
          return savePorjectLaunch(this, args)
            .then(response => {
              this.setting.loading = false;
              this.$message({
                message: "添加成功",
                type: "success"
              });
              this.$router.push({
                path: "/project/item"
              });
            })
            .catch(err => {
              this.setting.loading = false;
              console.log(err);
            });
        }
      });
    }
  }
};
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
