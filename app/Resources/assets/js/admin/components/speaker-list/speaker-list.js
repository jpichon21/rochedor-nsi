import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { SortableContainer, SortableElement, SortableHandle } from 'react-sortable-hoc'
import { getSpeaker, getSpeakers, setSpeakerPosition, initStatus, setLocale } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, Button, CircularProgress, Paper, Icon } from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { NavLink, Link } from 'react-router-dom'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import { locales } from '../../locales'

export class SpeakerList extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false
    }

    this.handleClose = this.handleClose.bind(this)
    this.onSortEnd = this.onSortEnd.bind(this)
  }

  componentWillMount () {
    this.props.dispatch(getSpeakers(this.props.locale))
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

  onSortEnd ({oldIndex, newIndex}) {
    this.props.dispatch(setSpeakerPosition(this.props.speakers[oldIndex].id, newIndex))
  }

  render () {
    Moment.locale(this.props.locale)
    const { classes } = this.props
    const DragHandle = SortableHandle(() => <Icon style={{'cursor': 'move'}}>sort</Icon>)
    const SortableItem = SortableElement(({speaker}) =>
      <TableRow>
        <TableCell style={{'width': '50px'}}>
          <DragHandle />
        </TableCell>
        <TableCell><NavLink className={classes.link} to={`/speaker-edit/${speaker.id}`}>{speaker.name}</NavLink></TableCell>
      </TableRow>
    )

    const SortableList = SortableContainer(({speakers}) => {
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
          <div className={classes.buttons}>
            <Button component={Link} variant='fab' color='secondary' aria-label='Ajouter' to={'/speaker-create'}>
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
