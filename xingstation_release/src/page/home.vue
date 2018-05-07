<template>
  <div class="main">
    <div class="first-sidebar">
      <div class="logo-wrap">
        <div class="logo">
          <img src="../assets/images/exe-logo-white-circle.png" />
        </div>
      </div>
      <el-menu router :default-active="'/' + currModule">
        <el-menu-item v-for="m in modules" :key="m.path" :index="'/' + m.path" class="menu-item" v-if="m.path != 'inform'">
          <img :src="m.src" class="first-sidebar-icon"/>
          {{m.meta.title}}
        </el-menu-item>
        <el-menu-item class="menu-item" index="/inform">
          <el-badge :value="200" :max="99" class="item">
            <el-button size="small">通知</el-button>
          </el-badge>
        </el-menu-item>
      </el-menu>
      <el-popover
        ref="popover"
        placement="top"
        width="80"
        v-model="visible"
        trigger="hover"
        popper-class="popper-logout">
        <span class="logout-btn" @click="logout">登出</span>
      </el-popover>
      <div class="sidebar-user" v-popover:popover @click="handleUser">
        <img src="https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/LOnMrqbHJn.png?imageView2/1/w/200/h/200" alt="" class="avatar"/>
        <div class="sidebar-user-block">
          <p class="sidebar-user-item sidebar-user-item-main" style="font-size: 20px;">{{name}}</p>
          <p class="sidebar-user-item sidebar-user-item-sub" style="font-size: 12px;">{{role}}</p>
        </div>
      </div>
    </div>
    <div class="modules">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import { Menu, MenuItem, Popover, Button, Badge} from 'element-ui'
import auth from 'service/auth'

export default {
  name: 'home',
  data() {
    return {
      visible: false,
    }
  },
  created() {
    let userInfo = JSON.parse(localStorage.getItem('user_info'))
    this.$store.commit('setCurUserInfo', userInfo)
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
                m.src = require('../assets/images/icons/warehouse-icon.png')
                break
              case 'system':
                m.src = require('../assets/images/icons/setting-icon.png')
                break
              case 'company':
                m.src = require('../assets/images/icons/marketing-icon.png')
                break
              case 'main':
                m.src = require('../assets/images/icons/advertisement-icon.png')
                break
              case 'contract':
                m.src = require('../assets/images/icons/fitting-icon.png')
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
          if (m.path === 'marketing') {
            this.memuFlag = true
          } else {
            this.memuFlag = false
          }
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
        return this.$store.state.curUserInfo.roles.length > 0
          ? this.$store.state.curUserInfo.roles[0].display_name
          : ''
      }
      return ''
    },
  },
  methods: {
    logout() {
      this.visible = false
      auth.logout(this)
    },
    handleUser(){
      console.log(2)
      this.$router.push({
        path: '/account/account/index'
      })
    }
  },
  components: {
    'el-menu': Menu,
    'el-menu-item': MenuItem,
    'el-popover': Popover,
    'el-button': Button,
    'el-badge': Badge
  },
}
</script>

<style lang="less">
@import '../assets/css/pcCommon.less';
.menu-item {
  display: flex;
  flex-direction: row;
  align-items: center;
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
  .avatar{
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
