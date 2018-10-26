<template>
  <div 
    class="main">
    <div 
      class="first-sidebar">
      <div 
        class="logo-wrap">
        <div 
          class="logo">
          <img 
            src="../assets/images/exe-logo-white-circle.png" >
        </div>
      </div>
      <el-menu 
        :default-active="'/' + currModule" 
        router >
        <el-menu-item 
          v-for="m in modules"
          v-if="m.path != 'inform'"
          :key="m.path" 
          :index="'/' + m.path" 
          class="menu-item" >
          <img 
            :src="m.src" 
            class="first-sidebar-icon">
          {{ m.meta.title }}
        </el-menu-item>
        <el-menu-item 
          class="menu-item" 
          index="/inform">
          <el-badge 
            :value="noticeCount" 
            :max="99" 
            class="item">
            <img 
              src="../assets/images/icons/notification-icon.png" 
              class="first-sidebar-icon" 
              style="padding-right: 3px;">
            通知
          </el-badge>
        </el-menu-item>
      </el-menu>
    </div>
    <div class="system-menu">
      <div 
        v-for="item in systemMenuList" 
        :key="item.id" 
        :class="{'active': active === item.id}" 
        class="system-menu-item" 
        @click="systemMenu(item)" >{{ item.name }}</div>
    </div>
    <div 
      class="modules">
      <router-view/>
    </div>
  </div>
</template>

<script>
import { Menu, MenuItem, Button, Badge, Icon } from 'element-ui'
import auth from 'service/auth'
import { Cookies } from 'utils/cookies'
import notice from 'service/notice'
const DOMAIN = process.env.DOMAIN

export default {
  name: 'Home',
  components: {
    'el-menu': Menu,
    'el-menu-item': MenuItem,
    'el-button': Button,
    'el-badge': Badge
  },
  data() {
    return {
      visible: false,
      setIntervalValue: '',
      systemMenuList: [
        {
          id: 'zhongtai',
          name: '中台系统'
        },
        {
          id: 'liucheng',
          name: '流程管理'
        }
      ],
      active: 'zhongtai'
    }
  },
  computed: {
    modules() {
      let items = []
      for (let route of this.$router.options.routes) {
        if (route.path == '/') {
          for (let m of route['children']) {
            if (!auth.checkPathPermission(m) || !m.meta || !m.meta.title) {
              continue
            }
            switch (m.path) {
              case 'project':
                m.src = require('../assets/images/icons/project-icon.png')
                break
              case 'system':
                m.src = require('../assets/images/icons/permission-icon.png')
                break
              case 'company':
                m.src = require('../assets/images/icons/company-icon.png')
                break
              case 'ad':
                m.src = require('../assets/images/icons/advertisement-icon.png')
                break
              case 'equipment':
                m.src = require('../assets/images/icons/device-icon.png')
                break
              case 'team':
                m.src = require('../assets/images/icons/team-icon.png')
                break
              case 'market':
                m.src = require('../assets/images/icons/market-icon.png')
                break
              case 'home':
                m.src = require('../assets/images/icons/home-icon.png')
                break
              case 'report':
                m.src = require('../assets/images/icons/report-icon.png')
                break
              case 'setting':
                m.src = require('../assets/images/icons/setting-icon.png')
                break
              default:
                m.src = ''
                break
            }
            items.push(m)
          }
        }
      }
      return items
    },
    currModule() {
      let path = this.$store.getters.currRoute.path
      for (let m of this.modules) {
        if (path.indexOf('/' + m.path) == 0) {
          return m.path
        }
      }
      return ''
    },
    noticeCount() {
      return this.$store.state.notificationCount.noticeCount
    }
  },
  created() {
    if (Cookies.get('jwt_token') && !localStorage.getItem('jwt_token')) {
      localStorage.setItem('jwt_token', Cookies.get('jwt_token'))
      localStorage.setItem('jwt_ttl', Cookies.get('jwt_ttl'))
      localStorage.setItem('jwt_begin_time', Cookies.get('jwt_begin_time'))
      localStorage.setItem('user_info', Cookies.get('user_info'))
      localStorage.setItem('permissions', Cookies.get('permissions'))
    } else if (!Cookies.get('jwt_token')) {
      localStorage.removeItem('jwt_token')
      localStorage.removeItem('user_info')
      localStorage.removeItem('jwt_ttl')
      localStorage.removeItem('permissions')
      localStorage.removeItem('jwt_begin_time')
    }

    let userInfo = JSON.parse(localStorage.getItem('user_info'))
    this.$store.commit('setCurUserInfo', userInfo)
    this.notificationStats()
  },
  methods: {
    systemMenu(item) {
      this.active = item.id
      switch (item.id) {
        case 'zhongtai':
          window.location.href = 'http://ad.' + DOMAIN + '/login'
          // window.opne('http://devad.' + DOMAIN + '/login')

          break
        case 'liucheng':
          console.log(33)
          // window.opne('http://devflow.' + DOMAIN + '/login')

          window.location.href = 'http://flow.' + DOMAIN + '/login'
          break
        default:
          window.location.href = 'http://ad.xingstation.com/login'
          break
      }
    },
    notificationStats() {
      return notice
        .notificationStats(this)
        .then(response => {
          response.setIntervalValue = this.setIntervalValue
          this.$store.commit('saveNotificationState', response)
        })
        .catch(err => {
          console.log(err)
        })
    }
  }
}
</script>

<style lang="less">
@import '../assets/css/pcCommon.less';
.system-menu {
  display: flex;
  position: relative;
  width: 100%;
  padding-left: 90px;
  flex-flow: row;
  height: 50px;
  justify-content: center;
  align-items: center;
  border-bottom: 1px solid #ccc8c8;
  .system-menu-item {
    margin-right: 35px;
    height: 50px;
    line-height: 50px;
    cursor: pointer;
    &.active {
      border-bottom: 2px solid #2196f3;
    }
  }
}
.menu-item {
  display: flex;
  flex-direction: row;
  align-items: center;
}
.el-badge__content {
  border: none;
}
.el-badge__content.is-fixed {
  top: 10px;
  right: 45px;
}
.logo-wrap {
  .logo {
    margin-top: 15px;
  }
}
.modules-top {
  padding-top: 0;
}

.first-sidebar-icon {
  padding-right: 8px;
  margin-left: -3px;
  height: 16px;
}
.sidebar-user {
  position: absolute;
  bottom: 0;
  display: table;
  width: 100%;
  height: 90px;
  text-align: center;
  // background: #20a0ff url(../assets/images/user-bg.png) no-repeat center 5px;
  color: #fff;
  cursor: pointer;
  .avatar {
    width: 100%;
  }
}
.sidebar-user-block {
  position: absolute;
  z-index: 33;
  top: 10%;
  left: 16%;
  right: 16%;
  color: #000;
  font-weight: 600;
  // display: table-cell;
  // vertical-align: middle;
}
.sidebar-user-item {
  max-width: 90px;
  margin: 10px 0;
  overflow: hidden;
  text-overflow: ellipsis;
  &.sidebar-user-item-main {
    font-size: 20px;
  }
  &.sidebar-user-item-sub {
    font-size: 14px;
  }
}
</style>
