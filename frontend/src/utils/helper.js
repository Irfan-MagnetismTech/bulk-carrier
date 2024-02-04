import moment from 'moment';

export const formatDate = date => date ? moment(date).format('DD/MM/YYYY') : '';

export const formatDateTime = dateTime => dateTime ? moment(dateTime).format('DD/MM/YYYY, hh:mm A') : '';

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