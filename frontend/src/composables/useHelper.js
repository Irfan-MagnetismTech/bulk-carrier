
export default function useHelper() {

  function numberFormat(value){
    let nf = Intl.NumberFormat('en', {
      minimumFractionDigits: 2,
    });
    return nf.format(value);
  }

  return {
    numberFormat
  };
}