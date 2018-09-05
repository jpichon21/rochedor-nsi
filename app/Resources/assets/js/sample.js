const participant = {
  nom: '',
  prenom: '',
  codcli: '',
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

const delivery = {
  adliv: {
    adresse: '',
    zipcode: '',
    city: ''
  },
  destliv: '',
  paysliv: '',
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

export const getParticipant = () => {
  return clone(participant)
}
