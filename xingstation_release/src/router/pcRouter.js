import pcHome from 'page/home'
import system from 'router/pc/system'
import company from 'router/pc/company'
import project from 'router/pc/project'
import inform from 'router/pc/inform'
import account from 'router/pc/account'
import ad from 'router/pc/ad'
import team from 'router/pc/team'
import equipment from 'router/pc/equipment'
// import point from 'router/pc/point'
import home from 'router/pc/home'
import report from 'router/pc/report'
import resource from 'router/pc/resource'
import setting from 'router/pc/setting'

export default {
  path: '/',
  name: 'pc站',
  component: pcHome,
  meta: {
    // permission: 'main',
  },
  children: [
    home,
    system,
    company,
    project,
    setting,
    // point,
    inform,
    account,
    ad,
    equipment,
    // resource,
    team,
    report
  ],
}
