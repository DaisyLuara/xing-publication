import pcHome from 'page/home'
import system from 'router/pc/system'
import project from 'router/pc/project'
import inform from 'router/pc/inform'
import account from 'router/pc/account'
import ad from 'router/pc/ad'
import team from 'router/pc/team'
import equipment from 'router/pc/equipment'
import auth from 'router/pc/auth'
import resource from 'router/pc/resource'
import home from 'router/pc/home'
import report from 'router/pc/report'
import market from 'router/pc/market'
import activity from 'router/pc/activity'
import prize from 'router/pc/prize'
import feedback from 'router/pc/feedback'
import credit from 'router/pc/credit'
import content from 'router/pc/content'

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
    prize,
    market,
    inform,
    account,
    ad,
    feedback,
    credit,
    equipment,
    auth,
    team,
    report,
    activity,
    resource,
    content
  ]
}
