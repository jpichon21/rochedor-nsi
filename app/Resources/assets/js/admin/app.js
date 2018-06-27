import React, { Fragment } from 'react'
import { hot } from 'react-hot-loader'
import { HashRouter } from 'react-router-dom'
import { Route, Switch, Redirect } from 'react-router'
import { connect } from 'react-redux'
import PageList from './components/page-list/page-list'
import PageCreate from './components/page-create/page-create'
import PageEdit from './components/page-edit/page-edit'
import NewsList from './components/news-list/news-list'
import NewsCreate from './components/news-create/news-create'
import NewsEdit from './components/news-edit/news-edit'
import SpeakerList from './components/speaker-list/speaker-list'
import SpeakerEdit from './components/speaker-edit/speaker-edit'
import SpeakerCreate from './components/speaker-create/speaker-create'
import ContentList from './components/content-list/content-list'
import ContentEdit from './components/content-edit/content-edit'
import HomeEdit from './components/home-edit/home-edit'
import Login from './components/login/login'
import Logout from './components/logout/logout'
import PrivateRoute from './components/private-route/private-route'
import { doCheckLogin } from './actions'

const RedirectPageList = () => {
  return <Redirect to='/content-list' />
}

export class App extends React.Component {
  constructor () {
    super()

    this.state = {
      locale: 'fr'
    }
  }
  componentWillMount () {
    this.props.dispatch(doCheckLogin())
  }
  render () {
    return (
      <Fragment>
        <HashRouter>
          <Fragment>
            <Switch>
              <Route path='/' exact render={RedirectPageList} />
              <PrivateRoute path='/page-list' exact component={PageList} />
              <PrivateRoute path='/page-create' exact component={PageCreate} />
              <PrivateRoute path='/page-edit/:pageId' exact component={PageEdit} />
              <PrivateRoute path='/news-list/' exact component={NewsList} />
              <PrivateRoute path='/news-create/' exact component={NewsCreate} />
              <PrivateRoute path='/news-edit/:newsId' exact component={NewsEdit} />
              <PrivateRoute path='/speaker-list/' exact component={SpeakerList} />
              <PrivateRoute path='/speaker-edit/:speakerId' exact component={SpeakerEdit} />
              <PrivateRoute path='/speaker-create/' exact component={SpeakerCreate} />
              <PrivateRoute path='/content-list/' exact component={ContentList} />
              <PrivateRoute path='/content-edit/:pageId' exact component={ContentEdit} />
              <PrivateRoute path='/home-edit/' exact component={HomeEdit} />
              <PrivateRoute path='/logout' exact component={Logout} />
              <Route path='/login' exact component={Login} />
            </Switch>
          </Fragment>
        </HashRouter>
      </Fragment>
    )
  }
}

export default connect()(hot(module)(App))
