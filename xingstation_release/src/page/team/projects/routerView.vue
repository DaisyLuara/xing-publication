<template>
  <div class="projects-root">
    <router-view />
  </div>
</template>
<script>
export default {
  name: 'ProjectsView',
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