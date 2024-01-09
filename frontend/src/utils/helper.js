import moment from 'moment';

export const formatDate = date => date ? moment(date).format('DD/MM/YYYY') : '';