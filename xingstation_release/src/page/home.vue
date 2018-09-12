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
      <el-popover
        ref="popover"
        v-model="visible"
        placement="top"
        width="80"
        trigger="hover"
        popper-class="popper-logout">
        <span 
          class="logout-btn"
          @click="logout">登出</span>
      </el-popover>
      <div 
        v-popover:popover 
        class="sidebar-user" 
        @click="handleUser">
        <img 
          src="../assets/images/user-default-icon.png" 
          alt="" 
          class="avatar">
        <div 
          class="sidebar-user-block">
          <p 
            class="sidebar-user-item sidebar-user-item-main" 
            style="font-size: 18px;">{{ name }}</p>
          <p 
            class="sidebar-user-item sidebar-user-item-sub"
            style="font-size: 14px;">{{ role }}</p>
        </div>
      </div>
    </div>
    <div 
      class="modules">
      <router-view/>
    </div>
  </div>
</template>

<script>
import { Menu, MenuItem, Popover, Button, Badge, Icon } from 'element-ui'
import auth from 'service/auth'
import notice from 'service/notice'

export default {
  name: 'Home',
  components: {
    'el-menu': Menu,
    'el-menu-item': MenuItem,
    'el-popover': Popover,
    'el-button': Button,
    'el-badge': Badge
  },
  data() {
    return {
      visible: false,
      setIntervalValue: ''
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
    name() {
      return this.$store.state.curUserInfo.name
        ? this.$store.state.curUserInfo.name
        : ''
    },
    role() {
      if ('roles' in this.$store.state.curUserInfo) {
        return this.$store.state.curUserInfo.roles.data.length > 0
          ? this.$store.state.curUserInfo.roles.data[0].display_name
          : ''
      }
      return ''
    },
    noticeCount() {
      return this.$store.state.notificationCount.noticeCount
    }
  },
  created() {
    let userInfo = JSON.parse(localStorage.getItem('user_info'))
    this.$store.commit('setCurUserInfo', userInfo)
    this.notificationStats()
  },
  methods: {
    logout() {
      this.visible = false
      auth.logout(this)
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
    },
    handleUser() {
      this.$router.push({
        path: '/account/account'
      })
    }
  }
}
</script>

<style lang="less">
@import '../assets/css/pcCommon.less';
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

.el-popover.popper-logout {
  padding: 0;
  min-width: 80px;
  text-align: center;
}
.logout-btn {
  display: block;
  width: 100%;
  height: 35px;
  line-height: 35px;
  cursor: pointer;
  font-size: 14px;
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
