<template>
  <div
    class="point-data-wrapper" >
    <el-tabs 
      v-model="activeName" 
      type="card"
      @tab-click="handleTab">
      <el-tab-pane label="按人次计" name="first">
        <PersonTimes ref="personTimes"/>
      </el-tab-pane>
      <el-tab-pane label="按人数计" name="second">
        <PeopleNum ref="peopleCount"/>
      </el-tab-pane>
    </el-tabs>
   
  </div>
</template>
<script>
import PersonTimes from './com/person_times'
import PeopleNum from './com/people_num'
import { Tabs, TabPane } from 'element-ui'

export default {
  components: {
    'el-tabs': Tabs,
    'el-tab-pane': TabPane,
    PersonTimes,
    PeopleNum
  },
  data() {
    return {
      activeName: 'first'
    }
  },
  mounted() {},
  created() {},
  methods: {
    handleTab(tab, event) {
      if (tab.name === 'first') {
        this.$refs.personTimes.handleChange()
      } else {
        this.$refs.peopleCount.handleChange()
      }
    }
  }
}
</script>
<style lang="less" scoped>
.point-data-wrapper {
  background: #fff;
}
</style>

