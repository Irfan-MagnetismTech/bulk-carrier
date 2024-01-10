import moment from 'moment';

export const itemsPerPageOptions = [
    { label: '15', value: 15 },
    { label: '30', value: 30 },
    { label: '50', value: 50 },
    { label: '100', value: 100 }
];
  
export const loaderSetting = { 'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2' };


export const formatDate = date => date ? moment(date).format('DD/MM/YYYY') : '';