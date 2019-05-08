<template>
  <div class="activity-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'ActivityView'
}
</script>

<style lang="less" scoped>
.activity-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>
