import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";

export default function useBooking() {
    const router = useRouter();
    const bookings = ref([]);
    const $loading = useLoading();
    const error = ref([]);
    const isLoading = ref(false);
    const booking = ref({
        booking_date: '',
        ordered_tues: 0,
        total_tues: 0,
        client_name: '',
        client_contact: '',
        client_email: '',
        departure_port_code: '',
        destination_port_code: '',
        payment_term: '',
        shipment_term: '',
        commodity: '',
        note: '',
        consignee_company: '',
        consignee_contact_name: '',
        consignee_contact: '',
        consignee_email: '',
        booking_tues: [
            {
                status: 'LDN',
                gp_20: 0,
                rf_20: 0,
                fr_20: 0,
                gp_40: 0,
                rf_40: 0,
                fr_40: 0,
                gp_45: 0,
                rf_45: 0,
                fr_45: 0,
                total: 0,
            },
            {
                status: 'MTY',
                gp_20: 0,
                rf_20: 0,
                fr_20: 0,
                gp_40: 0,
                rf_40: 0,
                fr_40: 0,
                gp_45: 0,
                rf_45: 0,
                fr_45: 0,
                total: 0,
            },
        ],
    });

    async function getBookings(page, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data } = await Api.get('/commercial/bookings', {
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                },
            });
            bookings.value = data.value;
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            //NProgress.done();
            loader.hide();
            isLoading.value = false;
        }
    }

    function storeBooking(form) {

        if(!form.departure_port_code || !form.destination_port_code) {
            alert('Please select departure and destination port');
            return;
        }

        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        Api.post("commercial/bookings", form)
            .then(() => {
                booking.value = {};
                router.push("/commercial/bookings");
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    async function showBooking(bookingId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        
        await Api.get(`commercial/bookings/${bookingId}`)
            .then((response) => {
                booking.value = response.data;
                console.log("Booking retrived.");
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    function updateBooking(form, bookingId) {
       // NProgress.start();

        if(!form.departure_port_code || !form.destination_port_code) {
            alert('Please select departure and destination port');
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        
        Api.put(`commercial/bookings/${bookingId}`, form)
            .then((response) => {
                booking.value = response.data;
                console.log("Booking updated.");
                router.push({ name: "commercial.bookings.index" });
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    function deleteBooking(bookingId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        
        Api.delete(`commercial/bookings/${bookingId}`)
            .then(() => {
                getBookings();
                console.log("Booking deleted.");
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    return {
        bookings,
        booking,
        getBookings,
        storeBooking,
        showBooking,
        updateBooking,
        deleteBooking,
        isLoading,
        error,
    };
}
