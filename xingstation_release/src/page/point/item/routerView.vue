<template>
  <div class="item-root">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'itemView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .item-root {
    height: 100%;
    margin: 10px;
    background-color: #EFF2F7;
  }
</style>
