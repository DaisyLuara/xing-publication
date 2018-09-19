<template>
  <div>
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
      visible: false
    }
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
.avatar-wrap {
  position: absolute;
  top: 0;
  right: 30px;
  height: 75px;
  cursor: pointer;
  .avatar {
    height: 50%;
    border-radius: 50%;
  }
}
</style>
