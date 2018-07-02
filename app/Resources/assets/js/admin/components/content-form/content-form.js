import React, { Fragment } from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { Editor } from 'react-draft-wysiwyg'
import immutable from 'object-path-immutable'
import draftToHtml from 'draftjs-to-html'
import { SortableContainer, SortableElement, arrayMove, SortableHandle } from 'react-sortable-hoc'
import ExpandMoreIcon from '@material-ui/icons/ExpandMore'
import SaveIcon from '@material-ui/icons/Save'
import OnDemandVideoIcon from '@material-ui/icons/OndemandVideo'
import PhotoSizeSelectActualIcon from '@material-ui/icons/PhotoSizeSelectActual'
import { withStyles } from '@material-ui/core/styles'
import { tileData } from './tileData'
import CustomOption from './CustomOption'
import { uploadFile } from '../../actions'
import moment from 'moment'
import {
  Entity,
  RichUtils,
  EditorState,
  convertToRaw,
  convertFromHTML,
  ContentState
} from 'draft-js'
import {
  Tab,
  Tabs,
  MenuItem,
  Menu,
  GridList,
  GridListTile,
  TextField,
  Button,
  Typography,
  Grid,
  ExpansionPanel,
  ExpansionPanelDetails,
  ExpansionPanelSummary,
  ExpansionPanelActions,
  Divider,
  Popover,
  IconButton,
  CircularProgress,
  Tooltip,
  Icon
} from '@material-ui/core'

