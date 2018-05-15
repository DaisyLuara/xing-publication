<template>
  <div class="projects-root">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'projectsView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .projects-root {
    height: 100%;
    margin: 10px;
    background-color: #EFF2F7;
  }
</style>