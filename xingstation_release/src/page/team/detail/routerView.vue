<template>
  <div class="detail-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'DetailView'
}
</script>

<style lang="less" scoped>
.detail-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>
