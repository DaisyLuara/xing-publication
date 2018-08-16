<template>
  <div 
    class="root">
    <el-tabs 
      v-model="activeName"
      class="tabs" 
      @tab-click="handleClick">
      <el-tab-pane 
        label="全部" 
        name="all">
        <All 
          ref="all" 
          :active="activeName" />
      </el-tab-pane>
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
    All: () => import('./com/all')
  },
  data() {
    return {
      activeName: 'all'
    }
  },
  computed: {
    ...mapState({
      lastClickTab: state => state.appState.lastClickTab
    })
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
        searchValue: ''
      }
      this.$store.commit('appState/saveCurrentState', state)
    }
  }
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
