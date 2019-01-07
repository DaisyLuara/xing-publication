import pcHome from 'page/home'
import system from 'router/pc/system'
import project from 'router/pc/project'
import inform from 'router/pc/inform'
import account from 'router/pc/account'
import ad from 'router/pc/ad'
import team from 'router/pc/team'
import equipment from 'router/pc/equipment'
import home from 'router/pc/home'
import report from 'router/pc/report'
import market from 'router/pc/market'

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
    project,
    market,
    inform,
    account,
    ad,
    equipment,
    team,
    report
  ],
}
