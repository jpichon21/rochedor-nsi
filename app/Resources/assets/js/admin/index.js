import React from 'react'
import ReactDOM from 'react-dom'
import { Provider } from 'react-redux'
import App from './app'
import { configureStore } from './store'
import { MuiThemeProvider, createMuiTheme } from '@material-ui/core/styles'
import { CssBaseline } from '@material-ui/core'

const myMarge = 30

const store = configureStore({
  pages: [],
  locale: 'fr',
  status: '',
  page: {
    locale: 'fr',
    title: '',
    sub_title: '',
    url: '',
    description: '',
    parent_id: null,
    content: {
      intro: '',
      sections: [
        {
          title: '',
          body: '',
          slides: [
            {
              layout: '1-1-2',
              images: [
                { type: '', url: '', alt: '', video: '' },
                { type: '', url: '', alt: '', video: '' },
                { type: '', url: '', alt: '', video: '' },
                { type: '', url: '', alt: '', video: '' }
              ]
            }
          ]
        }
      ]
    }
  },
  pageVersions: {}
})

const theme = createMuiTheme({
  myMarge: myMarge,
  container: {
    maxWidth: 1024,
    padding: myMarge,
    marginLeft: 'auto',
    marginRight: 'auto'
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
  },
  link: {
    underline: 'none',
    textDecoration: 'none',
    color: 'inherit'
  }
})

ReactDOM.render(
  <Provider store={store}>
    <MuiThemeProvider theme={theme}>
      <CssBaseline />
      <App />
    </MuiThemeProvider>
  </Provider>,
  document.getElementById('app')
)
