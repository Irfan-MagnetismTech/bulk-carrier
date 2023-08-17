import Swal from 'sweetalert2';


export default function useSweetAlert2() {

      const showError = (message = null) => {
          Swal.fire({
            icon: 'error',
            text: message,
          })
      };

      const showConfirm = (message = null) => {
         Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Overwrite it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Success',
                    'Action has been completed!',
                    'success'
                )
            }
        })
      };

      const closeAlert = () => {
        close();
      };

      return {
        showError,
        showConfirm,
        closeAlert,
      };
}

