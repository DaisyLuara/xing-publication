<template>
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
  },
  components: {
    accountForm
  }
}
</script>
<style lang="less">
@import '../assets/css/accountForm.less';
</style>
