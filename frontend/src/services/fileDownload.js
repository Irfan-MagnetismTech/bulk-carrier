import { computed, onMounted, ref } from 'vue';
import { useStore } from "vuex";
/**
 * @typedef {Object} FileDownload
 * @property {function} downloadFile
 */
export default function useFileDownload() {
    /**
     * Download file
     * @param {string} data
     * @param {string} fileName
     * @returns {void}
     * @example
     * const { downloadFile } = useFileDownload();
     * downloadFile(data, fileName);
     * @example
     * useFileDownload().downloadFile(data, fileName);
     * @example
     * import { useFileDownload } from 'vue-composable';
     * 
     * const { downloadFile } = useFileDownload();
     * downloadFile(data, fileName);
     * @example
     * import { useFileDownload } from 'vue-composable';
     *      
     * useFileDownload().downloadFile(data, fileName);
     * @example
     * import useFileDownload from 'vue-composable/dist/vue-composable.esm';
     * 
     * const { downloadFile } = useFileDownload();
     * downloadFile(data, fileName);
     * @example
     * import useFileDownload from 'vue-composable/dist/vue-composable.esm';
     * 
     * useFileDownload().downloadFile(data, fileName);
     */
        const downloadFile = (data, fileName) => {
        const url = window.URL.createObjectURL(new Blob([data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', fileName);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link); // Remove the link after clicking
    }
    return {
        downloadFile
    }

}


  


