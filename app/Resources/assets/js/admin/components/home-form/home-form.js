import React, { Fragment } from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { Editor } from 'react-draft-wysiwyg'
import immutable from 'object-path-immutable'
import draftToHtml from 'draftjs-to-html'
import htmlToDraft from 'html-to-draftjs'
import SaveIcon from '@material-ui/icons/Save'
import { withStyles } from '@material-ui/core/styles'
import { uploadFile } from '../../actions'
import moment from 'moment'
import {
  EditorState,
  convertToRaw,
  ContentState } from 'draft-js'
import {
  MenuItem,
  Menu,
  TextField,
  Button,
  Typography,
  Grid,
  Tooltip,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogContentText,
  DialogActions,
  ExpansionPanel,
  ExpansionPanelDetails,
  ExpansionPanelSummary,
  Icon
} from '@material-ui/core'
import IsAuthorized, { ACTION_HOME_EDIT } from '../../isauthorized/isauthorized'

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
      anchorVersion: null,
      noticeBlockquote: true,
      AlertBlockquoteOpen: false
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleInputFilter = this.handleInputFilter.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleCloseVersion = this.handleCloseVersion.bind(this)
    this.handleVersion = this.handleVersion.bind(this)
    this.handleVersionOpen = this.handleVersionOpen.bind(this)
    this.handleChangeTextArea = this.handleChangeTextArea.bind(this)
    this.handleInit = this.handleInit.bind(this)
    this.handleConvertFromHTML = this.handleConvertFromHTML.bind(this)
    this.handleOpenAlertBlockquote = this.handleOpenAlertBlockquote.bind(this)
    this.handleCloseAlertBlockquote = this.handleCloseAlertBlockquote.bind(this)
  }

  handleInit (props) {
    const state = this.handleConvertFromHTML(props)
    this.setState(state)
  }

  handleConvertFromHTML (state) {
    let sections = state.home.content.sections.map((section) => {
      if (typeof section.body === 'string') {
        const blocksFromHTML = htmlToDraft(section.body)
        let content
        if (blocksFromHTML.contentBlocks) {
          content = ContentState.createFromBlockArray(
            blocksFromHTML.contentBlocks,
            blocksFromHTML.entityMap
          )
        } else {
          content = ContentState.createFromText('')
        }
        section.body = EditorState.createWithContent(content)
      }
      return section
    })
    return immutable.set(state, 'home.content.sections', sections)
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
    this.setState(immutable.set(this.state, `home.content.sections.${indexSection}.body`, editorState))
  }

  handleSubmit (event) {
    event.preventDefault()
    let home = this.state.home
    Object.keys(home.content.sections).map((key) => {
      if (home.content.sections[key].body) {
        home = immutable.set(home, `content.sections.${key}.body`, draftToHtml(convertToRaw(home.content.sections[key].body.getCurrentContent())))
      }
    })
    this.props.submitHandler(home)
  }

  handleInputFilter (event) {
    const re = /[0-9A-Za-z-]+/g
    if (!re.test(event.key)) {
      event.preventDefault()
    }
  }

  handleCloseVersion () {
    this.setState({ anchorVersion: null })
  }

  handleVersionOpen (event) {
    this.setState({ anchorVersion: event.currentTarget })
  }

  isSubmitEnabled () {
    const p = this.state.home
    if (p.title === '' || p.description === '') {
      return false
    }
    return true
  }

  handleChangeFileUpload (event, indexSection, indexSlide, indexImage) {
    this.setState({
      fileUploading: {
        isUploading: true,
        indexSection: indexSection,
        indexSlide: indexSlide,
        indexImage: indexImage
      }
    })
    this.props.dispatch(uploadFile(event.target.files[0])).then((res) => {
      this.setState({
        fileUploading: {
          isUploading: false
        }
      })
    })
  }

  componentWillMount () {
    this.handleInit(this.props)
  }

  componentWillReceiveProps (nextProps) {
    if (nextProps.home) {
      if (JSON.stringify(nextProps.home) !== JSON.stringify(this.props.home)) {
        this.handleInit(nextProps)
      }
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

  handleOpenAlertBlockquote () {
    this.setState({ AlertBlockquoteOpen: true })
  }

  handleCloseAlertBlockquote () {
    this.setState({ AlertBlockquoteOpen: false })
  }

  render () {
    document.addEventListener('click', event => {
      const element = event.target
      if (
        element &&
        element.classList.contains('rdw-dropdownoption-default') &&
        element.textContent === 'Citation' &&
        this.state.noticeBlockquote
      ) {
        this.setState({ noticeBlockquote: false })
        this.handleOpenAlertBlockquote()
      }
    })
    const { classes } = this.props
    const versions = this.props.versions
    return (
      <div className={classes.container}>
        <Dialog
          open={this.state.AlertBlockquoteOpen}
          onClose={this.handleCloseAlertBlockquote}>
          <DialogTitle>Citation</DialogTitle>
          <DialogContent>
            <DialogContentText>
              <p>La citation doit être renseignée en <em>italique</em><br />L'auteur doit être renseigné en <strong>gras</strong></p>
              <p>Exemple :<br /><em>La foi, c'est une confiance, la gratuité d'une amitié.</em> <strong>Florin Callerand</strong></p>
            </DialogContentText>
          </DialogContent>
          <DialogActions>
            <Button onClick={this.handleCloseAlertBlockquote} color='primary' autoFocus>J'ai compris !</Button>
          </DialogActions>
        </Dialog>
        <Typography variant='display1' className={classes.title}>
          SEO
        </Typography>
        <form className={classes.form}>
          <Tooltip
            enterDelay={300}
            id='tooltip-controlled'
            leaveDelay={300}
            placement='bottom'
            title="Renseigner le titre 1 de la page d'accueil"
          >
            <TextField
              required
              autoComplete='off'
              InputLabelProps={{ shrink: true }}
              className={classes.textfield}
              fullWidth
              name='home.title'
              label='Titre'
              value={this.state.home.title}
              onChange={this.handleInputChange} />
          </Tooltip>
          <Tooltip
            enterDelay={300}
            id='tooltip-controlled'
            leaveDelay={300}
            placement='bottom'
            title="Renseigner le titre 2 de la page d'accueil"
          >
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
          </Tooltip>
        </form>
        <Typography variant='display1' className={classes.title}>
          Contenu
        </Typography>
        <form className={classes.form}>
          {
            this.state.home.content.sections.map((section, indexSection) => (
              <ExpansionPanel key={indexSection} className={classes.expansion} expanded>
                <ExpansionPanelSummary>
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
                    <Grid item xs={12}>
                      <Tooltip
                        enterDelay={300}
                        id='tooltip-controlled'
                        leaveDelay={300}
                        placement='bottom'
                        title='Renseigner le titre 1 du volet'
                      >
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
                      </Tooltip>
                      <Tooltip
                        enterDelay={300}
                        id='tooltip-controlled'
                        leaveDelay={300}
                        placement='bottom'
                        title='Renseigner le titre 2 du volet'
                      >
                        <TextField
                          autoComplete='off'
                          InputLabelProps={{ shrink: true }}
                          className={classes.textfield}
                          fullWidth
                          multiline
                          name={`home.content.sections.${indexSection}.sub_title`}
                          label='Titre ligne 2'
                          value={section.sub_title}
                          onChange={this.handleInputChange} />
                      </Tooltip>
                      <Editor
                        stripPastedStyles
                        spellCheck
                        localization={{
                          locale: 'fr',
                          translations: {
                            'components.controls.link.linkTarget': 'Lien (URL)'
                          }
                        }}
                        editorState={this.state.home.content.sections[indexSection].body}
                        onEditorStateChange={editorState => this.handleChangeTextArea(editorState, indexSection)}
                        toolbar={{
                          options: ['inline', 'blockType', 'textAlign', 'link'],
                          inline: {
                            inDropdown: true,
                            options: ['bold', 'italic', 'underline']
                          },
                          blockType: {
                            inDropdown: true,
                            options: ['Normal', 'Blockquote']
                          },
                          textAlign: {
                            inDropdown: true,
                            options: ['left', 'center', 'right', 'justify']
                          },
                          link: {
                            inDropdown: true,
                            options: ['link', 'unlink']
                          }
                        }} />
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
              <Tooltip
                enterDelay={300}
                id='tooltip-controlled'
                leaveDelay={300}
                onClose={this.handleTooltipClose}
                onOpen={this.handleTooltipOpen}
                open={this.state.open}
                placement='bottom'
                title='Revenir à une version antérieur'
              >
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
              </Tooltip>
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
          <IsAuthorized action={ACTION_HOME_EDIT}>
            <Tooltip
              enterDelay={300}
              id='tooltip-controlled'
              leaveDelay={300}
              onClose={this.handleTooltipClose}
              onOpen={this.handleTooltipOpen}
              open={this.state.open}
              placement='bottom'
              title='Publier'
            >
              <Button
                disabled={!this.isSubmitEnabled()}
                onClick={this.handleSubmit}
                className={classes.button}
                variant='fab'
                text='Publier'
                color='primary'>
                <SaveIcon />
              </Button>
            </Tooltip>
          </IsAuthorized>
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
    description: '',
    parent_id: null,
    content: {
      intro: '',
      sections: [
        {
          title: '',
          sub_title: '',
          body: ''
        }
      ]
    }
  }
}

export default compose(withStyles(styles), connect(mapStateToProps))(HomeForm)
