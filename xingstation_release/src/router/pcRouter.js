import pcHome from 'page/home'
import system from 'router/pc/system'
// import main from 'router/pc/main'
import customer from 'router/pc/customer'
import project from 'router/pc/project'
import inform from 'router/pc/inform'
import account from 'router/pc/account'
// import feedback from 'router/pc/feedback'
// import help from 'router/pc/help'
import contract from 'router/pc/contract'

export default {
  path: '/',
  name: 'pc站',
  component: pcHome,
  meta: {
    permission: 'main',
  },
  children: [
    // main,
    system,
    customer,
    project,
    contract,
    inform,
    account,
    // feedback,
    // help
  ],
}
