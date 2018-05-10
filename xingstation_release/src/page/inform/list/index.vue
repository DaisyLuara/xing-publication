<template>
  <div class="root">
    <el-tabs class="tabs" v-model="activeName" @tab-click="handleClick">
      <el-tab-pane label="全部" name="all">
        <All ref="all" :active="activeName" />
      </el-tab-pane>
      <!-- <el-tab-pane label="系统" name="system">
        <System ref="system" :active="activeName" />
      </el-tab-pane>
      <el-tab-pane label="审核" name="audit">
        <Audit :active="activeName" />
      </el-tab-pane>
      <el-tab-pane label="节目" name="program">
        <Program :active="activeName" />
      </el-tab-pane>
      <el-tab-pane label="账户" name="account">
        <Account :active="activeName" />
      </el-tab-pane> -->
    </el-tabs>
  </div>  
</template>

<script>
import { Tabs, TabPane, Button } from 'element-ui'
import { mapState } from 'vuex'
export default {
  components: {
    ElTabs: Tabs,
    ElTabPane: TabPane,
    ElButton: Button,
    All: () => import('./com/all'),
    // Audit: resolve => require(['./com/audit'], resolve),
    // System:  resolve => require(['./com/system'], resolve),
    // Account:  resolve => require(['./com/account'], resolve),
    // Program:  resolve => require(['./com/program'], resolve),
  },
  computed: {
    ...mapState({
      lastClickTab: state => state.appState.lastClickTab,
    }),
  },
  data() {
    return {
      activeName: 'all',
    }
  },
  mounted() {
    this.activeName =
      this.lastClickTab !== '' ? this.lastClickTab : this.activeName
  },
  methods: {
    handleClick(tab, event) {
      this.activeName = tab.name
      let state = {
        page: 1,
        tab: tab.name,
        searchValue: '',
      }
      this.$store.commit('appState/saveCurrentState', state)
    },
  },
}
</script>

<style lang="less" scoped>
.root {
  background-color: white;
  margin: 10px;
  border-radius: 5px;
  .buttons {
    padding: 15px 15px 0 15px;
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
  }
  .tabs {
    margin: 10px;
    border-radius: 5px;
    padding: 0 10px;
  }
}
</style>
