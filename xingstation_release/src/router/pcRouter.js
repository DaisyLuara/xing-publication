import pcHome from 'page/home'
import system from 'router/pc/system'
// import main from 'router/pc/main'
import company from 'router/pc/company'
import project from 'router/pc/project'
import inform from 'router/pc/inform'
import account from 'router/pc/account'
import ad from 'router/pc/ad'
import equipment from 'router/pc/equipment'
// import feedback from 'router/pc/feedback'
// import help from 'router/pc/help'
// import contract from 'router/pc/contract'

export default {
  path: '/',
  name: 'pc站',
  component: pcHome,
  meta: {
    // permission: 'main',
  },
  children: [
    // main,
    system,
    company,
    project,
    equipment,
    // contract,
    inform,
    account,
    ad
    // feedback,
    // help
  ],
}
