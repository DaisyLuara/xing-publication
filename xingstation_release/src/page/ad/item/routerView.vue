<template>
  <div 
    class="item-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'ItemView',
  data() {
    return {}
  }
}
</script>

<style lang="less" scoped>
.item-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>
