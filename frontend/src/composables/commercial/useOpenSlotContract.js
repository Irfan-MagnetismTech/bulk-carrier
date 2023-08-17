import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useOpenSlotContract() {
    const router = useRouter();
    const contracts = ref([]);
    const notification = useNotification();
    const contract = ref( {
        contract_no: '',
        customer_id: null,
        customer_code: null,
        service_id: '',
        contract_type: '',
        master_contract_id: '',
        rate_type: 'Open',
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
        contract_os_ports: [{
            contract_id: '',
            input_type: '',
            pol: '',
            pod: '',
            pol_pod: '',
            term: '',
            f_payment_location: '',
            f_payment_currency: '',
            s_recovery: '',
            s_recovery_pol: 0,
            s_pol_bill_to: '',
            s_pol_bill_currency: '',
            s_recovery_pod: 0,
            s_pod_bill_to: '',
            s_pod_bill_currency: '',
            weight_capacity: 1,
            tues_45_container: '',
            weight_limit_20: '',
            weight_limit_40: '',
            weight_limit_45: '',
            contract_os_port_rates: [{
                contract_os_port_id: '',
                charge_type: '',
                charge_type_category: '',
                gp_20_ldn: 1,
                gp_40_ldn: 1,
                gp_45_ldn: 1,
                gp_20_mty: 1,
                gp_40_mty: 1,
                gp_45_mty: 1,
            }],
            contract_os_port_surcharges: [
                {
                    contract_os_port_id: '',
                    charge_type: 'DG-1',
                    charge_type_category: 'surcharge',
                    unit: '',
                    ldn_20: 1,
                    ldn_40: 1,
                    ldn_45: 1,
                    mty_20: 1,
                    mty_40: 1,
                    mty_45: 1,
                },
                {
                    contract_os_port_id: '',
                    charge_type: 'DG-2',
                    charge_type_category: 'surcharge',
                    unit: '',
                    ldn_20: 1,
                    ldn_40: 1,
                    ldn_45: 1,
                    mty_20: 1,
                    mty_40: 1,
                    mty_45: 1,
                },
                {
                    contract_os_port_id: '',
                    charge_type: 'DG-3',
                    charge_type_category: 'surcharge',
                    unit: '',
                    ldn_20: 1,
                    ldn_40: 1,
                    ldn_45: 1,
                    mty_20: 1,
                    mty_40: 1,
                    mty_45: 1,
                },
                {
                    contract_os_port_id: '',
                    charge_type: 'DG-C',
                    charge_type_category: 'surcharge',
                    unit: '',
                    ldn_20: 1,
                    ldn_40: 1,
                    ldn_45: 1,
                    mty_20: 1,
                    mty_40: 1,
                    mty_45: 1,
                },
                {
                    contract_os_port_id: '',
                    charge_type: 'RF',
                    charge_type_category: 'surcharge',
                    unit: null,
                    ldn_20: 1,
                    ldn_40: 1,
                    ldn_45: 1,
                    mty_20: 1,
                    mty_40: 1,
                    mty_45: 1,
                },
                {
                    contract_os_port_id: '',
                    charge_type: 'TK',
                    charge_type_category: 'surcharge',
                    unit: null,
                    ldn_20: 1,
                    ldn_40: 1,
                    ldn_45: 1,
                    mty_20: 1,
                    mty_40: 1,
                    mty_45: 1,
                },
                {
                    contract_os_port_id: '',
                    charge_type: 'OWS',
                    charge_type_category: 'surcharge',
                    unit: null,
                    ldn_20: 1,
                    ldn_40: 1,
                    ldn_45: 1,
                    mty_20: 1,
                    mty_40: 1,
                    mty_45: 1,
                },
            ],
        }],
    });
    const globalOsContractSurcharges = ref([
        {
            contract_os_port_id: '',
            charge_type: 'DG-1',
            charge_type_category: 'surcharge',
            unit: '',
            ldn_20: 1,
            ldn_40: 1,
            ldn_45: 1,
            mty_20: 1,
            mty_40: 1,
            mty_45: 1,
        },
        {
            contract_os_port_id: '',
            charge_type: 'DG-2',
            charge_type_category: 'surcharge',
            unit: '',
            ldn_20: 1,
            ldn_40: 1,
            ldn_45: 1,
            mty_20: 1,
            mty_40: 1,
            mty_45: 1,
        },
        {
            contract_os_port_id: '',
            charge_type: 'DG-3',
            charge_type_category: 'surcharge',
            unit: '',
            ldn_20: 1,
            ldn_40: 1,
            ldn_45: 1,
            mty_20: 1,
            mty_40: 1,
            mty_45: 1,
        },
        {
            contract_os_port_id: '',
            charge_type: 'DG-C',
            charge_type_category: 'surcharge',
            unit: '',
            ldn_20: 1,
            ldn_40: 1,
            ldn_45: 1,
            mty_20: 1,
            mty_40: 1,
            mty_45: 1,
        },
        {
            contract_os_port_id: '',
            charge_type: 'RF',
            charge_type_category: 'surcharge',
            unit: '',
            ldn_20: 1,
            ldn_40: 1,
            ldn_45: 1,
            mty_20: 1,
            mty_40: 1,
            mty_45: 1,
        },
        {
            contract_os_port_id: '',
            charge_type: 'TK',
            charge_type_category: 'surcharge',
            unit: '',
            ldn_20: 1,
            ldn_40: 1,
            ldn_45: 1,
            mty_20: 1,
            mty_40: 1,
            mty_45: 1,
        },
        {
            contract_os_port_id: '',
            charge_type: 'OWS',
            charge_type_category: 'surcharge',
            unit: '',
            ldn_20: 1,
            ldn_40: 1,
            ldn_45: 1,
            mty_20: 1,
            mty_40: 1,
            mty_45: 1,
        },
    ]);
    const errors = ref({});
    const isLoading = ref(false);
    const page = ref('');

    function getContracts() {
        NProgress.start();
        isLoading.value = true;

        Api.get("commercial/contracts")
            .then((response) => {
                contracts.value = response.data.value;
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    async function storeContract(form) {
        NProgress.start();
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            for (let i = 0; i < form.attachment.length; i++) {
                formData.append('attachment[]', form.attachment[i]);
            }
        }

        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post('commercial/open-contracts', formData);
            contract.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "commercial.contracts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }

    }

    function showContract(contractId) {
        NProgress.start();
        isLoading.value = true;

        Api.get(`commercial/open-contracts/${contractId}`)
            .then((response) => {
                contract.value = response.data.value;
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    async function updateContract(form, contractId) {
        NProgress.start();
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            for (let i = 0; i < form.attachment.length; i++) {
                formData.append('attachment[]', form.attachment[i]);
            }
        }

        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post('commercial/open-contracts/update', formData);
            contract.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "commercial.contracts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }

    }

    function copyOsContract(contractId) {
        if (!confirm('Are you sure you want to copy this Contract?')) {
            return;
        }

        NProgress.start();
        isLoading.value = true;

        Api.get(`commercial/open-contracts/copy/${contractId}`)
            .then((response) => {
                contract.value = response.data.value;
                router.push({name: 'commercial.open-slot-contract.edit', params: { contractId: contract.value.id }});
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    function deleteOpenSlotContract(contractId) {
        if (!confirm('Are you sure you want to delete this Contract?')) {
            return;
        }

        NProgress.start();
        isLoading.value = true;

        Api.delete(`commercial/open-contracts/${contractId}`)
            .then(() => {
                router.go('commercial.contracts.index');
                //getContracts();
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    return {
        contracts,
        contract,
        copyOsContract,
        getContracts,
        storeContract,
        showContract,
        updateContract,
        deleteOpenSlotContract,
        globalOsContractSurcharges,
        isLoading,
        errors,
    };
}
