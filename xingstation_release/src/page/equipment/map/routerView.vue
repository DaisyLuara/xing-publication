<template>
  <div class="item-root">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'mapView',
  methods: {}
}
</script>

<style lang="less" scoped>
.item-root {
  height: 100%;
  margin: 10px;
  background-color: #eff2f7;
}
</style>
