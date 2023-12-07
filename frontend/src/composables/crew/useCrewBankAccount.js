import { useLoading } from "vue-loading-overlay";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from "../useNotification.js";

export default function useCrewBankAccount() {
    const router = useRouter();
    const crewBankAccounts = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const crewBankAccount = ref({
        crw_crew_id : "", 
        crw_crew_name: "",
        bank_name : "", 
        branch_name : "", 
        routing_number : "", 
        account_name : "", 
        account_number : "", 
        benificiary_name : "", 
        attachment : "", 
        is_active : "", 
        business_unit : "", 
    });

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);

    async function getCrewBankAccounts(filterOptions) {
        let loader = null;
        if (!filterOptions.isFilter) {
            loader = $loading.show({
                "can-cancel": false,
                loader: "dots",
                color: "#7e3af2",
            });
            isLoading.value = true;
            isTableLoading.value = false;
        } else {
            isTableLoading.value = true;
            isLoading.value = false;
            loader?.hide();
        }

        filterParams.value = filterOptions;

        try {
            const { data, status } = await Api.get("/crw/crw-bank-accounts",
                {
                    params: {
                        page: filterOptions.page,
                        items_per_page: filterOptions.items_per_page,
                        data: JSON.stringify(filterOptions),
                    },
                }
            );
            crewBankAccounts.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            if (!filterOptions.isFilter) {
                loader?.hide();
                isLoading.value = false;
            } else {
                isTableLoading.value = false;
                loader?.hide();
            }
        }
    }

    async function storeCrewBankAccount(form) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.post("/crw/crw-bank-accounts", form);
            crewBankAccount.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crewBankAccounts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCrewBankAccount(crewBankAccountId) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-bank-accounts/${crewBankAccountId}`
            );
            crewBankAccount.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCrewBankAccount(form, crewBankAccountId) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/crw/crw-bank-accounts/${crewBankAccountId}`, form);
            crewBankAccount.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crewBankAccounts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCrewBankAccount(crewBankAccountId) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete(`/crw/crw-bank-accounts/${crewBankAccountId}`);
            notification.showSuccess(status);
            await getCrewBankAccounts(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        crewBankAccounts,
        crewBankAccount,
        getCrewBankAccounts,
        storeCrewBankAccount,
        showCrewBankAccount,
        updateCrewBankAccount,
        deleteCrewBankAccount,
        isTableLoading,
        isLoading,
        errors,
    };
}
