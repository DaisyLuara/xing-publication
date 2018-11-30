<template>
  <div class="logout">
    <div
      class="logo-wrap">
      <div
        class="logo">
        <img
          src="../../assets/images/logo.png">
      </div>
    </div>
    <el-dropdown 
      :hide-on-click="true"
      class="avatar-wrap">
      <div class="avatar-block">
        <span>
        {{ name }}
        </span>
        <img 
          src="~assets/images/user-default-icon.png" 
          alt="" 
          class="avatar">
      </div>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item><span @click="handleUser">账号设置</span></el-dropdown-item>
        <el-dropdown-item divided><span @click="intoCenter">个人中心</span></el-dropdown-item>
        <el-dropdown-item divided><span @click="logout">退出</span></el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </div>
</template>
<script>
import { Dropdown, DropdownItem, DropdownMenu } from 'element-ui'
import auth from 'service/auth'

export default {
  components: {
    'el-dropdown': Dropdown,
    'el-dropdown-item': DropdownItem,
    'el-dropdown-menu': DropdownMenu
  },
  data() {
    return {
      visible: false,
      name: null
    }
  },
  created() {
    let user_info = JSON.parse(this.$cookie.get('user_info'))
    this.name = user_info.name
  },
  methods: {
    logout() {
      this.visible = false
      auth.logout(this)
    },
    handleUser() {
      this.$router.push({
        path: '/account/account'
      })
    },
    intoCenter() {
      this.$router.push({
        path: '/account/center'
      })
    }
  }
}
</script>

<style lang="less" scoped>
.logout {
  height: 60px;
  position: fixed;
  top: 0;
  background: #222830;
  left: 0;
  right: 0;
  width: 100%;
  z-index: 130;
  .logo-wrap {
    position: relative;
    display: flex;
    margin-left: 20px;
    width: 100%;
    height: 60px;
    .logo {
      width: 60px;
      display: flex;
      justify-content: center;
      align-items: center;
      img {
        width: 100%;
      }
    }
  }
  .logout-btn {
    display: block;
    width: 100%;
    height: 35px;
    line-height: 35px;
    cursor: pointer;
    font-size: 14px;
  }

  .avatar-wrap {
    position: absolute;
    top: 0;
    right: 30px;
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    cursor: pointer;
    .avatar-block {
      span {
        margin-right: 10px;
      }
      height: 60px;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-left: 10px;
    }
    .avatar {
      height: 50%;
      // margin: 15%;
      border-radius: 50%;
    }
  }
}
</style>
