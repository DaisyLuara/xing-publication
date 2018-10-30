<template>
  <div class="logout">
    <div
      class="logo-wrap">
      <div
        class="logo">
        <img
          src="../../assets/images/exe-logo-white-circle.png">
      </div>
    </div>
    <el-popover
      ref="popover"
      v-model="visible"
      placement="left"
      width="80"
      trigger="hover"
      popper-class="popper-logout">
      <span 
        class="logout-btn"
        @click="logout">登出</span>
    </el-popover>
    <div 
      v-popover:popover
      class="avatar-wrap" 
      @click="handleUser">
      <span>{{ name }}</span>
      <div style="height: 75px;">
        <img 
          src="~assets/images/user-default-icon.png" 
          alt="" 
          class="avatar">
      </div>
    </div>
  </div>
</template>
<script>
import { Popover } from 'element-ui'
import auth from 'service/auth'

export default {
  components: {
    'el-popover': Popover
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
    }
  }
}
</script>

<style lang="less" scoped>
.logout {
  height: 80px;
  background: #222830;
  .logo-wrap {
    position: relative;
    display: flex;
    margin-left: 20px;
    width: 100%;
    height: 75px;
    .logo {
      width: 60px;
      height: 60px;
      border-radius: 25px;

      img {
        width: 100%;
        height: 100%;
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
    height: 80px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    cursor: pointer;
    .avatar {
      height: 70%;
      margin: 15%;
      border-radius: 50%;
    }
  }
}
</style>
