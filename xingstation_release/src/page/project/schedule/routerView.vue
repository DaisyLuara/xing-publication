<template>
  <div class="schedule-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'ScheduleView'
}
</script>

<style lang="less" scoped>
.schedule-root {
  height: 100%;
  margin: 10px;
  background-color: #eff2f7;
}
</style>
