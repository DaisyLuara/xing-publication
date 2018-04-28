<template>
  <div class="second-sidebar">
    <div class="module-name">{{moduleName}}</div>
    <el-menu router :default-active="currPath">
      <el-menu-item v-for="item in items" :key="item.path" :index="getPath(item)">{{item.meta.title}}</el-menu-item>
    </el-menu>
  </div>
</template>

<script>
import { Menu, MenuItem } from 'element-ui'
import auth from 'service/auth'

export default {
  name: 'secondSidebar',
  props: ['module'],
  data() {
    return {
    }
  },
  computed: {
    currPath: function () {
      let path = this.$store.getters.currRoute.path
      let i = path.indexOf('/', 1)
      i = path.indexOf('/', i + 1)
      if (i > 0) {
        path = path.substr(0, i)
      }
      return path
    },
    route: function () {
      for (let route of this.$router.options.routes) {
        if (route.path == '/') {
          for (let m of route['children']) {
            if (m.path == this.module) {
              return m
            }
          }
        }
      }
      return {
        children: []
      }
    },
    moduleName: function () {
      return this.route.meta && this.route.meta.title + '管理'
    },
    items: function () {
      let ret = []
      for (let item of this.route.children) {
        if (auth.checkPathPermission(item)) {
          if (item.meta && item.meta.title) {
            ret.push(item)
          }
        }
      }
      return ret
    }
  },
  methods: {
    getPath(item) {
      return '/' + this.module + '/' + item.path
    }
  },
  components: {
    'el-menu': Menu,
    'el-menu-item': MenuItem
  }
}
</script>

<style lang="less">
</style>
