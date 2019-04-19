<template>
  <div class="main">
    <headModule/>
    <!-- v-show="!iconMenuShow"  -->
    <div 
      class="first-sidebar" 
      @mouseleave="leaveIcon">
      <el-menu 
        :default-active="'/' + currModule" 
        :style="{'height':style.height}" 
        router>
        <el-menu-item
          v-for="m in modules"
          v-if="m.path != 'inform'"
          :key="m.path"
          :index="'/' + m.path"
          class="menu-item"
          @click="handleMenuHide"
        >
          <img 
            :src="m.src" 
            class="first-sidebar-icon">
          {{ m.meta.title }}
        </el-menu-item>
        <el-menu-item 
          class="menu-no-icon-item" 
          index="/inform" 
          @click="handleMenuHide">
          <el-badge 
            :value="noticeCount" 
            :max="99" 
            class="item">
            <img
              src="../assets/images/icons/notification-icon.png"
              class="first-sidebar-icon"
              style="padding-right: 3px;"
            >
            通知
          </el-badge>
        </el-menu-item>
      </el-menu>
      <div 
        v-show="false" 
        class="menu-show">
        <i 
          class="el-icon-d-arrow-left left-icon-menu" 
          @click="handleMenuHide"/>
      </div>
    </div>
    <!-- v-show="iconMenuShow" -->
    <div 
      v-show="false" 
      class="first-icon-sidebar" 
      @mouseenter="iconEnter">
      <el-menu 
        :default-active="'/' + currModule" 
        router>
        <el-menu-item
          v-for="m in modules"
          v-if="m.path != 'inform'"
          :key="m.path"
          :index="'/' + m.path"
          class="menu-item"
        >
          <img 
            :src="m.src" 
            class="first-sidebar-icon">
        </el-menu-item>
        <el-menu-item 
          class="menu-item menu-icon-item" 
          index="/inform">
          <el-badge 
            :value="noticeCount" 
            :max="99" 
            class="item">
            <img
              src="../assets/images/icons/notification-icon.png"
              class="first-sidebar-icon"
              style="padding-right: 3px;"
            >
          </el-badge>
        </el-menu-item>
      </el-menu>
      <div class="menu-icon-show">
        <i 
          class="el-icon-d-arrow-right right-icon-menu" 
          @click="handleMenuShow"/>
      </div>
    </div>

    <div class="system-menu">
      <div
        v-for="item in systemMenuList"
        :key="item.id"
        :class="{'active': active === item.id}"
        class="system-menu-item"
        @click="systemMenu(item)"
      >{{ item.name }}</div>
    </div>
    <div class="modules">
      <router-view/>
    </div>
  </div>
</template>

<script>
import { Menu, MenuItem, Button, Badge, Icon } from "element-ui";
import auth from "service/auth";
import { Cookies } from "utils/cookies";
import { notificationStats } from "service";

const NODE_ENV = process.env.NODE_ENV;

export default {
  name: "Home",
  components: {
    "el-menu": Menu,
    "el-menu-item": MenuItem,
    "el-button": Button,
    "el-badge": Badge
  },
  data() {
    return {
      iconMenuShow: true,
      visible: false,
      setIntervalValue: "",
      style: {
        height: 0
      },
      systemMenuList: [
        {
          id: "zhongtai",
          name: "中台系统"
        },
        {
          id: "liucheng",
          name: "流程管理"
        }
      ],
      active: "zhongtai"
    };
  },
  computed: {
    modules() {
      let items = [];
      for (let route of this.$router.options.routes) {
        if (route.path == "/") {
          for (let m of route["children"]) {
            if (!auth.checkPathPermission(m) || !m.meta || !m.meta.title) {
              continue;
            }
            switch (m.path) {
              case "project":
                m.src = require("../assets/images/icons/project-icon.png");
                break;
              case "system":
                m.src = require("../assets/images/icons/permission-icon.png");
                break;
              case "ad":
                m.src = require("../assets/images/icons/advertisement-icon.png");
                break;
              case "equipment":
                m.src = require("../assets/images/icons/device-icon.png");
                break;
              case "resource_auth":
                m.src = require("../assets/images/icons/auth_icon.png?v=1");
                break;
              case "team":
                m.src = require("../assets/images/icons/team-icon.png");
                break;
              case "market":
                m.src = require("../assets/images/icons/market-icon.png");
                break;
              case "home":
                m.src = require("../assets/images/icons/home-icon.png");
                break;
              case "report":
                m.src = require("../assets/images/icons/report-icon.png");
                break;
              case "activity":
                m.src = require("../assets/images/icons/activity-icon.png");
                break;
              case "prize":
                m.src = require("../assets/images/icons/prize-icon.png");
                break;
              case "feedback":
                m.src = require("../assets/images/icons/feedback_icon.png");
                break;
              default:
                m.src = "";
                break;
            }
            items.push(m);
          }
        }
      }
      return items;
    },
    currModule() {
      let path = this.$store.getters.currRoute.path;
      for (let m of this.modules) {
        if (path.indexOf("/" + m.path) == 0) {
          return m.path;
        }
      }
      return "";
    },
    noticeCount() {
      return this.$store.state.notificationCount.noticeCount;
    }
  },
  mounted() {
    let height = document.getElementsByClassName("first-sidebar")[0]
      .offsetHeight;
    this.style.height = height - 138 + "px";
  },
  created() {
    let userInfo = JSON.parse(this.$cookie.get("user_info"));
    this.$store.commit("setCurUserInfo", userInfo);
    this.notificationStats();
  },
  methods: {
    leaveIcon() {
      this.iconMenuShow = true;
    },
    iconEnter() {
      this.iconMenuShow = false;
    },
    handleMenuShow() {
      this.iconMenuShow = false;
    },
    handleMenuHide() {
      this.iconMenuShow = true;
    },
    systemMenu(item) {
      this.active = item.id;
      switch (item.id) {
        case "zhongtai":
          this.linkRedirect("ad");
          break;
        case "liucheng":
          this.linkRedirect("flow");
          break;
      }
    },
    linkRedirect(type) {
      let userInfo = this.$cookie.get("user_info");
      let jwt_ttl = this.$cookie.get("jwt_ttl");
      let token = this.$cookie.get("jwt_token");
      let jwt_begin_time = this.$cookie.get("jwt_begin_time");
      window.location.href =
        process.env.SERVER_URL +
        "/api/system_skip?user_info=" +
        userInfo +
        "&type=" +
        type +
        "&token=" +
        token +
        "&jwt_ttl=" +
        jwt_ttl +
        "&jwt_begin_time=" +
        jwt_begin_time;
    },
    notificationStats() {
      return notificationStats(this)
        .then(response => {
          response.setIntervalValue = this.setIntervalValue;
          this.$store.commit("saveNotificationState", response);
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>

<style lang="less">
@import "../assets/css/pcCommon.less";

.menu-icon-show {
  text-align: center;
  font-size: 18px;
  font-weight: 600;
  .right-icon-menu {
    color: #fff;
  }
}
.menu-show {
  font-size: 18px;
  font-weight: 600;
  text-align: right;
  margin-right: 10px;
  .left-icon-menu {
    color: #fff;
  }
}

.system-menu {
  position: fixed;
  top: 60px;
  left: 0;
  right: 0;
  width: 100%;
  text-align: center;
  padding-left: 90px;
  height: 60px;
  background: #fff;
  box-shadow: 0 1px 0 #ccc8c8;
  z-index: 300;
  .system-menu-item {
    margin-right: 35px;
    height: 60px;
    line-height: 60px;
    display: inline-block;
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
