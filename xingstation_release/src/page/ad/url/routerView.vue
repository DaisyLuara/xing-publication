<template>
  <div class="url-root">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'urlView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .url-root {
    height: 100%;
    margin: 10px;
    background-color: #EFF2F7;
  }
</style>
