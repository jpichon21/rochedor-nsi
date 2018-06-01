import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getPages } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, Button, CircularProgress, Paper } from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { withRouter, NavLink } from 'react-router-dom'
import AppMenu from '../app-menu/app-menu'

export class PageList extends React.Component {
  constructor (props) {
    super(props)
    this.handleLocaleChange = this.handleLocaleChange.bind(this)
  }

  componentWillMount () {
    this.props.dispatch(getPages(this.props.locale))
  }

  handleLocaleChange (event) {
    this.setState({locale: event.target.value}, () => {
      this.props.dispatch(getPages(this.props.locale))
    })
  }

  goTo (path) {
    this.props.history.push(path)
  }

  render () {
    Moment.locale(this.props.locale)
    const { classes } = this.props
    const items = this.props.pages.map(page => {
      return (
        <TableRow key={page.id}>
          <TableCell><NavLink to={`/page-edit/${page.id}`}>{page.title}</NavLink></TableCell>
          <TableCell><NavLink to={`/page-edit/${page.id}`}>{Moment(page.updated).format('DD/MM/YY')}</NavLink></TableCell>
        </TableRow>
      )
    })
    return (
      <div>
        <AppMenu title={'Liste des pages'} />
        <div className={classes.container}>
          <Paper className={classes.paper}>
            {
              this.props.loading
                ? <CircularProgress size={50} />
                : (
                  <Table>
                    <TableHead>
                      <TableRow>
                        <TableCell>Intitulé</TableCell>
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
            <Button variant='fab' color='secondary' aria-label='Ajouter' onClick={() => this.goTo('/page-create')} >
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
    pages: state.pages,
    loading: state.loading
  }
}

PageList.propTypes = {
  classes: PropTypes.object.isRequired
}

export default withRouter(compose(withStyles(styles), connect(mapStateToProps))(PageList))
