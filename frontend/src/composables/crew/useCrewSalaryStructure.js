import { useLoading } from "vue-loading-overlay";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from "../useNotification.js";

export default function useCrewSalaryStructure() {
    const router = useRouter();
    const crewSalaryStructures = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const crewSalaryStructure = ref({
        crw_crew_id : "",
        promotion_id : "",
        increment_sequence : "",
        crw_crew_name: "",
        crw_crew_contact: "",
        effective_date: "",
        currency: "BDT",
        gross_salary: "",
        addition: "",
        deduction: "",
        net_amount: "",
        business_unit : "",
        remarks: "",
        is_active: 1,
    });

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);

    async function getCrewSalaryStructures(filterOptions) {
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
            const { data, status } = await Api.get("/crw/crw-salary-structures",
                {
                    params: {
                        page: filterOptions.page,
                        items_per_page: filterOptions.items_per_page,
                        data: JSON.stringify(filterOptions),
                    },
                }
            );
            crewSalaryStructures.value = data.value;
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

    async function storeCrewSalaryStructure(form) {
        const loader = $loading.show({"can-cancel": false, loader: "dots", color: "#7e3af2"});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post("/crw/crw-salary-structures", form);
            crewSalaryStructure.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crewSalaryStructures.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCrewSalaryStructure(crewSalaryStructureId) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-salary-structures/${crewSalaryStructureId}`
            );
            crewSalaryStructure.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCrewSalaryStructure(form, crewSalaryStructureId) {
        const loader = $loading.show({ "can-cancel": false, loader: "dots", color: "#7e3af2" });
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/crw/crw-salary-structures/${crewSalaryStructureId}`, form);
            crewSalaryStructure.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crewSalaryStructures.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCrewSalaryStructure(crewSalaryStructureId) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete(`/crw/crw-salary-structures/${crewSalaryStructureId}`);
            notification.showSuccess(status);
            await getCrewSalaryStructures(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        crewSalaryStructures,
        crewSalaryStructure,
        getCrewSalaryStructures,
        storeCrewSalaryStructure,
        showCrewSalaryStructure,
        updateCrewSalaryStructure,
        deleteCrewSalaryStructure,
        isTableLoading,
        isLoading,
        errors,
    };
}
