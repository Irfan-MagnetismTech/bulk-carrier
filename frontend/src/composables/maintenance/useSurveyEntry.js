import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useSurveyEntry() {
    const router = useRouter();
    const surveyEntries = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const surveyEntry = ref({
        ops_vessel_id: '',
        ops_vessel: '',
        mnt_survey_item_id: '',
        mnt_survey_item: '',
        mnt_survey_type_id: '',
        mnt_survey_type: '',
        // short_code: '',
        // survey_name: '',
        mnt_survey_id: '',
        mnt_survey: '',
        range_date_from: '',
        range_date_to: '',
        assigned_date: '',
        due_date: '',
        business_unit: '',
        mnt_surveys: [],
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getSurveyEntries(filterOptions) {
        let loader = null;
        if (!filterOptions.isFilter) {
            loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;
            isTableLoading.value = false;
        }
        else {
            isTableLoading.value = true;
            isLoading.value = false;
            loader?.hide();
        }

        filterParams.value = filterOptions;

        try {
            const {data, status} = await Api.get('/mnt/survey-entries',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            surveyEntries.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            //NProgress.done();
            if (!filterOptions.isFilter) {
                loader?.hide();
                isLoading.value = false;
            }
            else {
                isTableLoading.value = false;
                loader?.hide();
            }
        }
    }

    async function storeSurveyEntry(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/survey-entries', form);
            surveyEntry.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.survey-entries.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showSurveyEntry(surveyId) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/survey-entries/${surveyId}`);
            surveyEntry.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function updateSurveyEntry(form, surveyId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/mnt/survey-entries/${surveyId}`,
                form
            );
            surveyEntry.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.survey-entries.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteSurveyEntry(surveyId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/mnt/survey-entries/${surveyId}`);
            notification.showSuccess(status);
            await getSurveyEntries(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            // notification.showError(status);
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    

    
    return {
        surveyEntries,
        surveyEntry,
        getSurveyEntries,
        storeSurveyEntry,
        showSurveyEntry,
        updateSurveyEntry,
        deleteSurveyEntry,
        isLoading,
        isTableLoading,
        errors,
    };
}
