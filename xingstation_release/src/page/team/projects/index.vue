<template>
  <div 
    class="root">
    <div
      v-loading="setting.loading" 
      :element-loading-text="setting.loadingText" 
      class="item-list-wrap">
      <div 
        class="item-content-wrap">
        <el-card 
          class="box-card">
          <div 
            class="search-wrap">
            <el-form 
              ref="searchForm" 
              :model="filters" 
              :inline="true">
              <el-form-item  
                v-for="item in titleArr" 
                :key="item.id" 
                label="" >
                <el-button
                  v-if="item.id === 0" 
                  :class="{'active': item.id == active}" 
                  size="small" 
                  icon="el-icon-star-off"
                  class="btn"
                  @click="changePage('0')"/>
                <el-button  
                  v-else
                  :class="{'active': item.id == active}"
                  size="small" 
                  class="btn" 
                  @click="changePage(item)" >{{ item.attributes.name }}</el-button>
              </el-form-item>
            </el-form>
            <el-button
              size="small"
              plain
              @click="addProject">新建项目</el-button>
          </div>
          <el-table
            :data="allProjectsList"
            :show-header="false" 
            :cell-class-name="tableColClassName"
            style="width: 100%" 
          >
            <el-table-column
              prop=""
              label="">
              <template 
                slot-scope="scope">
                <div
                  @click="goProjectTask(scope.row)">{{ scope.row.attributes.name }}</div>
              </template>
            </el-table-column>
          </el-table>
        </el-card>
      </div>  
    </div>
  </div>
</template>

<script>
import team from 'service/team'
import auth from 'service/auth'

import {
  Button,
  Input,
  Table,
  TableColumn,
  Form,
  FormItem,
  MessageBox,
  Card
} from 'element-ui'

export default {
  components: {
    'el-table': Table,
    'el-table-column': TableColumn,
    'el-button': Button,
    'el-input': Input,
    'el-form': Form,
    'el-form-item': FormItem,
    'el-card': Card
  },
  data() {
    return {
      filters: {
        name: ''
      },
      SERVER_URL: process.env.SERVER_URL,
      active: '1',
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      dataValue: '',
      loading: true,
      arUserName: '',
      dataShowFlag: true,
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      emptyText: '暂无数据',
      titleArr: [],
      allProjectsList: [],
      projectsList: []
    }
  },
  created() {
    auth
      .refreshUserInfo(this)
      .then(res => {
        this.getTeamsList()
      })
      .catch(err => {
        console.log(err)
        this.setting.loading = false
      })
  },
  methods: {
    goProjectTask(item) {
      this.$router.push({
        path: '/team/projects/task',
        query: { id: item.id, name: item.attributes.name }
      })
    },
    tableColClassName({ row, column, rowIndex, columnIndex }) {
      return 'col-td'
    },
    changePage(item) {
      if (item === '0') {
        this.active = '0'
        var pinnedArr = this.projectsList.filter(
          project => project.attributes.is_pinned == true
        )
        this.allProjectsList = pinnedArr
      } else if (item.id == '1') {
        this.active = item.id
        this.allProjectsList = this.projectsList
      } else {
        this.active = item.id
        let arr = []
        for (var i = 0; i < this.projectsList.length; i++) {
          for (
            var j = 0;
            j < this.projectsList[i].relationships.project_groups.data.length;
            j++
          ) {
            if (
              item.id ==
              this.projectsList[i].relationships.project_groups.data[j].id
            ) {
              arr.push(this.projectsList[i])
              break
            }
          }
        }
        this.allProjectsList = arr
      }
    },
    getTeamsList() {
      this.setting.loadingText = '拼命加载中'
      this.setting.loading = true
      let id = 'c6dc912c2f494e7ea73bed4488bb3493'
      return team
        .getProjectsList(this, id)
        .then(response => {
          if (response) {
            this.allProjectsList = response.data
            this.titleArr = response.included

            this.titleArr.unshift(
              {
                id: '0',
                type: 'project_groups',
                attributes: {
                  name: '',
                  display_order: 0
                }
              },
              {
                id: '1',
                type: 'project_groups',
                attributes: {
                  name: '所有项目',
                  display_order: 0
                }
              }
            )

            this.projectsList = this.allProjectsList
          }
          this.setting.loading = false
        })
        .catch(err => {
          console.log(err)
          this.setting.loading = false
        })
    },
    addProject() {
      this.$router.push({
        path: '/team/projects/add'
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
    .el-table {
      font-size: 18px;
      color: #333;
    }
    .el-table--enable-row-hover .el-table__body tr:hover > td {
      background-color: #fbfdf7;
    }
    .el-button:hover {
      color: #606266;
    }
    .item-content-wrap {
      position: relative;
      width: 960px;
      margin: 0 auto;
      .search-wrap {
        margin-top: 5px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
        margin-bottom: 10px;
        .btn {
          background-color: #f0f0f0;
          border: 1px solid #f0f0f0;
          border-radius: 20px;
        }
        .active {
          background-color: #aed4d1;
          border: 1px solid #aed4d1;
          color: #fff;
        }
        .el-form-item {
          margin-bottom: 10px;
        }
        .el-select {
          width: 250px;
        }
        .item-input {
          width: 230px;
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
      .total-wrap {
        margin-top: 5px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
        margin-bottom: 10px;
        .label {
          font-size: 14px;
          margin: 5px 0;
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
