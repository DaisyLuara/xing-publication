<template>
  <div class="schedule-root">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'scheduleView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .schedule-root {
    height: 100%;
    margin: 10px;
    background-color: #EFF2F7;
  }
</style>
