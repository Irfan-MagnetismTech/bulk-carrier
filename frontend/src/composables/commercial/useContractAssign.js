import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';


export default function useContractAssign() {
    const router = useRouter();
    const $loading = useLoading();
    const contractAssigns = ref([]);
    const customerContracts = ref([]);
    const contractVoyages = ref([]);
    const tariffAssignCustomerList = ref([]);
    const voyageData = ref({});
    const contractAssign = ref([]);
    const notification = useNotification();
    const errors = ref(null);
    const isLoading = ref(false);
    const form = ref({
        voyage_id: null,
    });

    function getContractAssigns(voyageId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        Api.get(`commercial/voyage/contract/list/${voyageId}`)
            .then((response) => {
                contractAssigns.value = response.data.value;
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
               // NProgress.done();
            });
    }

    function storeContractAssign(form) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        Api.post("commercial/contract-assigns", form)
            .then(() => {
                contractAssign.value = {};
                router.push({ name: "commercial.contract-assigns.voyages" });
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
               // NProgress.done();
            });
    }

    function showContractAssign(voyageCustomerId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        Api.get(`commercial/contract-assigns/${voyageCustomerId}`)
            .then((response) => {
                contractAssign.value = response.data.value;
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

    async function assignCustomerContract(form) {
        if (!confirm('Are you sure you want to assign this contract?')) {
            return;
        }

        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            const { data, status } = await Api.post(
                `commercial/voyage/customer/contract/assign`,
                form
            );
            notification.showSuccess(status, data.message);
            //router.go();

        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
           // NProgress.done();
        }
    }

    async function getContractVoyages(page) {
       // NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/commercial/contract/voyages', {
                params: {
                    page: page || 1,
                },
            });
            contractVoyages.value = data.value.data;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            //NProgress.done();
            isLoading.value = false;
        }
    }

    function showVoyageWithNo(voyageNo) {

        if(!voyageNo){
            notification.showError(422,'','Please enter voyage no');
            return;
        }

        NProgress.start();
        isLoading.value = true;

        Api.get(`operation/voyages/voyage-no/${voyageNo}`)
            .then((response) => {
                contractVoyages.value = response.data.value;
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    async function loadCustomerContracts(form) {

        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            const { data, status } = await Api.post(
                `commercial/get-customer-contracts`,
                form
            );
            customerContracts.value = data.value;
            customerContracts.value.map(function (data,index){
                customerContracts.value[index].sector = form.sector;
                customerContracts.value[index].voyage_customer_id = form.voyage_customer_id;
            });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function getTariffAssignCustomerList(voyageNo) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        

        Api.post(`stevedore/tariff-assign-customers/${voyageNo}`)
            .then((response) => {
                tariffAssignCustomerList.value = response.data;
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
            });
    }

    return {
        form,
        getContractAssigns,
        customerContracts,
        showVoyageWithNo,
        contractAssigns,
        contractAssign,
        contractVoyages,
        assignCustomerContract,
        storeContractAssign,
        showContractAssign,
        getContractVoyages,
        tariffAssignCustomerList,
        getTariffAssignCustomerList,
        voyageData,
        loadCustomerContracts,
        isLoading,
        errors,
    };
}
