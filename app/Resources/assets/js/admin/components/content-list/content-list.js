import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getContents, initStatus, setLocale } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, CircularProgress, Paper, Typography } from '@material-ui/core'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { NavLink } from 'react-router-dom'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import { locales } from '../../locales'
import IsAuthorized, {
  ACTION_CONTENT_ASSOCIATION_VIEW,
  ACTION_CONTENT_EDITION_VIEW,
  ACTION_CONTENT_ASSOCIATION_EDIT,
  ACTION_CONTENT_EDITION_EDIT
} from '../../isauthorized/isauthorized'
import Redirect from 'react-router-dom/Redirect'
import Tab from '@material-ui/core/Tab'
import Tabs from '@material-ui/core/Tabs'

export class ContentList extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false,
      currentTabValue: 'category',
    }
    this.onLocaleChange = this.onLocaleChange.bind(this)
    this.handleClose = this.handleClose.bind(this)
    this.handleTabChange = this.handleTabChange.bind(this)
  }

  componentWillMount () {
    this.props.dispatch(getContents(this.props.locale))
  }

  componentWillReceiveProps (nextProps) {
    if ((nextProps.status !== 'ok' && nextProps.status !== '' && nextProps.status !== 'Deleted successfully' && nextProps.status !== 'Page updated') || nextProps.error) {
      this.setState({ alertOpen: true })
    }
    this.props.dispatch(initStatus())
  }

  handleClose () {
    this.props.dispatch(initStatus())
    this.setState({ alertOpen: false })
  }

  onLocaleChange (locale) {
    this.props.dispatch(setLocale(locale))
    this.props.dispatch(getContents(locale))
  }
  handleTabChange(e, tab) {
    this.setState({currentTabValue: tab})
  }

  render () {
    Moment.locale(this.props.locale)
    const { classes } = this.props
    const categories = this.props.pages.map(page => page.category).filter((value, index, self) => value && self.indexOf(value) === index).concat([''])

    const { currentTabValue } = this.state

    const renderRow = (page) => {
      if (page) {
        return (
          <TableRow key={page.id}>
            <TableCell>
              {`${page.title} ${page.sub_title}`}
              <IsAuthorized action={page.type === 'editions' ? ACTION_CONTENT_EDITION_EDIT : ACTION_CONTENT_ASSOCIATION_EDIT}>
                <NavLink className={classes.link} to={`/content-edit/${page.id}`}>
                  Modifier
                </NavLink>
              </IsAuthorized>
            </TableCell>
            <TableCell>
              {page.routes[0] && page.routes[0].static_prefix}
              {page.routes[0] && (
                <a className={classes.link} target='_blank' href={`${page.routes[0].static_prefix}`}>Aperçu</a>
              )}
            </TableCell>
            <TableCell>{Moment(page.updated).format('DD/MM/YY')}</TableCell>
          </TableRow>
        )
      } else {
        return (
          <TableRow key={Math.random()}>
            <TableCell>Page inexistante</TableCell>
            <TableCell />
            <TableCell />
          </TableRow>
        )
      }
    }
    return (
      <div>
        <IsAuthorized action={[ACTION_CONTENT_ASSOCIATION_VIEW, ACTION_CONTENT_EDITION_VIEW]} alternative={<Redirect to={'/'} />}>
          <Alert open={this.state.alertOpen} content={this.props.status} onClose={this.handleClose} />
          <AppMenu title={'Liste des contenus'} localeHandler={this.onLocaleChange} locales={locales} locale={this.props.locale} />
          <div className={classes.container}>
            <Tabs
              centered
              variant='fullWidth'
              indicatorColor='primary'
              textColor='primary'
              value={currentTabValue}
              onChange={this.handleTabChange}
            >
              <Tab label='Par catégorie' value='category' />
              <Tab label='Par ordre alphabétique' value='alpha' />
            </Tabs>
            {currentTabValue === 'category'
              ? displayByCategory(categories, renderRow, this.props.loading, classes, this.props.pages)
              : displayByAlpha(renderRow, this.props.loading, classes, this.props.pages)}
          </div>
        </IsAuthorized>
      </div>
    )
  }
}

const displayByCategory = (categories, renderRow, loading, classes, pages) => {
  return categories.map(category => (
    <div key={category}>
      <Typography variant='headline' className={classes.title}>
        {category || 'Autres'}
      </Typography>
      <Paper className={classes.list}>
        {
          loading
            ? <CircularProgress size={50} />
            : (
              <Table>
                <TableHead>
                  <TableRow>
                    <TableCell>Titre</TableCell>
                    <TableCell>URL</TableCell>
                    <TableCell>Dernière modification</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {pages.filter(page => page.category === category).map(page => renderRow(page))}
                </TableBody>
              </Table>
            )
        }
      </Paper>
    </div>
  ))
}
const displayByAlpha = (renderRow, loading, classes, pages) => {
  return (
      <Paper className={classes.list}>
        {
          loading
            ? <CircularProgress size={50} />
            : (
              <Table>
                <TableHead>
                  <TableRow>
                    <TableCell>Titre</TableCell>
                    <TableCell>URL</TableCell>
                    <TableCell>Dernière modification</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {pages.sort((page1, page2) => {
                    if(page1.title < page2.title) { return -1 }
                    if(page1.title > page2.title) { return 1 }
                    return 0
                  }).map(page => renderRow(page))}
                </TableBody>
              </Table>
            )
        }
      </Paper>
  )
}

const styles = theme => ({
  ...theme,
  link: {
    marginLeft: 10,
    textDecoration: 'none',
    color: theme.palette.secondary.main
  },
  list: {
    marginBottom: theme.myMarge
  }
})

const mapStateToProps = state => {
  return {
    pages: state.pages,
    loading: state.loading,
    status: state.status,
    error: state.error,
    locale: state.locale
  }
}

ContentList.propTypes = {
  classes: PropTypes.object.isRequired
}

export default compose(withStyles(styles), connect(mapStateToProps))(ContentList)
