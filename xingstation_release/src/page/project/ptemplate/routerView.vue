<template>
  <div class="template-root">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'templateView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .template-root {
    height: 100%;
    margin: 10px;
    background-color: #EFF2F7;
  }
</style>
