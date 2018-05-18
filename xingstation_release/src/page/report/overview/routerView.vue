<template>
  <div class="overview-root">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'overviewView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .overview-root {
    height: 100%;
    margin: 10px;
    background-color: #EFF2F7;
  }
</style>
