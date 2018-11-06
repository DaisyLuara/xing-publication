<template>
  <div class="list-root">
    <router-view />
  </div>
</template>
<script>
export default {
  name: 'ListView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .list-root {
    height: 100%;
    background-color: #EFF2F7;
  }
</style>