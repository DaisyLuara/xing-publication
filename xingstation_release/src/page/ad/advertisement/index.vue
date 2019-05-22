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
                  prop="adTrade">
                  <el-select
                    v-model="adSearchForm.ad_trade_id"
                    filterable
                    placeholder="请搜索广告行业"
                    clearable>
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
                  prop="type">
                  <el-input
                    v-model="adSearchForm.name"
                    placeholder="模糊查询广告素材"/>
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
            广告素材数量: {{ pagination.total }}
          </span>
          <el-button
            size="small"
            type="success"
            @click="linkToAdd">新增广告素材
          </el-button>
        </div>
        <el-table
          ref="multipleTable"
          :data="adList"
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
                  label="ID">
                  <span>{{ scope.row.id }}</span>
                </el-form-item>
                <el-form-item
                  label="广告行业">
                  <span>{{ scope.row.ad_trade_name }}</span>
                </el-form-item>
                <el-form-item
                  label="类型">
                  <span>{{ scope.row.type_text }}</span>
                </el-form-item>
                <el-form-item
                  label="素材名称">
                  <span>{{ scope.row.name }}</span>
                </el-form-item>
                <el-form-item
                  label="附件">
                  <a
                    :href="scope.row.link"
                    target="_blank"
                    style="color: blue">点击查看</a>
                </el-form-item>
                <el-form-item
                  label="附件大小">
                  <span>{{ scope.row.size }} M</span>
                </el-form-item>
                <el-form-item
                  label="广告标记">
                  <span>{{ scope.row.isad_text }}</span>
                </el-form-item>
                <el-form-item
                  label="创建人">
                  <span>{{ scope.row.create_user_name }}</span>
                </el-form-item>
                <el-form-item
                  label="创建时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            prop="id"
            label="ID"
            min-width="50"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="ad_trade_name"
            label="广告行业"
            min-width="60"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="type_text"
            label="类型"
            min-width="60"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            label="素材名称"
            min-width="120">
            <template slot-scope="scope">
              <span>{{ scope.row.name }}</span>
              <br/>
              <span>
                <img
                  :src="(scope.row.type === 'static' || scope.row.type === 'gif' ) ? scope.row.link : scope.row.img"
                  width="40px"/>
              </span>
            </template>
          </el-table-column>
          <el-table-column
            min-width="80"
            label="附件">
            <template slot-scope="scope">
              <a
                :href="scope.row.link"
                target="_blank"
                style="color: blue">点击查看</a>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            label="创建人"
            min-width="80">
            <template slot-scope="scope">
              <span>{{ scope.row.create_user_name ? scope.row.create_user_name : '' }}</span>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="isad_text"
            label="广告标记"
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
                type="success"
                @click="linkToEdit(scope.row.id)">编辑模版
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
    getSearchAdTradeList,

    getAdList,
    saveAd,
    modifyAd

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

        adSearchForm: {
          ad_trade_id: '',
          name: '',
        },
        pagination: {
          total: 0,
          pageSize: 10,
          currentPage: 1
        },
        adList: []
      }
    },
    created() {
      this.setting.loading = true
      let getSearchAdTradeList = this.getSearchAdTradeList()
      let getAdList = this.getAdList();
      Promise.all([getSearchAdTradeList, getAdList])
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

      getAdList() {
        this.setting.loadingText = '拼命加载中'
        this.setting.loading = true
        let searchArgs = {
          page: this.pagination.currentPage,
          ad_trade_id: this.adSearchForm.ad_trade_id,
          name: this.adSearchForm.name,
        }
        return getAdList(this, searchArgs)
          .then(response => {
            this.adList = response.data
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
        this.getAdList()
      },
      resetSearch(formName) {
        this.adSearchForm.ad_trade_id = ''
        this.adSearchForm.name = ''
        this.pagination.currentPage = 1
        this.getAdList()
      },
      changePage(currentPage) {
        this.pagination.currentPage = currentPage
        this.getAdList()
      },
      linkToAdd() {
        this.$router.push({
          path: '/ad/advertisement/add'
        })
      },
      linkToEdit(id) {
        this.$router.push({
          path: '/ad/advertisement/edit/' + id
        })
      },

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
