import { useLoading } from "vue-loading-overlay";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from "../../composables/useNotification.js";

export default function useCrewAssign() {
    const router = useRouter();
    const crewAssigns = ref([]);
    const isTableLoading = ref(false);
    const isCrewUpdateStatusModalOpen = ref(0);
    const $loading = useLoading();
    const notification = useNotification();
    const crewAssign = ref({
        business_unit: "",
        ops_vessel_id: "",
        ops_vessel_name: "",
        // ops_vessel_flag: "",
        crw_crew_id: "",
        crw_crew_name: "",
        crw_crew_contact: "",
        position_onboard: "",
        joining_date: "",
        joining_port_code: "",
        joining_port_name: "",
        is_watchkeeper: "0",
        duration: "",
        remarks: "",
    });

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);

    async function getCrewAssigns(filterOptions) {
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
            const { data, status } = await Api.get(
                "/crw/crw-crew-assignments",
                {
                    params: {
                        page: filterOptions.page,
                        items_per_page: filterOptions.items_per_page,
                        data: JSON.stringify(filterOptions),
                    },
                }
            );
            crewAssigns.value = data.value;
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

    async function storeCrewAssign(form) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.post(
                "/crw/crw-crew-assignments",
                form
            );
            crewAssign.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crewAssigns.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCrewAssign(crewAssignId) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(
                `/crw/crw-crew-assignments/${crewAssignId}`
            );
            crewAssign.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCrewAssign(form, crewAssignId) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/crw/crw-crew-assignments/${crewAssignId}`, form);
            crewAssign.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crewAssigns.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCrewAssign(crewAssignId) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete(
                `/crw/crw-crew-assignments/${crewAssignId}`
            );
            notification.showSuccess(status);
            await getCrewAssigns(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCrewAssignStatus(form,crewAssigns) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post(`/crw/update-crew-assign-status/${form.id}`, form);
            let crewDoc = crewAssigns.find(doc => doc.id === form.id);
            crewDoc.status = data.value.status;
            isCrewUpdateStatusModalOpen.value = 0;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }

    }

    return {
        crewAssigns,
        crewAssign,
        getCrewAssigns,
        storeCrewAssign,
        showCrewAssign,
        updateCrewAssign,
        deleteCrewAssign,
        isCrewUpdateStatusModalOpen,
        updateCrewAssignStatus,
        isTableLoading,
        isLoading,
        errors,
    };
}
