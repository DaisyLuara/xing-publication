<template>
  <div class="item-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'ItemView'
}
</script>

<style lang="less" scoped>
  .item-root {
    height: 100%;
    background-color: #EFF2F7;
  }
</style>
