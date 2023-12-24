import useDebouncedRef from "./useDebouncedRef";
import {ref} from "vue";
import Swal from "sweetalert2";

export default function useHelper() {

  function numberFormat(value){
    let nf = Intl.NumberFormat('en', {
      minimumFractionDigits: 2,
    });
    return nf.format(value);
  }

    /**
     * Download file from API 
     * @param {string} data
     * @param {string} fileName
     * @returns {void}
     * @example
     * const { downloadFile } = useHelper();
     * downloadFile(data, fileName);
     * @example
     * useHelper().downloadFile(data, fileName);
     * @example
     * import useHelper from 'useHelper';
      *
      * const { downloadFile } = useHelper();
      * downloadFile(data, fileName);
      * @example
      * import useHelper from 'vue-composable'; 
      *   
      * useHelper().downloadFile(data, fileName);
      * 
      */
       
  const downloadFile = (data, fileName, headers) => {
    //get fileextension
    const fileTypeToExtension = {
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'xlsx',
        'application/vnd.ms-excel': 'xls',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'docx',
        'application/msword': 'doc',
        'application/pdf': 'pdf',
        'image/jpeg': 'jpg',
        'image/png': 'png',
        'image/gif': 'gif',
        'text/csv': 'csv',
        'text/plain': 'txt',
        'application/zip': 'zip',
        'application/x-rar-compressed': 'rar',
    };
    
      const fileType = headers['content-type'];
      const fileExtension = fileTypeToExtension[fileType] || 'unknown';
      const fName = `${fileName}.${fileExtension}`;
    // const fileType = headers['content-type'];
    // const fileExtension = fileTypeToExtension[fileType] || 'unknown';
    // const fileName = `materials.${fileExtension}`;
    const url = window.URL.createObjectURL(new Blob([data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', fName);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    }

    function checkUniqueArray(lines){
        const itemNamesSet = new Set();
        const messages = ref([]);
        let isHasError = false;
        const hasDuplicates = lines.some((item,index) => {
            if (itemNamesSet.has(item.item_name)) {
                let data = `Duplicate entry [line :${index + 1}]`;
                messages.value.push(data);
                return true; // Duplicate found
            }
            itemNamesSet.add(item.item_name);
            return false; // No duplicate yet
        });

        if (hasDuplicates) {
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
                isHasError = true;
            }
        } else {
            return isHasError;
        }
    }

  return {
    numberFormat,
    downloadFile,
    checkUniqueArray
  };
}