import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useContract() {
    const router = useRouter();
    const $loading = useLoading();
    const contracts = ref([]);
    const contractAttachments = ref([]);
    const contractNo = ref([]);
    const contract = ref( {
        contract_no: '',
        customer_id: null,
        customer_code: null,
        service_id: '',
        contract_type: '',
        master_contract_id: '',
        rate_type: 'Fixed',
        effective_date: '',
        rate_validity_type: '',
        rate_validity_from: '',
        rate_validity_till: '',
        rate_validity_port: '',
        // new fields start
        valid_from: '',
        valid_from_on: '',
        valid_from_at: '',
        valid_from_bound: '',
        valid_till: '',
        valid_till_on: '',
        valid_till_at: '',
        valid_till_bound: '',
        // new fields end
        billing_party: '',
        billing_address: '',
        billing_emails: '',
        billing_style: '',
        line_1: '',
        line_2: '',
        due_date: '',
        remarks: '',
        short_note: '',
        //new fields for bank details
        bank_id: '',
        bank_name: '',
        account_no: '',
        account_name: '',
        branch: '',
        routing_no: '',
        currency: '',
        special_instruction: '',
        attachment: [],
        agents: [{
            port_code: null,
            name: '',
            billing_name: '',
            billing_email: '',
            billing_style: '',
        }],
        allocations: [],
    });
    const notification = useNotification();
    const errors = ref({});
    const isLoading = ref(false);
    const page = ref('');

    async function getContracts(page,form = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('commercial/contracts', {
                params: {
                    page: page || 1,
                    service_id: form.service_id || null,
                    contract_type: form.contract_type || null,
                    slot_owner_code: form.slot_owner_code || null,
                    customer_id: form.customer_id || null,
                    valid_from: form.valid_from || null,
                    valid_till: form.valid_till || null,
                },
            });
            contracts.value = data.value;
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

    async function storeContract(form) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            for (let i = 0; i < form.attachment.length; i++) {
                formData.append('attachment[]', form.attachment[i]);
            }
        }

        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post('commercial/contracts', formData);
            contract.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "commercial.contracts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    function showContract(contractId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        
        Api.get(`commercial/contracts/${contractId}`)
            .then((response) => {
                contract.value = response.data.value;

                //process contract allocations group start
                if(contract?.value?.allocations?.length) {
                    contract?.value?.allocations.forEach((allocation, allocationIndex) => {
                        let group1 = {group: 'Group-1', currency: contract?.value?.currency, rate: allocation.dg_group_1, per: allocation.dg_unit, calculation: 'Per '+ allocation.dg_unit};
                        let group2 = {group: 'Group-2', currency: contract?.value?.currency, rate: allocation.dg_group_2, per: allocation.dg_unit, calculation: 'Per '+ allocation.dg_unit};
                        let group3 = {group: 'Group-3', currency: contract?.value?.currency, rate: allocation.dg_group_3, per: allocation.dg_unit, calculation: 'Per '+ allocation.dg_unit};
                        let groupC = {group: 'Group-C', currency: contract?.value?.currency, rate: allocation.dg_group_common, per: allocation.dg_unit, calculation: 'Per '+ allocation.dg_unit};
                        contract.value.allocations[allocationIndex].dgGroups = [group1, group2, group3, groupC];
                    });
                }
                //process contract allocations group end
            })
            .catch((error) => {
                errors.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    function copyContract(parentContractId) {
        if (!confirm('Are you sure you want to copy this Contract?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        //NProgress.start();
        isLoading.value = true;

        Api.get(`commercial/contracts/copy/${parentContractId}`)
            .then((response) => {
                contract.value = response.data.value;
                router.push({name: 'commercial.contracts.edit', params: { contractId: contract.value.id }});
            })
            .catch((error) => {
                errors.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
               // NProgress.done();
            });
    }

    async function updateContract(form, contractId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            for (let i = 0; i < form.attachment.length; i++) {
                formData.append('attachment[]', form.attachment[i]);
            }
        }

        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post('commercial/contracts/update', formData);
            contract.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "commercial.contracts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
            notification.showError(422,'',data.message);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    function deleteContract(contractId) {
        if (!confirm('Are you sure you want to delete this Contract?')) {
            return;
        }

        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        
        Api.delete(`commercial/contracts/${contractId}`)
            .then(() => {
                router.go('commercial.contracts.index');
            })
            .catch((error) => {
                errors.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    function deleteContractAttachment(contractAttachmentId,contractAttachmentIndex) {
        if (!confirm('Are you sure you want to delete this Attachment?')) {
            return;
        }

       // NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        Api.delete(`/files/${contractAttachmentId}`)
            .then(() => {
                contractAttachments.value.splice(contractAttachmentIndex,1);
            })
            .catch((error) => {
                errors.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    async function getContractByContractNo(contract_no) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data } = await Api.post(
                'commercial/contract/get-contract-by-contract-no',
                { contract_no }
            );
            contractNo.value = data.value;
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function updateContractStatus(id,currentUrl) {

        if (!confirm('Are you sure you want to update the status?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        //isLoading.value = true;

        try {
            const { data,status } = await Api.post(
                'commercial/contracts/status-update',
                { id }
            );
            notification.showSuccess(status, data.message);
            window.location.reload();

        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            isLoading.value = false;
            loader.hide();
            //NProgress.done();
        }
    }


    return {
        contracts,
        contract,
        contractNo,
        getContracts,
        storeContract,
        showContract,
        contractAttachments,
        getContractByContractNo,
        updateContractStatus,
        copyContract,
        updateContract,
        deleteContractAttachment,
        deleteContract,
        isLoading,
        errors,
    };
}
