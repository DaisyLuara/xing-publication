<template>
  <div class="other-root">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'otherView',
  data() {
    return {}
  },
  methods: {}
}
</script>

<style lang="less" scoped>
.other-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>
