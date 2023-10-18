import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useCommonApiRequest() {
    const router = useRouter();
    const crwRankLists = ref([]);
    const crwAgencies = ref([]);
    const crwAgencyContracts = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const errors = ref(null);
    const isLoading = ref(false);

    async function getCrewRankLists(businessUnit) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let form = {
            'business_unit': businessUnit,
        }

        try {
            const { data, status } = await Api.post('/crw/get-crew-ranks', form);
            crwRankLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getCrewAgencyLists(businessUnit) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let form = {
            'business_unit': businessUnit,
        }

        try {
            const { data, status } = await Api.post('/crw/get-crew-agencies', form);
            crwAgencies.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getCrewAgencyContracts(businessUnit = null, agencyId = null) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let form = {
            'business_unit': businessUnit,
            'crw_agency_id': agencyId,
        }

        try {
            const { data, status } = await Api.post('/crw/get-crew-agency-contracts', form);
            crwAgencyContracts.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        crwRankLists,
        crwAgencies,
        crwAgencyContracts,
        getCrewRankLists,
        getCrewAgencyLists,
        getCrewAgencyContracts,
        isLoading,
        errors,
    };
}