const DragHandle = SortableHandle(() => <Icon style={{ 'cursor': 'move' }}>sort</Icon>)
const SortableItem = SortableElement(({ section, indexSection, state, classes, context }) =>
  <ExpansionPanel key={indexSection} className={classes.expansion}>
    <ExpansionPanelSummary expandIcon={<ExpandMoreIcon />}>
      <DragHandle />
      &nbsp; &nbsp; &nbsp;
      <Typography className={classes.heading}>
        {
          'Volet n°' + (indexSection + 1)
        }
      </Typography>
      <Typography className={classes.secondaryHeading}>
        {
          section.title === ''
            ? 'Sans titre'
            : section.title
        }
      </Typography>
    </ExpansionPanelSummary>
    <ExpansionPanelDetails className={classes.details}>
      <Grid container spacing={32}>
        <Grid item xs={6}>
          <Tooltip
            enterDelay={300}
            id='tooltip-controlled'
            leaveDelay={300}
            placement='bottom'
            title='Renseigner le titre du volet'
          >
            <TextField
              required
              autoComplete='off'
              InputLabelProps={{ shrink: true }}
              className={classes.textfield}
              fullWidth
              multiline
              name={`page.content.sections.${indexSection}.title`}
              label='Titre'
              value={section.title}
              onChange={context.handleInputChange} />
          </Tooltip>
          <Editor
            stripPastedStyles
            spellCheck
            localization={{locale: 'fr'}}
            editorState={context.state.page.content.sections[indexSection].bodyRaw}
            onEditorStateChange={editorState => context.handleChangeTextArea(editorState, indexSection)}
            toolbarCustomButtons={[<CustomOption addDocument={event => { context.handleChangeDocumentUpload(event, indexSection) }} />]}
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
        <Grid item xs={6}>
          <Tabs
            value={context.state.indexTabs[indexSection]}
            onChange={(event, value) => context.handleChangeTabs(value, indexSection)}
            scrollable
            scrollButtons='auto'
            indicatorColor='primary'
            textColor='primary'>
            {
              section.slides.map((slide, indexSlide) => (
                <Tab key={indexSlide} label={`Assemblage ${indexSlide + 1}`} />
              ))
            }
          </Tabs>
          {
            section.slides.map((slide, indexSlide) => (
              <div key={indexSlide}>
                {
                  context.state.indexTabs[indexSection] === indexSlide &&
                  <GridList className={classes.gridList} cols={2} rows={2}>
                    {
                      tileData[slide.layout].map((tile, indexImage) => (
                        <GridListTile key={tile.id} cols={tile.cols} rows={tile.rows}>
                          <div className={classes.tile} style={{ backgroundImage: `url('${slide.images[tile.id].url}')` }}>
                            {
                              context.state.fileUploading.isUploading &&
                                context.state.fileUploading.type === 'image' &&
                                context.state.fileUploading.indexSection === indexSection &&
                                context.state.fileUploading.indexSlide === indexSlide &&
                                context.state.fileUploading.indexImage === indexImage
                                ? <CircularProgress />
                                : (
                                  <div>
                                    <Tooltip
                                      enterDelay={300}
                                      id='tooltip-controlled'
                                      leaveDelay={300}
                                      placement='bottom'
                                      title='Sélectionner une image'
                                    >
                                      <IconButton
                                        color={slide.images[tile.id].url === '' ? 'primary' : 'secondary'}>
                                        <PhotoSizeSelectActualIcon />
                                        <input
                                          type='file'
                                          className={classes.inputfile}
                                          onChange={event => { context.handleChangeImageUpload(event, indexSection, indexSlide, indexImage) }} />
                                      </IconButton>
                                    </Tooltip>
                                    <Tooltip
                                      enterDelay={300}
                                      id='tooltip-controlled'
                                      leaveDelay={300}
                                      placement='bottom'
                                      title='Sélectionner une vidéo'
                                    >
                                      <IconButton
                                        color={slide.images[tile.id].video === '' ? 'primary' : 'secondary'}
                                        onClick={event => { context.handleOpenPopover(event, `${indexSection}-${indexSlide}-${indexImage}`) }}>
                                        <OnDemandVideoIcon />
                                      </IconButton>
                                    </Tooltip>
                                    <Popover
                                      open={context.state.popoverOpened === `${indexSection}-${indexSlide}-${indexImage}`}
                                      anchorEl={context.state.anchorPopover}
                                      onClose={context.handleClosePopover}>
                                      <Tooltip
                                        enterDelay={300}
                                        id='tooltip-controlled'
                                        leaveDelay={300}
                                        placement='bottom'
                                        title="Renseigner l'url de la vidéo choisie"
                                      >
                                        <TextField
                                          className={classes.popover}
                                          autoComplete='off'
                                          InputLabelProps={{ shrink: true }}
                                          name='page.title'
                                          label='URL Vidéo'
                                          value={context.state.page.content.sections[indexSection].slides[indexSlide].images[indexImage].video}
                                          onChange={(event) => { context.handleChangePopover(event, indexSection, indexSlide, indexImage) }} />
                                      </Tooltip>
                                    </Popover>
                                  </div>
                                )
                            }
                          </div>
                        </GridListTile>
                      ))
                    }
                  </GridList>
                }
              </div>
            ))
          }
          <div className={classes.options}>
            <Tooltip
              enterDelay={300}
              id='tooltip-controlled'
              leaveDelay={300}
              placement='bottom'
              title="Choisir la disposition de l'assemblage d'images"
            >
              <Button
                variant='outlined'
                onClick={event => { context.handleOpenLayoutMenu(event, indexSection) }}
                className={classes.option}>
                Disposition
              </Button>
            </Tooltip>
            <Tooltip
              enterDelay={300}
              id='tooltip-controlled'
              leaveDelay={300}
              placement='bottom'
              title="Suprimer l'assemblage d'images séléctionné"
            >
              <Button
                variant='outlined'
                onClick={() => { context.handleDeleteSlide(indexSection) }}
                disabled={section.slides.length === 1}
                className={classes.option}>
                Supprimer
              </Button>
            </Tooltip>
            <Tooltip
              enterDelay={300}
              id='tooltip-controlled'
              leaveDelay={300}
              placement='bottom'
              title="Ajouter un assemblage d'images"
            >
              <Button
                variant='outlined'
                onClick={() => { context.handleAddSlide(indexSection) }}
                color='primary'
                className={classes.option}>
                Ajouter
              </Button>
            </Tooltip>
          </div>
          <Menu
            anchorEl={context.state.anchorMenuLayout}
            open={context.state.menuLayoutOpened === indexSection}
            onClose={context.handleCloseLayoutMenu}>
            <MenuItem onClick={() => { context.handleChangeLayoutMenu('2', indexSection) }}>1 Image</MenuItem>
            <MenuItem onClick={() => { context.handleChangeLayoutMenu('2-2', indexSection) }}>2 Images horizontales</MenuItem>
            <MenuItem onClick={() => { context.handleChangeLayoutMenu('1-1', indexSection) }}>2 Images verticales</MenuItem>
            <MenuItem onClick={() => { context.handleChangeLayoutMenu('2-1-1', indexSection) }}>3 Images (Horizontale en haut)</MenuItem>
            <MenuItem onClick={() => { context.handleChangeLayoutMenu('1-1-2', indexSection) }}>3 Images (Horizontale en bas)</MenuItem>
            <MenuItem onClick={() => { context.handleChangeLayoutMenu('1-1-1-1', indexSection) }}>4 Images</MenuItem>
          </Menu>
        </Grid>
      </Grid>
    </ExpansionPanelDetails>
    <Divider />
    <ExpansionPanelActions>
      <Tooltip
        enterDelay={300}
        id='tooltip-controlled'
        leaveDelay={300}
        placement='bottom'
        title='Supprimer le volet'
      >
        <Button
          onClick={() => { context.handleDeleteSection(indexSection) }}
          disabled={context.state.page.content.sections.length === 1}>
          Supprimer
        </Button>
      </Tooltip>
    </ExpansionPanelActions>
  </ExpansionPanel>
)

