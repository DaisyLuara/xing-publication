<template>
  <account-form 
    :type="'login'"/>
</template>
<script>
import accountForm from '../components/common/accountForm'
import auth from 'service/auth'
export default {
  name: 'Login',
  components: {
    accountForm
  }
}
</script>
<style lang="less">
@import '../assets/css/accountForm.less';
</style>
