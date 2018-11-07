<template>
  <div class="site-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'SiteView'
}
</script>

<style lang="less" scoped>
.site-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>