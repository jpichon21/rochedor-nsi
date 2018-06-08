import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getPages, initStatus, setLocale } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, Button, CircularProgress, Paper } from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { NavLink, Link } from 'react-router-dom'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import { locales } from '../../locales'

export class PageList extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false
    }
    this.onLocaleChange = this.onLocaleChange.bind(this)
    this.handleClose = this.handleClose.bind(this)
  }

  componentWillMount () {
    this.props.dispatch(getPages(this.props.locale))
  }

  componentWillReceiveProps (nextProps) {
    if ((nextProps.status !== 'ok' && nextProps.status !== '' && nextProps.status !== 'Deleted successfully' && nextProps.status !== 'Page updated') || nextProps.error) {
      this.setState({alertOpen: true})
    }
    this.props.dispatch(initStatus())
  }

  handleClose () {
    this.props.dispatch(initStatus())
    this.setState({alertOpen: false})
  }

  onLocaleChange (locale) {
    this.props.dispatch(setLocale(locale))
    this.props.dispatch(getPages(locale))
  }

  render () {
    Moment.locale(this.props.locale)
    const { classes } = this.props
    const items = this.props.pages.map(page => {
      return (
        <TableRow key={page.id}>
          <TableCell>{`${page.title} ${page.sub_title}`}<NavLink className={classes.link} to={`/page-edit/${page.id}`}>Modifier</NavLink></TableCell>
          <TableCell>{page.routes[0].static_prefix}<a className={classes.link} target='_blank' href={`${page.routes[0].static_prefix}`}>Ouvrir</a></TableCell>
          <TableCell>{Moment(page.updated).format('DD/MM/YY')}</TableCell>
        </TableRow>
      )
    })
    return (
      <div>
        <Alert open={this.state.alertOpen} content={this.props.status} onClose={this.handleClose} />
        <AppMenu title={'Liste des pages'} localeHandler={this.onLocaleChange} locales={locales} locale={this.props.locale} />
        <div className={classes.container}>
          <Paper className={classes.paper}>
            {
              this.props.loading
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
                      {items}
                    </TableBody>
                  </Table>
                )
            }
          </Paper>
          <div className={classes.buttons}>
            <Button component={Link} variant='fab' color='secondary' aria-label='Ajouter' to={'/page-create'}>
              <AddIcon />
            </Button>
          </div>
        </div>
      </div>
    )
  }
}

const styles = theme => ({
  ...theme,
  link: {
    marginLeft: 10,
    textDecoration: 'none',
    color: theme.palette.secondary.main
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

PageList.propTypes = {
  classes: PropTypes.object.isRequired
}

export default compose(withStyles(styles), connect(mapStateToProps))(PageList)
