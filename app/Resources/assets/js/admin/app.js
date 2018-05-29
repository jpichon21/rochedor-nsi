import React from 'react'
import { hot } from 'react-hot-loader'
import { HashRouter } from 'react-router-dom'
import ReactDOM from 'react-dom'
import { Route, Switch } from 'react-router'
import { connect, Provider } from 'react-redux'
import PageList from './components/page-list/page-list'
import { configureStore } from './store'

const store = configureStore({ pages: [] })

class App extends React.Component {
  render () {
    return (
      <div>
        <HashRouter>
          <div className='container'>
            <Switch>
              <Route path='/' exact component={PageList} />
            </Switch>
          </div>
        </HashRouter>
      </div>
    )
  }
}

export default connect()(hot(module)(App))

ReactDOM.render(
  <Provider store={store}>
    <App />
  </Provider>,
  document.getElementById('app')
)
