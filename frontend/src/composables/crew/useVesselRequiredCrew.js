import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useVesselRequiredCrew() {
    const router = useRouter();
    const vesselRequiredCrews = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const vesselRequiredCrew = ref( {
        ops_vessel_id: '',
        ops_vessel_name: '',
        total_crew: '',
        effective_date: '',
        remarks: '',
        business_unit: '',
        crwVesselRequiredCrewLines: [
            {
                crw_rank_id: '',
                required_manpower: '',
                eligibility: '',
                remarks: '',
            }
        ]
    });

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getVesselRequiredCrews(page,businessUnit) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/crw/crw-vessel-required-crews',{
                params: {
                    page: page || 1,
                    business_unit: businessUnit,
                },
            });
            vesselRequiredCrews.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeVesselRequiredCrew(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/crw-vessel-required-crews', form);
            vesselRequiredCrew.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.vesselRequiredCrews.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showVesselRequiredCrew(VesselRequiredCrewId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-vessel-required-crews/${VesselRequiredCrewId}`);
            vesselRequiredCrew.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateVesselRequiredCrew(form, VesselRequiredCrewId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/crw/crw-vessel-required-crews/${VesselRequiredCrewId}`,
                form
            );
            vesselRequiredCrew.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.vesselRequiredCrews.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteVesselRequiredCrew(VesselRequiredCrewId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-vessel-required-crews/${VesselRequiredCrewId}`);
            notification.showSuccess(status);
            await getVesselRequiredCrews(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        vesselRequiredCrews,
        vesselRequiredCrew,
        getVesselRequiredCrews,
        storeVesselRequiredCrew,
        showVesselRequiredCrew,
        updateVesselRequiredCrew,
        deleteVesselRequiredCrew,
        isLoading,
        errors,
    };
}
