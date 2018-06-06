import React, {Fragment} from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { convertToRaw } from 'draft-js'
import immutable from 'object-path-immutable'
import draftToHtml from 'draftjs-to-html'
import ExpandMoreIcon from '@material-ui/icons/ExpandMore'
import SaveIcon from '@material-ui/icons/Save'
import { withStyles } from '@material-ui/core/styles'
import RichEditor from './RichEditor'
import { uploadFile } from '../../actions'
import {
  MenuItem,
  Menu,
  TextField,
  Button,
  Typography,
  Grid,
  ExpansionPanel,
  ExpansionPanelDetails,
  ExpansionPanelSummary,
  Icon
} from '@material-ui/core'
import moment from 'moment'

export class HomeForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      locale: this.props.locale,
      home: this.props.home,
      versionCount: 0,
      submitDisabled: true,
      anchorPopover: null,
      popoverOpened: false,
      anchorMenuLayout: null,
      menuLayoutOpened: false,
      showDeleteAlert: false,
      indexTabs: [],
      fileUploading: {
        isUploading: false
      },
      anchorVersion: null
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleInputFilter = this.handleInputFilter.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleCloseVersion = this.handleCloseVersion.bind(this)
    this.handleVersion = this.handleVersion.bind(this)
    this.handleVersionOpen = this.handleVersionOpen.bind(this)
    this.handleChangeTextArea = this.handleChangeTextArea.bind(this)
  }

  handleVersion (event, key) {
    event.preventDefault()
    if (key === null) {
      this.props.versionHandler(null)
    } else {
      this.props.versionHandler(this.props.versions[key].version)
    }
    this.setState({ versionCount: key, anchorVersion: null })
  }

  handleParent (event) {
    const parentKey = event.target.value
    this.setState((prevState) => {
      return {
        ...prevState,
        home: {
          ...prevState.home,
          parent_id: this.props.parents[parentKey].id
        },
        parentKey: parentKey
      }
    })
  }

  handleInputChange (event) {
    const state = immutable.set(this.state, event.target.name, event.target.value)
    this.setState(state)
  }

  handleChangeTextArea (editorState, indexSection) {
    const rawContentState = convertToRaw(editorState.getCurrentContent())
    const html = draftToHtml(rawContentState)
    const state = immutable.set(this.state, `home.content.sections.${indexSection}.body`, html)
    this.setState(state)
  }

  handleSubmit (event) {
    event.preventDefault()
    this.props.submitHandler(this.state.home)
  }

  handleInputFilter (event) {
    const re = /[0-9A-Za-z-]+/g
    if (!re.test(event.key)) {
      event.preventDefault()
    }
  }

  handleCloseVersion () {
    this.setState({anchorVersion: null})
  }

  handleVersionOpen (event) {
    this.setState({anchorVersion: event.currentTarget})
  }

  isSubmitEnabled () {
    const p = this.state.home
    if (p.title === '' || p.description === '' || p.url === '') {
      return false
    }
    if (p.locale !== 'fr' && !p.parent_id) {
      return false
    }
    if (p.locale === 'fr' && p.parent_id) {
      return false
    }
    return true
  }

  handleChangeFileUpload (event, indexSection, indexSlide, indexImage) {
    this.props.dispatch(uploadFile(event.target.files[0]))
    this.setState({
      fileUploading: {
        isUploading: true,
        indexSection: indexSection,
        indexSlide: indexSlide,
        indexImage: indexImage
      }
    })
  }

  componentWillReceiveProps (nextProps) {
    if (nextProps.home) {
      this.setState({home: nextProps.home})
    }
    if (nextProps.locale) {
      this.setState((prevState) => {
        return {
          home: {
            ...prevState.home,
            locale: nextProps.locale,
            parent_id: null
          }
        }
      })
    }
  }

  render () {
    const { classes } = this.props
    const versions = this.props.versions
    return (
      <div className={classes.container}>
        <Typography variant='display1' className={classes.title}>
          SEO
        </Typography>
        <form className={classes.form}>
          <TextField
            required
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            name='home.title'
            label='Titre ligne 1'
            value={this.state.home.title}
            onChange={this.handleInputChange} />
          <TextField
            required
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            name='home.sub_title'
            label='Titre ligne 2'
            value={this.state.home.sub_title}
            onChange={this.handleInputChange} />
          <TextField
            required
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            name='home.url'
            label='Url'
            value={this.state.home.url}
            onChange={this.handleInputChange}
            onKeyPress={this.handleInputFilter} />
          <TextField
            required
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            multiline
            name='home.description'
            label='Meta-description'
            value={this.state.home.description}
            onChange={this.handleInputChange} />
        </form>
        <Typography variant='display1' className={classes.title}>
          Contenu
        </Typography>
        <form className={classes.form}>
          <TextField
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            multiline
            name='home.content.intro'
            label='Introduction'
            value={this.state.home.content.intro}
            onChange={this.handleInputChange} />
          {
            this.state.home.content.sections.map((section, indexSection) => (
              <ExpansionPanel key={indexSection} className={classes.expansion} expanded>
                <ExpansionPanelSummary expandIcon={<ExpandMoreIcon />}>
                  <Typography>
                    {
                      section.title === ''
                        ? 'Volet ' + (indexSection + 1)
                        : section.title
                    }
                  </Typography>
                </ExpansionPanelSummary>
                <ExpansionPanelDetails className={classes.details}>
                  <Grid container spacing={32}>
                    <Grid item xs={6}>
                      <TextField
                        required
                        autoComplete='off'
                        InputLabelProps={{ shrink: true }}
                        className={classes.textfield}
                        fullWidth
                        multiline
                        name={`home.content.sections.${indexSection}.title`}
                        label='Titre'
                        value={section.title}
                        onChange={this.handleInputChange} />
                      <RichEditor
                        indexSection={indexSection}
                        onChange={this.handleChangeTextArea} />
                    </Grid>
                  </Grid>
                </ExpansionPanelDetails>
              </ExpansionPanel>
            ))
          }
        </form>
        <div className={classes.buttons}>
          {
            <Fragment>
              <Button
                className={classes.button}
                variant='fab'
                color='primary'
                aria-label='More'
                aria-owns={this.state.anchorVersion ? 'long-menu' : null}
                aria-haspopup='true'
                onClick={this.handleVersionOpen}
              >
                <Icon>history</Icon>
              </Button>
              <Menu
                id='long-menu'
                anchorEl={this.state.anchorVersion}
                open={Boolean(this.state.anchorVersion)}
                onClose={this.handleCloseVersion}
                PaperProps={{
                  style: {
                    maxHeight: 40 * 4.5,
                    width: 200
                  }
                }}
              >
                <MenuItem key={null} selected={this.state.versionCount === null} onClick={event => this.handleVersion(event, null)}>Courante</MenuItem>
                {Object.keys(versions).map((key) => (
                  <MenuItem key={key} selected={key === this.state.versionCount} onClick={event => this.handleVersion(event, key)}>
                    {moment(versions[key].logged_at).format('DD/MM/YYYY HH:mm:ss')}
                  </MenuItem>
                ))}
                }
              </Menu>
            </Fragment>

          }
          <Button
            disabled={!this.isSubmitEnabled()}
            onClick={this.handleSubmit}
            className={classes.button}
            variant='fab'
            color='secondary'>
            <SaveIcon />
          </Button>
        </div>
      </div>
    )
  }
}

