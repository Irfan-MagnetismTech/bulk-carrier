import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref, reactive } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useTariff() {
    const router = useRouter();
    const $loading = useLoading();
    const tariffs = ref([]);
    const tariffCustomers = ref([]);
    const invoiceData = ref([]);
    const notification = useNotification();
    const errors = ref(null);
    const isLoading = ref(false);
    const isInvoiceModalOpen = ref(0);

    const tariffContainerFile = reactive({
        file: null,
    });

    const formParams = ref({
       voyage_id: null,
       port_code: null,
       operation_type: '',
    });

    const tariff = ref( {
        port_code: null,
        name: '',
        cost_type: '',
        expire_date: '',
        currency: 'USD',
        short_note: '',
        tariff_heads: [
            {
                serial_no: 1,
                name: '',
                code: '',
                vendor: '',
            }
        ],
        tariff_charges: [
            {
                serial_no: 1,
                operation_type: 'Loading',
                load_status: 'TS',
                charge_code: '',
                fcl_20: '',
                fcl_40: '',
                fcl_45: '',
                mty_20: '',
                mty_40: '',
                mty_45: '',
                rf_20: '',
                rf_40: '',
                rf_45: '',
                fr_20: '',
                fr_40: '',
                fr_45: '',
                tk_20: '',
                tk_40: '',
                tk_45: '',
                dg_20: '',
                dg_40: '',
                dg_45: '',
            },
            {
                serial_no: 1,
                operation_type: 'Loading',
                load_status: 'LC',
                charge_code: '',
                currency: '',
                fcl_20: '',
                fcl_40: '',
                fcl_45: '',
                mty_20: '',
                mty_40: '',
                mty_45: '',
                rf_20: '',
                rf_40: '',
                rf_45: '',
                fr_20: '',
                fr_40: '',
                fr_45: '',
                tk_20: '',
                tk_40: '',
                tk_45: '',
                dg_20: '',
                dg_40: '',
                dg_45: '',
            },
            {
                serial_no: 1,
                operation_type: 'Discharge',
                load_status: 'TS',
                charge_code: '',
                currency: '',
                fcl_20: '',
                fcl_40: '',
                fcl_45: '',
                mty_20: '',
                mty_40: '',
                mty_45: '',
                rf_20: '',
                rf_40: '',
                rf_45: '',
                fr_20: '',
                fr_40: '',
                fr_45: '',
                tk_20: '',
                tk_40: '',
                tk_45: '',
                dg_20: '',
                dg_40: '',
                dg_45: '',
            },
            {
                serial_no: 1,
                operation_type: 'Discharge',
                load_status: 'LC',
                charge_code: '',
                currency: '',
                fcl_20: '',
                fcl_40: '',
                fcl_45: '',
                mty_20: '',
                mty_40: '',
                mty_45: '',
                rf_20: '',
                rf_40: '',
                rf_45: '',
                fr_20: '',
                fr_40: '',
                fr_45: '',
                tk_20: '',
                tk_40: '',
                tk_45: '',
                dg_20: '',
                dg_40: '',
                dg_45: '',
            }
        ],
    });

    async function getTariffs() {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/stevedore/tariffs');
            tariffs.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeTariff(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {

            const { data, status } = await Api.post('/stevedore/tariffs', form);
            tariff.value = data.value;
            notification.showSuccess(status, data.message);
            router.push({ name: "stevedorage.tariffs.index" });

        } catch (error) {

            const { data, status } = error.response;
            errors.value = notification.showError(status, data);

        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }


    async function showTariff(tariffId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/stevedore/tariffs/${tariffId}`);
            tariff.value = data.value;
            notification.showSuccess(status, data.message);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }



    async function updateTariff(form, tariffId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});

        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/stevedore/tariffs/${tariffId}`,
                form
            );
            tariff.value = data.value;
            notification.showSuccess(status, data.message);
            router.push({ name: "stevedorage.tariffs.index" });

        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
           // NProgress.done();
        }
    }
    //
    async function deleteTariff(tariffId) {
        if (!confirm('Are you sure you want to delete this tariff?')) {
            return;
        }
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/stevedore/tariffs/${tariffId}`);
            notification.showSuccess(status, data.message);
            await getTariffs();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function tariffContainerExport(voyage_id,voyageName) {
        NProgress.start();
        isLoading.value = true;

        Api.get(`stevedore/export-voyage-containers/${voyage_id}`, {
            responseType: 'blob'
        })
            .then((response) => {

                let fileURL = window.URL.createObjectURL(new Blob([response.data]));
                let fileLink = document.createElement('a');
                let dateTime = new Date();

                fileLink.href = fileURL;
                fileLink.setAttribute('download', `Tariff_Containers_of_${voyageName}.xlsx`);
                document.body.appendChild(fileLink);

                fileLink.click();
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    async function processTariffImportFile(form, voyage_id) {

        NProgress.start();
        isLoading.value = true;
        let formData = new FormData();
        formData.append('file', form.file);
        formData.append('voyage_id', voyage_id);
        axios({
            url: '/stevedore/update-voyage-containers-load-status',
            data: formData,
            method: 'POST',
            responseType: 'blob', // important
        }).then((response) => {

            if (response.status >= 200 && response.status < 300) {
                // Successful response - extract message property
                const reader = new FileReader();
                reader.onload = function() {
                    const data = JSON.parse(reader.result);
                    const message = data.message;
                    console.log("Response message: " + message);
                    notification.showSuccess(response.status, message);
                }
                reader.readAsText(response.data);
            } else {
                // Error response - display error message
                console.error("Request failed with status code: " + response.status);
                notification.showError(response.status, response.statusText);
            }


        }).catch((error) => {

            //get error message incoming from api response and display it

            if (error.response.status === 422) {
                const reader = new FileReader();
                reader.onload = function() {
                    const data = JSON.parse(reader.result);
                    const message = data.message;
                    console.log("Response message: " + message);
                    notification.showError(error.response.status, '', message);
                }
                reader.readAsText(error.response.data);
            } else {
                notification.showError(error.response.status, '', error.response.statusText);
            }

        }).finally(() => {
            NProgress.done();
            isLoading.value = false;

        });
    }

    async function tariffAssign(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {

            const { data, status } = await Api.post('/stevedore/voyage-tariff-assign', form);
            notification.showSuccess(status, data.message);

        } catch (error) {

            const { data, status } = error.response;
            errors.value = notification.showError(status, data);

        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function tariffInvoiceCustomers(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {

            const { data, status } = await Api.post('/stevedore/get-invoice-customers', form);
            tariffCustomers.value = data.value;
            notification.showSuccess(status);

        } catch (error) {

            const { data, status } = error.response;
            errors.value = notification.showError(status, data);

        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function generateInvoice(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/stevedore/generate-stevedore-invoice', form);
            invoiceData.value = data.value;
            isInvoiceModalOpen.value = true;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateTariffStatus(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {

            const { data, status } = await Api.post('/stevedore/tariffs/update/status', form);
            notification.showSuccess(status, data.message);
            getTariffs();
            //router.go({ name: "stevedorage.tariffs.index" });

        } catch (error) {

            const { data, status } = error.response;
            errors.value = notification.showError(status, data);

        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }


    return {
        tariffs,
        tariff,
        getTariffs,
        storeTariff,
        showTariff,
        updateTariff,
        deleteTariff,
        tariffContainerExport,
        processTariffImportFile,
        tariffAssign,
        tariffCustomers,
        tariffInvoiceCustomers,
        isInvoiceModalOpen,
        formParams,
        generateInvoice,
        tariffContainerFile,
        updateTariffStatus,
        invoiceData,
        isLoading,
        errors,
    };
}
