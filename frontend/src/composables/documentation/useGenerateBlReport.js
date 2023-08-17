import nprogress from "nprogress";
import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useGenerateBlReport() {
    const router = useRouter();
    const blLoadInfo = ref({});
    const notification = useNotification();
    const blNo = ref([]);
    const errors = ref(null);
    const isLoading = ref(false);
    const uniqueServicePorts = ref([]);
    const voyageSectors = ref([]);
    const generatedBlList = ref([]);

    const formParams = ref( {
        voyage_id: '--Choose Voyage--',
        pol: '',
        pod: '',
        sector: '',
    });

    async function getBillOfLadding(page, form = null) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/documentation/bill-of-ladding', {
                params: {
                    page: page || 1,
                    bl_no: form.bl_no || null,
                    service_id: form.service_id || null,
                    voyage_id: form.voyage_id || null,
                    pol_code: form.pol_code || null,
                    pod_code: form.pod_code || null,
                    customer_id: form.customer_id || null,
                    issue_date_from: form.issue_date_from || null,
                    issue_date_to: form.issue_date_to || null,
                },
            });
            blLoadInfo.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function generateBl(form) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/documentation/generate-bl', form);
            generatedBlList.value = data.value;
            notification.showSuccess(status,'Generate BL Report Successfully');
        } catch (error) {
            const { data, status } = error.response;
            //console.log(data);
            notification.showError(status,'', data.message);
            //errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function generateBlReport(form) {
        NProgress.start();
        isLoading.value = true;
        axios({
            url: '/documentation/generate-bl-pdf',
            data: form,
            method: 'POST',
            responseType: 'blob', // important
        }).then((response) => {
            let dateTime = new Date();

            //stream pdf file to new tab start
            let fileURL = URL.createObjectURL(response.data);
            let a = document.createElement('a');
            a.href = fileURL;
            a.target = '_blank';
            a.click();
            //stream pdf file to new tab end

            // download pdf file start

            // const url = window.URL.createObjectURL(new Blob([response.data]));
            // console.log(url);
            //
            // window.open(url, '_blank');

            // const url = window.URL.createObjectURL(new Blob([response.data]));
            // const link = document.createElement('a');
            // link.href = url;
            //window.open(url, '_blank');
            // link.setAttribute('download', "Bl-Report-" + dateTime.toJSON().slice(0,10).split('-').reverse().join('-') + ".pdf");
            // document.body.appendChild(link);
            // link.click();

            // download pdf file end

        }).catch((error) => {
            console.log(error);
        }).finally(() => {
            NProgress.done();
            isLoading.value = false;
        });
    }

    function getServiceUniquePorts(voyageId) {
        NProgress.start();
        isLoading.value = true;

        Api.get(`/documentation/get-service-port/${voyageId}`)
            .then((response) => {
                uniqueServicePorts.value = response.data.value;
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    async function getVoyageSectors(voyageId) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/operation/voyages/sectors/${voyageId}`);
            voyageSectors.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function getBillOfLadingByBlNo(bl_no) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data } = await Api.post(
                'documentation/bill-of-lading/get-bl-by-bl-no',
                { bl_no }
            );
            blNo.value = data.value;
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        formParams,
        blLoadInfo,
        generatedBlList,
        generateBlReport,
        getServiceUniquePorts,
        blNo,
        getBillOfLadingByBlNo,
        generateBl,
        getBillOfLadding,
        voyageSectors,
        uniqueServicePorts,
        getVoyageSectors,
        isLoading,
        errors,
    };
}
