import React, { Fragment } from 'react'
import { hot } from 'react-hot-loader'
import { HashRouter } from 'react-router-dom'
import { Route, Switch } from 'react-router'
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
import UserList from './components/user-list/user-list'
import UserEdit from './components/user-edit/user-edit'
import UserCreate from './components/user-create/user-create'
import HomeEdit from './components/home-edit/home-edit'
import HomeAdmin from './components/home-admin/home-admin'
import Login from './components/login/login'
import ForgottenPassword from './components/forgottenPassword/forgottenPassword'
import Logout from './components/logout/logout'
import PrivateRoute from './components/private-route/private-route'
import { doCheckLogin } from './actions'

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
              <PrivateRoute path='/' exact component={HomeAdmin} />
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

              <PrivateRoute path='/user-list/' exact component={UserList} />
              <PrivateRoute path='/user-edit/:userId' exact component={UserEdit} />
              <PrivateRoute path='/user-create/' exact component={UserCreate} />

              <PrivateRoute path='/logout' exact component={Logout} />
              <Route path='/login' exact component={Login} />
              <Route path='/mot-de-passe-oublie' exact component={ForgottenPassword} />
            </Switch>
          </Fragment>
        </HashRouter>
      </Fragment>
    )
  }
}

export default connect()(hot(module)(App))
