import React, { Fragment } from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { Editor } from 'react-draft-wysiwyg'
import immutable from 'object-path-immutable'
import draftToHtml from 'draftjs-to-html'
import htmlToDraft from 'html-to-draftjs'
import { SortableContainer, SortableElement, arrayMove, SortableHandle } from 'react-sortable-hoc'
import ExpandMoreIcon from '@material-ui/icons/ExpandMore'
import SaveIcon from '@material-ui/icons/Save'
import OnDemandVideoIcon from '@material-ui/icons/OndemandVideo'
import ArrowUpwardIcon from '@material-ui/icons/ArrowUpward'
import FilterCenterFocusIcon from '@material-ui/icons/FilterCenterFocus'
import CropIcon from '@material-ui/icons/Crop'
import FontDownloadIcon from '@material-ui/icons/FontDownload'
import PhotoSizeSelectActualIcon from '@material-ui/icons/PhotoSizeSelectActual'
import DeleteIcon from '@material-ui/icons/Delete'
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
  ContentState } from 'draft-js'
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
  IconButton,
  CircularProgress,
  Select,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
  FormControl,
  InputLabel,
  Tooltip,
  Icon
} from '@material-ui/core'
import IsAuthorized, { ACTION_CONTENT_SUPER_ADMIN, ACTION_PAGE_DELETE } from '../../isauthorized/isauthorized'

const TooltipWrapper = ({children, title}) => (
  <Tooltip
    enterDelay={300}
    id='tooltip-controlled'
    leaveDelay={100}
    placement='bottom'
    title={title}
  >
    {children}
  </Tooltip>
)

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
              InputLabelProps={{shrink: true}}
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
            localization={{
              locale: 'fr',
              translations: {
                'components.controls.link.linkTarget': 'Lien (URL)'
              }
            }}
            editorState={context.state.page.content.sections[indexSection].bodyRaw}
            onEditorStateChange={editorState => context.handleChangeTextArea(editorState, indexSection)}
            toolbarCustomButtons={[<CustomOption indexSection={indexSection} addDocument={(event, indexSection) => { context.handleChangeDocumentUpload(event, indexSection) }} />]}
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
        { section.slides &&
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
                          <div className={classes.tile} style={{backgroundImage: `url('${slide.images[tile.id].url}')`}}>
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
                                      title='Sélectionner une image (2Mo max)'
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
                                      title='Définir un cadrage'
                                    >
                                      <IconButton
                                        color={'crop' in slide.images[tile.id] && slide.images[tile.id].crop !== '' ? 'secondary' : 'primary'}
                                        onClick={event => { context.handleChangeImageCrop(event, `${indexSection}-${indexSlide}-${indexImage}`) }}>
                                        <CropIcon />
                                      </IconButton>
                                    </Tooltip>
                                    <br />
                                    <Tooltip
                                      enterDelay={300}
                                      id='tooltip-controlled'
                                      leaveDelay={300}
                                      placement='bottom'
                                      title='Ajouter une vidéo'
                                    >
                                      <IconButton
                                        color={slide.images[tile.id].video === '' ? 'primary' : 'secondary'}
                                        onClick={event => { context.handleChangeImageVideo(event, `${indexSection}-${indexSlide}-${indexImage}`) }}>
                                        <OnDemandVideoIcon />
                                      </IconButton>
                                    </Tooltip>
                                    <Tooltip
                                      enterDelay={300}
                                      id='tooltip-controlled'
                                      leaveDelay={300}
                                      placement='bottom'
                                      title='Ajouter une légende'
                                    >
                                      <IconButton
                                        color={slide.images[tile.id].alt === '' ? 'primary' : 'secondary'}
                                        onClick={event => { context.handleChangeImageAlt(event, `${indexSection}-${indexSlide}-${indexImage}`) }}>
                                        <FontDownloadIcon />
                                      </IconButton>
                                    </Tooltip>
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
        }
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