const styles = theme => ({
  ...theme,
  paper: {
    padding: theme.myMarge
  },
  options: {
    marginTop: theme.myMarge,
    textAlign: 'center'
  },
  option: {
    marginRight: theme.myMarge / 3,
    marginLeft: theme.myMarge / 3
  },
  expansion: {
    marginBottom: theme.myMarge
  },
  gridList: {
    paddingTop: theme.myMarge
  },
  tile: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    width: '100%',
    height: '100%',
    backgroundSize: 'cover',
    backgroundPosition: 'center',
    backgroundColor: '#eeeeee'
  },
  inputfile: {
    position: 'absolute',
    width: '100%',
    height: '100%',
    cursor: 'pointer',
    opacity: 0
  },
  popover: {
    margin: theme.myMarge
  }
})

const mapStateToProps = state => {
  return {
    status: state.postHomeStatus,
    versions: state.homeVersions,
    version: state.homeVersion,
    locale: state.locale,
    uploadStatus: state.uploadStatus
  }
}

HomeForm.propTypes = {
  classes: PropTypes.object.isRequired
}

HomeForm.defaultProps = {
  parents: {},
  parentKey: 0,
  versions: [],
  home: {
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
          body: ''
        }
      ]
    }
  }
}

export default compose(withStyles(styles), connect(mapStateToProps))(HomeForm)
