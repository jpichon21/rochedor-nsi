import React, { Fragment } from 'react'
import { hot } from 'react-hot-loader'
import { HashRouter } from 'react-router-dom'
import ReactDOM from 'react-dom'
import { Route, Switch, Redirect } from 'react-router'
import { connect, Provider } from 'react-redux'
import PageList from './components/page-list/page-list'
import PageCreate from './components/page-create/page-create'
import PageEdit from './components/page-edit/page-edit'
import { configureStore } from './store'
import CssBaseline from '@material-ui/core/CssBaseline'
import { MuiThemeProvider, createMuiTheme } from '@material-ui/core/styles'

const store = configureStore({
  pages: [],
  status: '',
  page: {
    title: '',
    sub_title: '',
    url: '',
    description: '',
    content: {}
  },
  pageVersions: {},
  title: 'Accueil' }
)

const myMarge = 30

const theme = createMuiTheme({
  myMarge: myMarge,
  container: {
    padding: myMarge
  },
  buttons: {
    display: 'flex',
    justifyContent: 'flex-end',
    position: 'fixed',
    right: myMarge,
    bottom: myMarge
  },
  button: {
    marginLeft: myMarge / 2
  },
  textfield: {
    marginBottom: myMarge
  },
  title: {
    marginBottom: myMarge,
    textTransform: 'uppercase'
  },
  form: {
    marginBottom: myMarge / 2
  }
})

const RedirectPageList = () => {
  return <Redirect to='/page-list' />
}

class App extends React.Component {
  constructor () {
    super()
    this.state = {
      locale: 'fr'
    }
  }
  render () {
    return (
      <HashRouter>
        <Fragment>
          <Switch>
            <Route path='/' exact render={RedirectPageList} />
            <Route path='/page-list' exact component={PageList} />
            <Route path='/page-create' exact component={PageCreate} />
            <Route path='/page-edit/:pageId' exact component={PageEdit} />
          </Switch>
        </Fragment>
      </HashRouter>
    )
  }
}

export default connect()(hot(module)(App))

ReactDOM.render(
  <Provider store={store}>
    <MuiThemeProvider theme={theme}>
      <CssBaseline>
        <App />
      </CssBaseline>
    </MuiThemeProvider>
  </Provider>,
  document.getElementById('app')
)
