const contact = {
  nom: '',
  prenom: '',
  newfich: false,
  codco: '',
  ident: '',
  civil: '',
  civil2: '',
  adresse: '',
  cp: '',
  ville: '',
  pays: '',
  tel: '',
  mobil: '',
  email: '',
  profession: '',
  datnaiss: '',
  coltyp: '',
  colp: '',
  transport: '',
  lieu: '',
  memo: '',
  check: false,
  aut16: 0,
  datAut16: null
}

const client = {
  professionnel: false,
  tvaintra: '',
  societe: '',
  nom: '',
  prenom: '',
  codcli: '',
  ident: '',
  civil: '',
  civil2: '',
  adresse: '',
  cp: '',
  ville: '',
  pays: '',
  tel: '',
  mobil: '',
  email: '',
  memo: '',
  conNews: false,
  conData: false
}

const delivery = {
  adliv: {
    civil: '',
    prenom: '',
    nom: '',
    adresse: '',
    zipcode: '',
    city: ''
  },
  destliv: '',
  paysliv: '',
  payslivLong: '',
  modpaie: '',
  modliv: '',
  validpaie: '',
  datliv: '',
  paysip: '',
  dateenreg: '',
  cartId: ''
}

const clone = (obj) => {
  var tmp = JSON.stringify(obj)
  return JSON.parse(tmp)
}
export const getDelivery = () => {
  return delivery
}

export const getContact = () => {
  return clone(contact)
}

export const getClient = () => {
  return clone(client)
}
