import Swal from 'sweetalert2';


export default function useSweetAlert2() {

      const showError = (message = null) => {
          Swal.fire({
            icon: 'error',
            text: message,
          })
      };

      function showConfirm(message = null){
             Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                return result.isConfirmed;
            })
      }

      // const showConfirm = (message = null) => {
      //    Swal.fire({
      //       title: 'Are you sure?',
      //       text: 'You want to delete this',
      //       icon: 'warning',
      //       showCancelButton: true,
      //       confirmButtonColor: '#3085d6',
      //       cancelButtonColor: '#d33',
      //       confirmButtonText: 'Yes'
      //   }).then((result) => {
      //       return result.isConfirmed;
      //   })
      // };

      const closeAlert = () => {
        close();
      };

      return {
        showError,
        showConfirm,
        closeAlert,
      };
}

