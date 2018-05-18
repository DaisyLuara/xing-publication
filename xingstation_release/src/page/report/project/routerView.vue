<template>
  <div class="project-root">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'projectView',
  data() {
    return {}
  },
  methods: {}
}
</script>

<style lang="less" scoped>
.project-root {
  height: 100%;
  margin: 10px;
  background-color: #eff2f7;
}
</style>
