import moment from 'moment';
import Swal from 'sweetalert2';

export const itemsPerPageOptions = [
    { label: '15', value: 15 },
    { label: '30', value: 30 },
    { label: '50', value: 50 },
    { label: '100', value: 100 }
];
  
export const loaderSetting = { 'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2' };


export const formatDate = date => date ? moment(date).format('DD/MM/YYYY') : '';

export const showErrorAlert = messages => {
    if (messages.value.length > 0) {
        let rawHtml = ` <ul class="text-left list-disc text-red-500 mb-3 px-5 text-base"> `;
        if (Object.keys(messages.value).length) {
            for (const property in messages.value) {
                rawHtml += `<li> ${messages.value[property]} </li>`
            }
            rawHtml += `</ul>`;

            Swal.fire({
                icon: "",
                title: "Correct Please!",
                html: `
            ${rawHtml}
                    `,
                customClass: "swal-width",
            });
            return false;
        }
    } else {
        return true;
    }
}