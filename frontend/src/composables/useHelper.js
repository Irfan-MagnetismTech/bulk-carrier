
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
        const downloadFile = (data, fileName) => {
        const url = window.URL.createObjectURL(new Blob([data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', fileName);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

  return {
    numberFormat,
    downloadFile
  };
}