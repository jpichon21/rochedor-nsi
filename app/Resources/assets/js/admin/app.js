import React, { Fragment } from 'react'
import { hot } from 'react-hot-loader'
import { HashRouter } from 'react-router-dom'
import ReactDOM from 'react-dom'
import { Route, Switch } from 'react-router'
import { connect, Provider } from 'react-redux'
import PageList from './components/page-list/page-list'
import PageCreate from './components/page-create/page-create'
import PageEdit from './components/page-edit/page-edit'
import AppMenu from './components/app-menu/app-menu'
import { configureStore } from './store'
import CssBaseline from '@material-ui/core/CssBaseline'
import { MuiThemeProvider, createMuiTheme } from '@material-ui/core/styles'

const store = configureStore({ pages: [], postPageStatus: null, page: {} })

const marge = 20

const theme = createMuiTheme({
  marge: marge,
  container: {
    maxWidth: 1024,
    marginTop: marge * 2,
    marginBottom: marge * 2,
    marginLeft: 'auto',
    marginRight: 'auto'
  },
  paper: {
    paddingTop: marge,
    paddingBottom: marge
  },
  title: {
    margin: marge,
    marginTop: 0
  },
  buttons: {
    display: 'flex',
    justifyContent: 'flex-end',
    margin: marge,
    marginBottom: 0
  },
  button: {
    marginLeft: marge
  },
  options: {
    display: 'flex',
    justifyContent: 'flex-end',
    marginBottom: marge
  },
  divider: {
    marginTop: marge,
    marginBottom: marge
  },
  form: {
    marginLeft: marge,
    marginRight: marge
  },
  textfield: {
    marginBottom: marge
  }
})

class App extends React.Component {
  constructor () {
    super()
    this.state = {
      title: 'Accueil'
    }
    this.updateTitle = this.updateTitle.bind(this)
  }
  render () {
    return (
      <HashRouter>
        <Fragment>
          <AppMenu title={this.state.title} />
          <Switch>
            <Route path='/page-list' render={(props) => (<PageList title={this.updateTitle} />)} />
            <Route path='/page-create' render={(props) => (<PageCreate title={this.updateTitle} />)} />
            <Route path='/page-edit/:pageId' render={(props) => (<PageEdit title={this.updateTitle} />)} />
          </Switch>
        </Fragment>
      </HashRouter>
    )
  }
  updateTitle (title) {
    console.log('title')
    this.setState({title: title})
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