export class PageForm extends React.Component {
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
      page: this.props.page,
      noticeBlockquote: true,
      noticeVideo: true,
      noticeId: '0-0-0',
      AlertBlockquoteOpen: false,
      AlertVideoOpen: false,
      AlertCropOpen: false,
      AlertAltOpen: false
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleInputFilter = this.handleInputFilter.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleChangeImageVideo = this.handleChangeImageVideo.bind(this)
    this.handleChangeImageCrop = this.handleChangeImageCrop.bind(this)
    this.handleChangeImageAlt = this.handleChangeImageAlt.bind(this)
    this.handleCloseVersion = this.handleCloseVersion.bind(this)
    this.handleChangeVideo = this.handleChangeVideo.bind(this)
    this.handleChangeCrop = this.handleChangeCrop.bind(this)
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
    this.handleOpenAlertBlockquote = this.handleOpenAlertBlockquote.bind(this)
    this.handleCloseAlertBlockquote = this.handleCloseAlertBlockquote.bind(this)
    this.handleOpenAlertVideo = this.handleOpenAlertVideo.bind(this)
    this.handleCloseAlertVideo = this.handleCloseAlertVideo.bind(this)
    this.handleCloseAlertCrop = this.handleCloseAlertCrop.bind(this)
    this.handleOpenAlertAlt = this.handleOpenAlertAlt.bind(this)
    this.handleCloseAlertAlt = this.handleCloseAlertAlt.bind(this)
    this.handleChangeType = this.handleChangeType.bind(this)
    this.handleParent = this.handleParent.bind(this)
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
      const blocksFromHTML = htmlToDraft(section.body)
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
    const parent = this.props.parents.find(page => page.id === parentKey)
    this.setState((prevState) => {
      return {
        ...prevState,
        page: {
          ...prevState.page,
          parent,
          parent_id: parent.id
        },
        parentKey: parentKey
      }
    })
  }

  handleInputChange (event) {
    event.preventDefault();
    const state = immutable.set(this.state, event.target.name, event.target.value)
    this.setState(state)
  }

  handleChangeTextArea (editorState, indexSection) {
    const state = immutable.set(this.state, `page.content.sections.${indexSection}.bodyRaw`, editorState)
    this.setState(state)
  }

  handleSubmit (event) {
    event.preventDefault();
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

  handleChangeImageVideo (event, indexPopover) {
    this.handleOpenAlertVideo()
    this.setState({
      noticeId: indexPopover
    })
  }

  handleChangeImageCrop (event, indexPopover) {
    this.handleOpenAlertCrop()
    this.setState({
      noticeId: indexPopover
    })
  }

  handleChangeImageAlt (event, indexPopover) {
    this.handleOpenAlertAlt()
    this.setState({
      noticeId: indexPopover
    })
  }

  handleCloseVersion () {
    this.setState({anchorVersion: null})
  }

  handleVersionOpen (event) {
    this.setState({anchorVersion: event.currentTarget})
  }

  handleChangeVideo (event, indexSection, indexSlide, indexImage) {
    const state = immutable.set(this.state, `page.content.sections.${indexSection}.slides.${indexSlide}.images.${indexImage}.video`, event.target.value)
    this.setState(state)
  }

  handleChangeCrop (position, indexSection, indexSlide, indexImage) {
    const state = immutable.set(this.state, `page.content.sections.${indexSection}.slides.${indexSlide}.images.${indexImage}.crop`, position)
    this.setState(state)
  }

  handleChangeAlt (event, indexSection, indexSlide, indexImage) {
    const state = immutable.set(this.state, `page.content.sections.${indexSection}.slides.${indexSlide}.images.${indexImage}.alt`, event.target.value)
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
    const emptySection = PageForm.defaultProps.page.content.sections[0]
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
    const emptySlide = PageForm.defaultProps.page.content.sections[0].slides[0]
    const position = this.state.page.content.sections[indexSection].slides.length
    const state = immutable.insert(this.state, `page.content.sections.${indexSection}.slides`, emptySlide, position)
    this.setState(state, () => {
      this.handleInitTabs()
    })
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
    this.setState({
      fileUploading: {
        isUploading: true,
        type: 'image',
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

  handleChangeDocumentUpload (event, indexSection) {
    this.setState({
      fileUploading: {
        isUploading: true,
        type: 'document',
        indexSection: indexSection
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
        window.alert(nextProps.uploadStatus.path)
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

  handleOpenAlertBlockquote () {
    this.setState({ AlertBlockquoteOpen: true })
  }

  handleCloseAlertBlockquote () {
    this.setState({ AlertBlockquoteOpen: false })
  }

  handleOpenAlertVideo () {
    this.setState({ AlertVideoOpen: true })
  }

  handleCloseAlertVideo () {
    this.setState({ AlertVideoOpen: false })
  }

  handleOpenAlertCrop () {
    this.setState({ AlertCropOpen: true })
  }

  handleCloseAlertCrop () {
    this.setState({ AlertCropOpen: false })
  }

  handleOpenAlertAlt () {
    this.setState({ AlertAltOpen: true })
  }

  handleCloseAlertAlt () {
    this.setState({ AlertAltOpen: false })
  }
  handleChangeType (value) {
    this.setState((prevState) => {
      return {
        ...prevState,
        page: {
          ...prevState.page,
          type: value
        },
        forceRefresh: Math.random()
      }
    })
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
    const noticeIds = this.state.noticeId.split('-')
    const noticeIndexSection = noticeIds[0]
    const noticeIndexSlide = noticeIds[1]
    const noticeIndexImage = noticeIds[2]
    const parents = (this.props.parents.length > 0)
      ? this.props.parents.map((p, k) => {
        return (
          <MenuItem value={k} key={k}>{p.title} {p.sub_title}</MenuItem>
        )
      })
      : null
    return (
      <div className={classes.container}>
        <Dialog open={this.state.fileUploading.isUploading &&
        this.state.fileUploading.type === 'image'}>
          <DialogTitle>Image</DialogTitle>
          <DialogContent>
            <DialogContentText>
              <p>Merci de patienter pendant le chargement de votre image...</p>
              <CircularProgress />
            </DialogContentText>
          </DialogContent>
        </Dialog>
        <Dialog
          open={this.state.AlertBlockquoteOpen}
          onClose={this.handleCloseAlertBlockquote}>
          <DialogTitle>Citation</DialogTitle>
          <DialogContent>
            <DialogContentText>
              <p>La citation doit être renseignée en <em>italique</em><br />L'auteur doit être renseigné en <strong>gras</strong> et police romaine</p>
              <p>Exemple :<br /><em>La foi, c'est une confiance, la gratuité d'une amitié.</em> <strong>Florin Callerand</strong></p>
            </DialogContentText>
          </DialogContent>
          <DialogActions>
            <Button onClick={this.handleCloseAlertBlockquote} color='primary' autoFocus>J'ai compris !</Button>
          </DialogActions>
        </Dialog>
        { this.state.page.content.sections[noticeIndexSection].slides &&
        <Fragment>
          <Dialog
            open={this.state.AlertVideoOpen}
            onClose={this.handleCloseAlertVideo}>
            <DialogTitle>Vidéo</DialogTitle>
            <DialogContent>
              <DialogContentText>
                <p>Vous êtes sur le point de renseigner l'URL de la vidéo. Ceci ne dispense pas d'ajouter une image !</p><br />
                <TextField
                  autoComplete='off'
                  InputLabelProps={{ shrink: true }}
                  name='page.title'
                  label='URL Vidéo'
                  value={this.state.page.content.sections[noticeIndexSection].slides[noticeIndexSlide].images[noticeIndexImage].video}
                  onChange={(event) => { this.handleChangeVideo(event, noticeIndexSection, noticeIndexSlide, noticeIndexImage) }} />
              </DialogContentText>
            </DialogContent>
            <DialogActions>
              <Button onClick={this.handleCloseAlertVideo} color='secondary' autoFocus>Annuler</Button>
              <Button onClick={this.handleCloseAlertVideo} color='primary' autoFocus>Valider</Button>
            </DialogActions>
          </Dialog>
          <Dialog
            open={this.state.AlertCropOpen}
            onClose={this.handleCloseAlertCrop}>
            <DialogTitle>Cadrage</DialogTitle>
            <DialogContent>
              <DialogContentText>
                <p>Veuillez sélectionnez la zone à privilégier si la photo doit être recadrée.<br />
                  Le sujet de la photo se situe plutôt vers :</p><br />
                <div style={{ position: 'relative', display: 'inline-block', minWidth: 150, minHeight: 150, backgroundColor: 'whitesmoke' }}>
                  <img style={{ display: 'block', maxWidth: 400, maxHeight: 400, opacity: 0.5 }} src={this.state.page.content.sections[noticeIndexSection].slides[noticeIndexSlide].images[noticeIndexImage].url} />
                  <ArrowUpwardIcon
                    color={this.state.page.content.sections[noticeIndexSection].slides[noticeIndexSlide].images[noticeIndexImage].crop === 'left top' ? 'secondary' : 'primary'}
                    style={{ position: 'absolute', left: 20, top: 20, fontSize: 30, transform: 'rotate(-45deg)', cursor: 'pointer' }}
                    onClick={() => { this.handleChangeCrop('left top', noticeIndexSection, noticeIndexSlide, noticeIndexImage) }}
                  />
                  <ArrowUpwardIcon
                    color={this.state.page.content.sections[noticeIndexSection].slides[noticeIndexSlide].images[noticeIndexImage].crop === 'left center' ? 'secondary' : 'primary'}
                    style={{ position: 'absolute', left: 20, top: '50%', marginTop: -15, fontSize: 30, transform: 'rotate(-90deg)', cursor: 'pointer' }}
                    onClick={() => { this.handleChangeCrop('left center', noticeIndexSection, noticeIndexSlide, noticeIndexImage) }}
                  />
                  <ArrowUpwardIcon
                    color={this.state.page.content.sections[noticeIndexSection].slides[noticeIndexSlide].images[noticeIndexImage].crop === 'left bottom' ? 'secondary' : 'primary'}
                    style={{ position: 'absolute', left: 20, bottom: 20, fontSize: 30, transform: 'rotate(-135deg)', cursor: 'pointer' }}
                    onClick={() => { this.handleChangeCrop('left bottom', noticeIndexSection, noticeIndexSlide, noticeIndexImage) }}
                  />
                  <ArrowUpwardIcon
                    color={this.state.page.content.sections[noticeIndexSection].slides[noticeIndexSlide].images[noticeIndexImage].crop === 'center top' ? 'secondary' : 'primary'}
                    style={{ position: 'absolute', left: '50%', top: 20, marginLeft: -15, fontSize: 30, cursor: 'pointer' }}
                    onClick={() => { this.handleChangeCrop('center top', noticeIndexSection, noticeIndexSlide, noticeIndexImage) }}
                  />
                  <FilterCenterFocusIcon
                    color={this.state.page.content.sections[noticeIndexSection].slides[noticeIndexSlide].images[noticeIndexImage].crop === 'center center' ? 'secondary' : 'primary'}
                    style={{ position: 'absolute', left: '50%', top: '50%', marginLeft: -15, marginTop: -15, fontSize: 30, cursor: 'pointer' }}
                    onClick={() => { this.handleChangeCrop('center center', noticeIndexSection, noticeIndexSlide, noticeIndexImage) }}
                  />
                  <ArrowUpwardIcon
                    color={this.state.page.content.sections[noticeIndexSection].slides[noticeIndexSlide].images[noticeIndexImage].crop === 'center bottom' ? 'secondary' : 'primary'}
                    style={{ position: 'absolute', left: '50%', bottom: 20, marginLeft: -15, fontSize: 30, transform: 'rotate(-180deg)', cursor: 'pointer' }}
                    onClick={() => { this.handleChangeCrop('center bottom', noticeIndexSection, noticeIndexSlide, noticeIndexImage) }}
                  />
                  <ArrowUpwardIcon
                    color={this.state.page.content.sections[noticeIndexSection].slides[noticeIndexSlide].images[noticeIndexImage].crop === 'right top' ? 'secondary' : 'primary'}
                    style={{ position: 'absolute', right: 20, top: 20, fontSize: 30, transform: 'rotate(45deg)', cursor: 'pointer' }}
                    onClick={() => { this.handleChangeCrop('right top', noticeIndexSection, noticeIndexSlide, noticeIndexImage) }}
                  />
                  <ArrowUpwardIcon
                    color={this.state.page.content.sections[noticeIndexSection].slides[noticeIndexSlide].images[noticeIndexImage].crop === 'right center' ? 'secondary' : 'primary'}
                    style={{ position: 'absolute', right: 20, top: '50%', marginTop: -15, fontSize: 30, transform: 'rotate(90deg)', cursor: 'pointer' }}
                    onClick={() => { this.handleChangeCrop('right center', noticeIndexSection, noticeIndexSlide, noticeIndexImage) }}
                  />
                  <ArrowUpwardIcon
                    color={this.state.page.content.sections[noticeIndexSection].slides[noticeIndexSlide].images[noticeIndexImage].crop === 'right bottom' ? 'secondary' : 'primary'}
                    style={{ position: 'absolute', right: 20, bottom: 20, fontSize: 30, transform: 'rotate(135deg)', cursor: 'pointer' }}
                    onClick={() => { this.handleChangeCrop('right bottom', noticeIndexSection, noticeIndexSlide, noticeIndexImage) }}
                  />
                </div>
              </DialogContentText>
            </DialogContent>
            <DialogActions>
              <Button onClick={this.handleCloseAlertCrop} color='secondary' autoFocus>Annuler</Button>
              <Button onClick={this.handleCloseAlertCrop} color='primary' autoFocus>Valider</Button>
            </DialogActions>
          </Dialog>
          <Dialog
            open={this.state.AlertAltOpen}
            onClose={this.handleCloseAlertAlt}>
            <DialogTitle>Légende</DialogTitle>
            <DialogContent>
              <DialogContentText>
                <p>Vous êtes sur le point de renseigner la légende de l'image.</p><br />
                <TextField
                  autoComplete='off'
                  InputLabelProps={{ shrink: true }}
                  name='page.title'
                  label='Légende'
                  value={this.state.page.content.sections[noticeIndexSection].slides[noticeIndexSlide].images[noticeIndexImage].alt}
                  onChange={(event) => { this.handleChangeAlt(event, noticeIndexSection, noticeIndexSlide, noticeIndexImage) }} />
              </DialogContentText>
            </DialogContent>
            <DialogActions>
              <Button onClick={this.handleCloseAlertAlt} color='secondary' autoFocus>Annuler</Button>
              <Button onClick={this.handleCloseAlertAlt} color='primary' autoFocus>Valider</Button>
            </DialogActions>
          </Dialog>
        </Fragment>
        }
        <IsAuthorized action={ACTION_CONTENT_SUPER_ADMIN}>
          <Typography variant='display1' className={classes.title}>
            ADMIN
          </Typography>
          <form className={classes.form} onSubmit={this.handleSubmit}>
            {this.state.page.locale === 'fr' && (
              <div>
                <TooltipWrapper
                  title='Détermine qui a les droits de voir/modifier ce contenu'
                >
                  <FormControl style={{ width: '100%', marginBottom: '30px' }}>
                    <InputLabel htmlFor={'type'} shrink>Type de page</InputLabel>
                    <Select
                      id={'type'}
                      className={classes.select}
                      value={this.state.page.type || ''}
                      onChange={e => this.handleChangeType(e.target.value)}
                    >
                      <MenuItem value={'association'}>Association</MenuItem>
                      <MenuItem value={'editions'}>Édition</MenuItem>
                    </Select>
                  </FormControl>
                </TooltipWrapper>
                <TooltipWrapper
                  title={`La catégorie dans laquelle est rangée la page dans l'admin`}
                >
                  <TextField
                    autoComplete='off'
                    InputLabelProps={{ shrink: true }}
                    className={classes.textfield}
                    fullWidth
                    name='page.category'
                    label='Catégorie'
                    value={this.state.page.category || ''}
                    onChange={this.handleInputChange} />
                </TooltipWrapper>
              </div>
            )}
            {this.state.page.locale !== 'fr' &&
            <TooltipWrapper
              title='Renseigner la traduction française associée'
            >
              <FormControl style={{ width: '100%' }}>
                <InputLabel htmlFor={'parent'} shrink>Page parente</InputLabel>
                <Select
                  id={'parent'}
                  className={classes.select}
                  value={this.state.page.parent ? this.state.page.parent.id : ''}
                  onChange={this.handleParent}
                  inputProps={{
                    name: 'parent_key',
                    id: 'parent_key'
                  }}>
                  {parents}
                </Select>
              </FormControl>
            </TooltipWrapper>
            }
          </form>
        </IsAuthorized>
        <Typography variant='display1' className={classes.title}>
          SEO
        </Typography>
        <form className={classes.form} onSubmit={this.handleSubmit}>
          <TooltipWrapper
            title='Renseigner le Titre 1 de la page'
          >
            <TextField
              required
              autoComplete='off'
              InputLabelProps={{shrink: true}}
              className={classes.textfield}
              fullWidth
              name='page.title'
              label='Titre ligne 1'
              value={this.state.page.title}
              onChange={this.handleInputChange} />
          </TooltipWrapper>
          <TooltipWrapper
            title='Renseigner le Titre 2 de la page'
          >
            <TextField
              required
              autoComplete='off'
              InputLabelProps={{shrink: true}}
              className={classes.textfield}
              fullWidth
              name='page.sub_title'
              label='Titre ligne 2'
              value={this.state.page.sub_title}
              onChange={this.handleInputChange} />
          </TooltipWrapper>
          <TooltipWrapper
            title="Renseigner l'url de la page"
          >
            <TextField
              required
              autoComplete='off'
              InputLabelProps={{shrink: true}}
              className={classes.textfield}
              fullWidth
              name='page.url'
              label='Url'
              value={this.state.page.url}
              onChange={this.handleInputChange}
              onKeyPress={this.handleInputFilter} />
          </TooltipWrapper>
          <TooltipWrapper
            title='Renseigner les méta-description de votre page'
          >
            <TextField
              required
              autoComplete='off'
              InputLabelProps={{shrink: true}}
              className={classes.textfield}
              fullWidth
              multiline
              name='page.description'
              label='Meta-description'
              value={this.state.page.description}
              onChange={this.handleInputChange} />
          </TooltipWrapper>
        </form>
        <Typography variant='display1' className={classes.title}>
          Contenu
        </Typography>
        <form className={classes.form} onSubmit={this.handleSubmit}>
          <TooltipWrapper
            title="Renseigner l'introduction de votre page"
          >
            <TextField
              autoComplete='off'
              InputLabelProps={{shrink: true}}
              className={classes.textfield}
              fullWidth
              multiline
              name='page.content.intro'
              label='Introduction'
              value={this.state.page.content.intro}
              onChange={this.handleInputChange} />
          </TooltipWrapper>
          <SortableList distance={50} items={this.state.page.content.sections} onSortEnd={this.handleSortSections} context={this} classes={classes} state={this.state} useDragHandle />
        </form>
        <div className={classes.buttons}>
          {
            this.props.edit &&
            (
              <Fragment>
                <TooltipWrapper
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
                </TooltipWrapper>
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
          <TooltipWrapper
            title='Ajouter un volet'
          >
            <Button
              onClick={this.handleAddSection}
              className={classes.button}
              variant='fab'
              color='primary'>
              <Icon>playlist_add</Icon>
            </Button>
          </TooltipWrapper>
          {
            this.props.edit &&
            <div>
              <IsAuthorized action={ACTION_PAGE_DELETE}>
                <TooltipWrapper
                  title='Supprimer la page'
                >
                  <Button
                    onClick={this.handleDelete}
                    className={classes.button}
                    variant='fab'
                    color='secondary'>
                    <DeleteIcon />
                  </Button>
                </TooltipWrapper>
              </IsAuthorized>
              <Dialog
                open={this.state.showDeleteAlert}
                onClose={this.handleDeleteClose}
                aria-labelledby='alert-dialog-title'
                aria-describedby='alert-dialog-description'>
                <DialogTitle id='alert-dialog-title'>
                  {'Êtes-vous sûr ?'}
                </DialogTitle>
                <DialogContent>
                  <DialogContentText id='alert-dialog-description'>
                    Cette action est irréversible, souhaitez-vous continuer?
                  </DialogContentText>
                </DialogContent>
                <DialogActions>
                  <Button onClick={this.handleDeleteConfirm} color='secondary' autoFocus>Oui</Button>
                  <Button onClick={this.handleDeleteClose} color='primary' autoFocus>Annuler</Button>
                </DialogActions>
              </Dialog>
            </div>
          }
          <TooltipWrapper
            title='Publier'
          >
            <Button
              disabled={!this.isSubmitEnabled()}
              onClick={this.handleSubmit}
              className={classes.button}
              variant='fab'
              color='primary'>
              <SaveIcon />
            </Button>
          </TooltipWrapper>
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

PageForm.propTypes = {
  classes: PropTypes.object.isRequired
}

PageForm.defaultProps = {
  parents: [],
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

export default compose(withStyles(styles), connect(mapStateToProps))(PageForm)
