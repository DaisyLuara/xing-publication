// import md5 from 'js-md5'

let validate = {
  account(value) {
    if (isEmpty(value)) {
      return {
        validate: false,
        errorText: '请输入账号'
      }
    }

    if (!checkMobile(value)) {
      return {
        validate: false,
        errorText: '手机号格式有误,请重新输入'
      }
    }

    return {
      validate: true
    }
  },

  password(value) {
    if (isEmpty(value)) {
      return {
        validate: false,
        errorText: '请输入密码'
      }
    }

    if (!checkPassword(value)) {
      return {
        validate: false,
        errorText: '密码长度不正确,请重新输入'
      }
    }

    return {
      validate: true
    }
  },

  imageCaptcha(value) {
    if (isEmpty(value)) {
      return {
        validate: false,
        errorText: '请输入验证码'
      }
    }

    if (isContainBlank(value)) {
      return {
        validate: false,
        errorText: '验证码不能包含空格'
      }
    }

    if (!checkLength(value, 5)) {
      return {
        validate: false,
        errorText: '请输入5位验证码'
      }
    }
    // if(!checkImageCaptcha(value, md5Val)){
    //   return {
    //     validate: false,
    //     errorText: '验证码不正确'
    //   }
    // }
    return {
      validate: true
    }
  },

  smsCaptcha(value) {
    if (isEmpty(value)) {
      return {
        validate: false,
        errorText: '短信验证码不能为空'
      }
    }

    if (!checkLength(value, 4)) {
      return {
        validate: false,
        errorText: '请输入4位验证码'
      }
    }

    if (!checkSmsCaptcha(value)) {
      return {
        validate: false,
        errorText: '短信验证码格式不正确'
      }
    }

    return {
      validate: true
    }
  }
}

function isEmpty(value) {
  let len = value.length
  if (len < 1) {
    return true
  } else {
    return false
  }
}

function isContainBlank(value) {
  if (/\s/.test(value)) {
    return true
  } else {
    return false
  }
}

function checkMobile(value) {
  if (!value.match(/^1\d{10}$/) || value.match(/^1[0-2]\d{9}$/)) {
    return false
  } else {
    return true
  }
}

function checkPassword(value) {
  let len = value.length
  if (len < 6 || len > 20) {
    return false
  } else {
    return true
  }
}

// function checkImageCaptcha(value, md5Val) {
//   console.log(md5(md5(value.toLowerCase())))
//   if (md5(md5(value.toLowerCase())) == md5Val) {
//     return true
//   } else {
//     return false
//   }
// }

function checkLength(value, len) {
  if (value.length != len) {
    return false
  } else {
    return true
  }
}

function checkSmsCaptcha(value) {
  let reg = /^[a-fA-F0-9]{4,6}$/
  if (!reg.test(value)) {
    return false
  } else {
    return true
  }
}

export default validate
