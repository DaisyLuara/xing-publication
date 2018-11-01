<template>
  <div class="coupon-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'CouponView'
}
</script>

<style lang="less" scoped>
.coupon-root {
  height: 100%;
  margin: 10px;
  background-color: #eff2f7;
}
</style>