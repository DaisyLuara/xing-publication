<template>
  <div class="point-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'PointView'
}
</script>

<style lang="less" scoped>
.point-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>