import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getNewsSet, initStatus, setLocale } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, Button, CircularProgress, Paper } from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { NavLink, Link } from 'react-router-dom'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import { locales } from '../../locales'

export class NewsList extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false
    }

    this.onLocaleChange = this.onLocaleChange.bind(this)
    this.handleClose = this.handleClose.bind(this)
  }

  componentWillMount () {
    this.props.dispatch(getNewsSet(this.props.locale))
  }

  componentWillReceiveProps (nextProps) {
    if ((nextProps.status !== 'ok' && nextProps.status !== '' && nextProps.status !== 'Deleted successfully') || nextProps.error) {
      this.setState({alertOpen: true})
    }
  }

  handleClose () {
    this.props.dispatch(initStatus())
    this.setState({alertOpen: false})
  }

  onLocaleChange (locale) {
    this.props.dispatch(setLocale(locale))
    this.props.dispatch(getNewsSet(locale))
  }

  render () {
    Moment.locale(this.props.locale)
    const { classes } = this.props
    const items = this.props.newsSet.map(news => {
      return (
        <TableRow key={news.id}>
          <TableCell><NavLink className={classes.link} to={`/news-edit/${news.id}`}>{news.intro}</NavLink></TableCell>
          <TableCell><NavLink className={classes.link} to={`/news-edit/${news.id}`}>{Moment(news.start).format('DD/MM/YY')}</NavLink></TableCell>
          <TableCell><NavLink className={classes.link} to={`/news-edit/${news.id}`}>{Moment(news.stop).format('DD/MM/YY')}</NavLink></TableCell>
        </TableRow>
      )
    })
    return (
      <div>
        <Alert open={this.state.alertOpen} content={this.props.status} onClose={this.handleClose} />
        <AppMenu title={'Actualités de la page d\'accueil'} localeHandler={this.onLocaleChange} locales={locales} locale={this.props.locale} />
        <div className={classes.container}>
          <Paper className={classes.paper}>
            {
              this.props.loading
                ? <CircularProgress size={50} />
                : (
                  <Table>
                    <TableHead>
                      <TableRow>
                        <TableCell>Introduction</TableCell>
                        <TableCell>Début</TableCell>
                        <TableCell>Fin</TableCell>
                      </TableRow>
                    </TableHead>
                    <TableBody>
                      {items}
                    </TableBody>
                  </Table>
                )
            }
          </Paper>
          <div className={classes.buttons}>
            <Button component={Link} variant='fab' color='secondary' aria-label='Ajouter' to={'/news-create'}>
              <AddIcon />
            </Button>
          </div>
        </div>
      </div>
    )
  }
}

const styles = theme => ({
  ...theme
})

const mapStateToProps = state => {
  return {
    newsSet: state.newsSet,
    loading: state.loading,
    status: state.status,
    error: state.error,
    locale: state.locale
  }
}

NewsList.propTypes = {
  classes: PropTypes.object.isRequired
}

NewsList.defaultProps = {
  locale: 'fr',
  newsSet: []
}

export default compose(withStyles(styles), connect(mapStateToProps))(NewsList)
