import { useLoading } from "vue-loading-overlay";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from "../useNotification.js";

export default function useCrwAttendance() {
    const router = useRouter();
    const crwAttendances = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const crwAttendance = ref({
        ops_vessel_id: "",
        year_month: "",
        working_days: "",
        business_unit: "",
        crwAttendanceLines: [            
            {
                crw_crew_id : "",
                crw_crew_name : "",
                crw_crew_assignment_id : "",
                // attendance_line_composite : "",
                present_days : "",
                absent_days : "",
                payable_days : "",
            },
        ],
    });

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);

    async function getCrwAttendances(filterOptions) {
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
            const { data, status } = await Api.get("/crw/crw-attendances", {
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions),
                },
            });
            crwAttendances.value = data.value;
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

    async function storeCrwAttendance(form) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.post("/crw/crw-attendances", form);
            crwAttendance.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crwAttendances.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCrwAttendance(crwAttendanceId) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-attendances/${crwAttendanceId}`);
            crwAttendance.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCrwAttendance(form, crwAttendanceId) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/crw/crw-attendances/${crwAttendanceId}`, form);
            crwAttendance.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crwAttendances.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCrwAttendance(crwAttendanceId) {
        const loader = $loading.show({
            "can-cancel": false,
            loader: "dots",
            color: "#7e3af2",
        });
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete(`/crw/crw-attendances/${crwAttendanceId}`);
            notification.showSuccess(status);
            await getCrwAttendances(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        crwAttendances,
        crwAttendance,
        getCrwAttendances,
        storeCrwAttendance,
        showCrwAttendance,
        updateCrwAttendance,
        deleteCrwAttendance,
        isTableLoading,
        isLoading,
        errors,
    };
}