const SortableList = SortableContainer(({ items, state, classes, context }) => {
  return (
    <div>
      {items.map((value, index) => (
        <SortableItem key={`item-${index}`} index={index} value={value} indexSection={index} section={value} context={context} classes={classes} state={state} />
      ))}
    </div>
  )
})

export class ContentForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      locale: this.props.locale,
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
      page: this.props.page
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleInputFilter = this.handleInputFilter.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleOpenPopover = this.handleOpenPopover.bind(this)
    this.handleClosePopover = this.handleClosePopover.bind(this)
    this.handleCloseVersion = this.handleCloseVersion.bind(this)
    this.handleChangePopover = this.handleChangePopover.bind(this)
    this.handleOpenLayoutMenu = this.handleOpenLayoutMenu.bind(this)
    this.handleCloseLayoutMenu = this.handleCloseLayoutMenu.bind(this)
    this.handleChangeLayoutMenu = this.handleChangeLayoutMenu.bind(this)
    this.handleVersion = this.handleVersion.bind(this)
    this.handleVersionOpen = this.handleVersionOpen.bind(this)
    this.handleParent = this.handleParent.bind(this)
    this.handleChangeTextArea = this.handleChangeTextArea.bind(this)
    this.handleDelete = this.handleDelete.bind(this)
    this.handleDeleteClose = this.handleDeleteClose.bind(this)
    this.handleDeleteConfirm = this.handleDeleteConfirm.bind(this)
    this.handleChangeTabs = this.handleChangeTabs.bind(this)
    this.handleInitTabs = this.handleInitTabs.bind(this)
    this.handleAddSection = this.handleAddSection.bind(this)
    this.handleDeleteSection = this.handleDeleteSection.bind(this)
    this.handleAddSlide = this.handleAddSlide.bind(this)
    this.handleDeleteSlide = this.handleDeleteSlide.bind(this)
    this.handleChangeImageUpload = this.handleChangeImageUpload.bind(this)
    this.handleChangeDocumentUpload = this.handleChangeDocumentUpload.bind(this)
    this.handleAddDocument = this.handleAddDocument.bind(this)
    this.handleUpdateRaw = this.handleUpdateRaw.bind(this)
    this.handleConvertFromHTML = this.handleConvertFromHTML.bind(this)
    this.handleSortSections = this.handleSortSections.bind(this)
    this.handleExpandSection = this.handleExpandSection.bind(this)
  }

  handleSortSections ({ oldIndex, newIndex }) {
    const sections = arrayMove(this.state.page.content.sections, oldIndex, newIndex)
    const state = immutable.set(this.state, 'page.content.sections', sections)
    this.setState(state)
  }

  handleUpdateRaw (props) {
    const sections = this.handleConvertFromHTML(props.page.content.sections)
    const state = immutable.set(props, `page.content.sections`, sections)
    this.setState(state, () => {
      this.handleInitTabs()
    })
  }

  handleInitTabs () {
    const indexTabs = this.state.page.content.sections.map(() => { return 0 })
    this.setState({ indexTabs })
  }

  handleConvertFromHTML (sections) {
    return sections.map(section => {
      const blocksFromHTML = convertFromHTML(section.body)
      const content = blocksFromHTML.contentBlocks
        ? ContentState.createFromBlockArray(
          blocksFromHTML.contentBlocks,
          blocksFromHTML.entityMap
        )
        : ContentState.createFromText('')
      section.bodyRaw = EditorState.createWithContent(content)
      return section
    })
  }

  handleConvertFromRaw (sections) {
    return sections.map(section => {
      section.bodyRaw && (
        section.body = draftToHtml(convertToRaw(section.bodyRaw.getCurrentContent()))
      )
      return section
    })
  }

  handleChangeTabs (indexTabs, indexSection) {
    const state = immutable.set(this.state, `indexTabs.${indexSection}`, indexTabs)
    this.setState(state)
  }

  handleVersion (event, key) {
    event.preventDefault()
    if (key === null) {
      this.props.versionHandler(this.state.page, null)
    } else {
      this.props.versionHandler(this.state.page, this.props.versions[key].version)
    }
    this.setState({ versionCount: key, anchorVersion: null })
  }

  handleParent (event) {
    const parentKey = event.target.value
    this.setState((prevState) => {
      return {
        ...prevState,
        page: {
          ...prevState.page,
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
    const state = immutable.set(this.state, `page.content.sections.${indexSection}.bodyRaw`, editorState)
    this.setState(state)
  }

  handleSubmit (event) {
    event.preventDefault()
    const sections = this.handleConvertFromRaw(this.state.page.content.sections)
    const state = immutable.set(this.state, 'page.content.sections', sections)
    this.setState(state, () => {
      this.props.submitHandler(this.state.page)
    })
  }

  handleInputFilter (event) {
    const re = /[0-9A-Za-z-]+/g
    if (!re.test(event.key)) {
      event.preventDefault()
    }
  }

  handleOpenPopover (event, indexPopover) {
    this.setState({
      anchorPopover: event.currentTarget,
      popoverOpened: indexPopover
    })
  }

  handleClosePopover () {
    this.setState({
      anchorPopover: null,
      popoverOpened: false
    })
  }

  handleCloseVersion () {
    this.setState({ anchorVersion: null })
  }

  handleVersionOpen (event) {
    this.setState({ anchorVersion: event.currentTarget })
  }

  handleChangePopover (event, indexSection, indexSlide, indexImage) {
    const state = immutable.set(this.state, `page.content.sections.${indexSection}.slides.${indexSlide}.images.${indexImage}.video`, event.target.value)
    this.setState(state)
  }

  handleOpenLayoutMenu (event, indexSection) {
    this.setState({
      anchorMenuLayout: event.currentTarget,
      menuLayoutOpened: indexSection
    })
  }

  handleCloseLayoutMenu () {
    this.setState({
      anchorMenuLayout: null,
      menuLayoutOpened: false
    })
  }

  handleChangeLayoutMenu (layout, indexSection) {
    const indexSlide = this.state.indexTabs[indexSection]
    const state = immutable.set(this.state, `page.content.sections.${indexSection}.slides.${indexSlide}.layout`, layout)
    this.setState(state, () => {
      this.handleCloseLayoutMenu()
    })
  }

  handleDelete () {
    this.setState({ showDeleteAlert: true })
  }

  handleDeleteClose () {
    this.setState({ showDeleteAlert: false })
  }

  handleDeleteConfirm () {
    this.props.deleteHandler(this.state.page)
    this.setState({ showDeleteAlert: false })
  }

  handleAddSection () {
    const emptySection = ContentForm.defaultProps.page.content.sections[0]
    const position = this.state.page.content.sections.length
    const state = immutable.insert(this.state, `page.content.sections`, emptySection, position)
    this.setState(state, () => {
      this.handleInitTabs()
    })
  }

  handleDeleteSection (indexSection) {
    const state = immutable.del(this.state, `page.content.sections.${indexSection}`)
    this.setState(state, () => {
      this.handleInitTabs()
    })
  }

  handleAddSlide (indexSection) {
    const emptySlide = ContentForm.defaultProps.page.content.sections[0].slides[0]
    const position = this.state.page.content.sections[indexSection].slides.length
    let state = immutable.insert(this.state, `page.content.sections.${indexSection}.slides`, emptySlide, position)
    // ontext.state.indexTabs[indexSection]
    this.setState(immutable.set(state, `indexTabs.${indexSection}`, position))
  }

  handleDeleteSlide (indexSection) {
    const indexSlide = this.state.indexTabs[indexSection]
    const state = immutable.del(this.state, `page.content.sections.${indexSection}.slides.${indexSlide}`)
    this.setState(state, () => {
      this.handleInitTabs()
    })
  }

  isSubmitEnabled () {
    const p = this.state.page
    if (p.title === '' || p.description === '' || p.url === '') {
      return false
    }
    if (!this.props.edit && ((p.locale !== 'fr' && !p.parent_id) || (p.locale === 'fr' && p.parent_id))) {
      return false
    }
    return true
  }

  handleChangeImageUpload (event, indexSection, indexSlide, indexImage) {
    this.props.dispatch(uploadFile(event.target.files[0]))
    this.setState({
      fileUploading: {
        isUploading: true,
        type: 'image',
        indexSection: indexSection,
        indexSlide: indexSlide,
        indexImage: indexImage
      }
    })
  }

  handleChangeDocumentUpload (event, indexSection) {
    this.props.dispatch(uploadFile(event.target.files[0]))
    this.setState({
      fileUploading: {
        isUploading: true,
        type: 'document',
        indexSection: indexSection
      }
    })
  }

  handleAddDocument (linkToDocument, indexSection) {
    const oldEditorState = this.state.page.content.sections[indexSection].bodyRaw
    const oldEditorStateSelection = oldEditorState.getSelection()
    if (!oldEditorStateSelection.isCollapsed()) {
      const editorState = RichUtils.toggleLink(
        oldEditorState,
        oldEditorStateSelection,
        Entity.create('LINK', 'MUTABLE', { url: linkToDocument })
      )
      this.handleChangeTextArea(editorState, indexSection)
    }
  }

  componentWillMount () {
    this.handleInitTabs()
  }

  componentWillReceiveProps (nextProps) {
    if (nextProps.page) {
      if (JSON.stringify(nextProps.page) !== JSON.stringify(this.props.page)) {
        this.handleUpdateRaw(nextProps)
      }
    }
    if (nextProps.locale) {
      this.setState((prevState) => {
        return {
          page: {
            ...prevState.page,
            locale: nextProps.locale,
            parent_id: null
          }
        }
      })
    }
    if (nextProps.uploadStatus) {
      const fileUploading = this.state.fileUploading
      if (fileUploading.type === 'image') {
        const state = immutable.set(this.state, `page.content.sections.${fileUploading.indexSection}.slides.${fileUploading.indexSlide}.images.${fileUploading.indexImage}.url`, nextProps.uploadStatus.path)
        this.setState(state)
      }
      if (fileUploading.type === 'document') {
        this.handleAddDocument(nextProps.uploadStatus.path, fileUploading.indexSection)
      }
      this.setState(prevState => {
        return {
          ...prevState,
          fileUploading: {
            isUploading: false
          }
        }
      })
    }
  }

  handleExpandSection (id, expanded) {
    this.setState((prevState) => {
      return {
        panels: {
          ...prevState.panels,
          [id]: expanded
        }
      }
    })
  }

  render () {
    const { classes } = this.props
    const versions = this.props.versions
    return (
      <div className={classes.container}>
        <Typography variant='display1' className={classes.title}>
          Contenu
        </Typography>
        <form className={classes.form}>
          <Tooltip
            enterDelay={300}
            id='tooltip-controlled'
            leaveDelay={100}
            onClose={this.handleTooltipClose}
            onOpen={this.handleTooltipOpen}
            open={this.state.open}
            placement='bottom'
            title="Renseigner l'introduction de votre page"
          >
            <TextField
              autoComplete='off'
              InputLabelProps={{ shrink: true }}
              className={classes.textfield}
              fullWidth
              multiline
              name='page.content.intro'
              label='Introduction'
              value={this.state.page.content.intro}
              onChange={this.handleInputChange} />
          </Tooltip>
          <SortableList distance={50} items={this.state.page.content.sections} onSortEnd={this.handleSortSections} context={this} classes={classes} state={this.state} useDragHandle />
        </form>
        <div className={classes.buttons}>
          {
            this.props.edit &&
            (
              <Fragment>
                <Tooltip
                  enterDelay={300}
                  id='tooltip-controlled'
                  leaveDelay={300}
                  onClose={this.handleTooltipClose}
                  onOpen={this.handleTooltipOpen}
                  open={this.state.open}
                  placement='bottom'
                  title='Historique des versions'
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

            )
          }
          <Tooltip
            enterDelay={300}
            id='tooltip-controlled'
            leaveDelay={300}
            onClose={this.handleTooltipClose}
            onOpen={this.handleTooltipOpen}
            open={this.state.open}
            placement='bottom'
            title='Ajouter un volet'
          >
            <Button
              onClick={this.handleAddSection}
              className={classes.button}
              variant='fab'
              color='primary'>
              <Icon>playlist_add</Icon>
            </Button>
          </Tooltip>
          <Tooltip
            enterDelay={300}
            id='tooltip-controlled'
            leaveDelay={300}
            onClose={this.handleTooltipClose}
            onOpen={this.handleTooltipOpen}
            open={this.state.open}
            placement='bottom'
            title='Sauvegarder'
          >
            <Button
              disabled={!this.isSubmitEnabled()}
              onClick={this.handleSubmit}
              className={classes.button}
              variant='fab'
              color='primary'>
              <SaveIcon />
            </Button>
          </Tooltip>
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
  },
  heading: {
    fontSize: theme.typography.pxToRem(12),
    fontStyle: 'italic',
    color: theme.palette.text.secondary,
    flexBasis: '75px'
  },
  secondaryHeading: {
    fontSize: theme.typography.pxToRem(15),
    flexShrink: 0
  }
})

const mapStateToProps = state => {
  return {
    status: state.postPageStatus,
    versions: state.pageVersions,
    version: state.pageVersion,
    locale: state.locale,
    uploadStatus: state.uploadStatus
  }
}

ContentForm.propTypes = {
  classes: PropTypes.object.isRequired
}

ContentForm.defaultProps = {
  parents: {},
  parentKey: 0,
  versions: [],
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
                { url: '', alt: '', video: '' },
                { url: '', alt: '', video: '' },
                { url: '', alt: '', video: '' },
                { url: '', alt: '', video: '' }
              ]
            }
          ]
        }
      ]
    }
  }
}

export default compose(withStyles(styles), connect(mapStateToProps))(ContentForm)