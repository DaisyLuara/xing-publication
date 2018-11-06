<template>
  <div 
    class="url-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'UrlView',
  data() {
    return {}
  }
}
</script>

<style lang="less" scoped>
.url-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>
