import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { SortableContainer, SortableElement, SortableHandle } from 'react-sortable-hoc'
import { getSpeakers, setSpeakerPosition, initStatus } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, Tooltip, Button, CircularProgress, Paper, Icon } from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { NavLink, Link } from 'react-router-dom'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import IsAuthorized, { ACTION_SPEAKER_EDIT, ACTION_SPEAKER_VIEW, ACTION_SPEAKER_CREATE } from '../../isauthorized/isauthorized'
import Redirect from 'react-router-dom/Redirect'

export class SpeakerList extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false,
      allowView: false,
      alllowEdit: false,
      allowCreate: false
    }

    this.handleClose = this.handleClose.bind(this)
    this.onSortEnd = this.onSortEnd.bind(this)
  }

  componentWillMount () {
    this.props.dispatch(getSpeakers(this.props.locale))
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

  onSortEnd ({ oldIndex, newIndex }) {
    this.props.dispatch(setSpeakerPosition(this.props.speakers[oldIndex].id, newIndex))
  }

  render () {
    Moment.locale(this.props.locale)
    const { classes } = this.props
    const DragHandle = SortableHandle(() => <Icon style={{ 'cursor': 'move' }}>sort</Icon>)
    const SortableItem = SortableElement(({ speaker }) =>
      <TableRow>
        <Tooltip
          enterDelay={300}
          id='tooltip-controlled'
          leaveDelay={100}
          onClose={this.handleTooltipClose}
          onOpen={this.handleTooltipOpen}
          open={this.state.open}
          placement='bottom'
          title='Réordonner par glisser déposer'
        >
          <TableCell style={{ 'width': '50px' }}>
            <IsAuthorized action={ACTION_SPEAKER_EDIT} alternative={<Icon>not_interested</Icon>}>
              <DragHandle />
            </IsAuthorized>
          </TableCell>
        </Tooltip>
        <TableCell>
          <IsAuthorized action={ACTION_SPEAKER_EDIT} alternative={speaker.name}>
            <NavLink className={classes.link} to={`/speaker-edit/${speaker.id}`}>{speaker.name}</NavLink>
          </IsAuthorized>
        </TableCell>
      </TableRow>
    )

    const SortableList = SortableContainer(({ speakers }) => {
      return (
        <TableBody>
          {speakers.map((speaker, index) => (
            <SortableItem key={`item-${index}`} index={index} speaker={speaker} />
          ))}
        </TableBody>
      )
    })

    return (
      <div>
        <IsAuthorized action={ACTION_SPEAKER_VIEW} alternative={<Redirect to='/' />}>
          <Alert open={this.state.alertOpen} content={this.props.status} onClose={this.handleClose} />
          <AppMenu title={'Liste des intervenants'} />
          <div className={classes.container}>
            <Paper className={classes.paper}>
              {
                this.props.loading
                  ? <CircularProgress size={50} />
                  : (
                    <Table>
                      <TableHead>
                        <TableRow>
                          <TableCell>Réordonner</TableCell>
                          <TableCell>Nom</TableCell>
                        </TableRow>
                      </TableHead>
                      <SortableList speakers={this.props.speakers} onSortEnd={this.onSortEnd} useDragHandle />
                    </Table>
                  )
              }
            </Paper>
            <IsAuthorized action={ACTION_SPEAKER_CREATE}>
              <div className={classes.buttons}>
                <Tooltip
                  enterDelay={300}
                  id='tooltip-controlled'
                  leaveDelay={100}
                  onClose={this.handleTooltipClose}
                  onOpen={this.handleTooltipOpen}
                  open={this.state.open}
                  placement='bottom'
                  title='Ajouter un intervenant'
                >
                  <Button component={Link} variant='fab' color='secondary' aria-label='Ajouter' to={'/speaker-create'}>
                    <AddIcon />
                  </Button>
                </Tooltip>
              </div>
            </IsAuthorized>
          </div>
        </IsAuthorized>
      </div>
    )
  }
}

const styles = theme => ({
  ...theme
})

const mapStateToProps = state => {
  return {
    speakers: state.speakers,
    loading: state.loading,
    status: state.status,
    error: state.error
  }
}

SpeakerList.propTypes = {
  classes: PropTypes.object.isRequired
}

SpeakerList.defaultProps = {
  locale: 'fr',
  speakers: []
}

export default compose(withStyles(styles), connect(mapStateToProps))(SpeakerList)
