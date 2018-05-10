<template>
  <!-- <account-form :type="'login'" :facility="checkFacility"></account-form> -->
  <account-form :type="'login'"></account-form>
</template>
<script>
import accountForm from '../components/common/accountForm'
import auth from 'service/auth'
export default {
  name: 'login',
  created(){
  },
  methods: {
  },
  computed:{
    // checkFacility: function() {
    //   if (/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))) {
    //     if (window.location.href.indexOf("?mobile") < 0) {
    //       if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
    //         return false;
    //       } else {
    //         return true;
    //       }
    //     }
    //   }
    // }
  },
  components: {
    accountForm
  }
}
</script>
<style lang="less">
@import '../assets/css/accountForm.less';
</style>
