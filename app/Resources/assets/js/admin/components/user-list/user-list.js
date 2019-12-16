import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getUsers, initStatus } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, Tooltip, Button, CircularProgress, Paper, Icon } from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { NavLink, Link } from 'react-router-dom'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import IsAuthorized, {
  ACTION_USER_VIEW,
  ACTION_USER_CREATE,
  ACTION_USER_EDIT,
  ROLE_ADMIN_ASSOCIATION, ROLE_SUPER_ADMIN, ROLE_ADMIN_EDITION
} from '../../isauthorized/isauthorized'
import Redirect from 'react-router-dom/Redirect'

export class UserList extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false,
      users: []
    }

    this.handleClose = this.handleClose.bind(this)
  }

  componentWillMount () {
    this.props.dispatch(getUsers()).then(res => {
      this.setState({ users: res || [] })
    })
  }

  componentWillReceiveProps (nextProps) {
    if ((nextProps.status !== 'ok' && nextProps.status !== '' && nextProps.status !== 'Deleted successfully') || nextProps.error) {
      this.setState({ alertOpen: true })
    }
  }

  handleClose () {
    this.props.dispatch(initStatus())
    this.setState({ alertOpen: false })
  }

  render () {
    Moment.locale(this.props.locale)
    const { classes } = this.props

    return (
      <div>
        <IsAuthorized action={ACTION_USER_VIEW} alternative={<Redirect to={'/'} />}>
          <Alert open={this.state.alertOpen} content={this.props.status} onClose={this.handleClose} />
          <AppMenu title={'Liste des utilisateurs'} locales={{}} />
          <div className={classes.container}>
            <Paper className={classes.paper}>
              {
                this.props.loading
                  ? <CircularProgress size={50} />
                  : (
                    <Table>
                      <TableHead>
                        <TableRow>
                          <TableCell>Nom</TableCell>
                          <TableCell>Identifiant</TableCell>
                          <TableCell>Droit(s)</TableCell>
                          <TableCell>Actif</TableCell>
                        </TableRow>
                      </TableHead>
                      <TableBody>
                        {this.state.users && this.state.users.map(user => {
                          return (
                            <TableRow key={user.id}>
                              <TableCell>
                                <IsAuthorized action={ACTION_USER_EDIT} alternative={user.name}>
                                  <NavLink className={classes.link} to={`/user-edit/${user.id}`}>{user.name}</NavLink>
                                </IsAuthorized>
                              </TableCell>
                              <TableCell>
                                <IsAuthorized action={ACTION_USER_EDIT} alternative={user.username}>
                                  <NavLink className={classes.link} to={`/user-edit/${user.id}`}>{user.username}</NavLink>
                                </IsAuthorized>
                              </TableCell>

                              <TableCell>
                                {getRoles(user).join(', ')}
                                {/* (user.roles.includes('ROLE_SUPER_ADMIN')) ? <Icon>check</Icon> : '' */}
                              </TableCell>
                              <TableCell>
                                {(user.active) ? <Icon>check</Icon> : ''}
                              </TableCell>
                            </TableRow>
                          )
                        })}
                      </TableBody>
                    </Table>
                  )
              }
            </Paper>
            <div className={classes.buttons}>
              <IsAuthorized action={ACTION_USER_CREATE}>
                <Tooltip
                  enterDelay={300}
                  id='tooltip-controlled'
                  leaveDelay={100}
                  onClose={this.handleTooltipClose}
                  onOpen={this.handleTooltipOpen}
                  open={this.state.open}
                  placement='bottom'
                  title='Ajouter un utilisateur'
                >
                  <Button component={Link} variant='fab' color='secondary' aria-label='Ajouter' to={'/user-create'}><AddIcon /></Button>
                </Tooltip>
              </IsAuthorized>
            </div>
          </div>
        </IsAuthorized>
      </div>
    )
  }
}

const getRoles = ({roles}) => {
  if (roles.includes(ROLE_SUPER_ADMIN)) {
    return ['Administrateur (tous les droits)']
  }

  const mainRoles = []
  if (roles.includes(ROLE_ADMIN_ASSOCIATION)) {
    mainRoles.push('Contenu Association')
  }
  if (roles.includes(ROLE_ADMIN_EDITION)) {
    mainRoles.push('Contenu Édition')
  }

  return mainRoles
}

const styles = theme => ({
  ...theme
})

const mapStateToProps = state => {
  return {
    loading: state.loading,
    status: state.status,
    error: state.error
  }
}

UserList.propTypes = {
  classes: PropTypes.object.isRequired
}

export default compose(withStyles(styles), connect(mapStateToProps))(UserList)
