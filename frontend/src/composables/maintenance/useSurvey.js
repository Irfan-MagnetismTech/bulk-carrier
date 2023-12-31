import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useSurvey() {
    const router = useRouter();
    const surveys = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const survey = ref({
        ops_vessel_id: '',
        ops_vessel: '',
        mnt_survey_item_id: '',
        mnt_survey_item: '',
        mnt_survey_type_id: '',
        mnt_survey_type: '',
        short_code: '',
        survey_name: '',
        range_date_from: '',
        range_date_to: '',
        assigned_date: '',
        due_date: '',
        business_unit: '',
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getSurveys(filterOptions) {
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
            const {data, status} = await Api.get('/mnt/surveys',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            surveys.value = data.value;
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

    async function storeSurvey(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/surveys', form);
            survey.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.surveys.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showSurvey(surveyId) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/surveys/${surveyId}`);
            survey.value = data.value;
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

    async function updateSurvey(form, surveyId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/mnt/surveys/${surveyId}`,
                form
            );
            survey.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.surveys.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteSurvey(surveyId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/mnt/surveys/${surveyId}`);
            notification.showSuccess(status);
            await getSurveys(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            // notification.showError(status);
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getSurveysWithoutPagination(mntSurveyItemId, mntSurveyTypeId) {
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/mnt/get-surveys', {
                params: {
                    mnt_survey_type_id: mntSurveyTypeId,
                    mnt_survey_item_id: mntSurveyItemId,
                },
            });
            surveys.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }


    

    
    return {
        surveys,
        survey,
        getSurveys,
        storeSurvey,
        showSurvey,
        updateSurvey,
        deleteSurvey,
        getSurveysWithoutPagination,
        isLoading,
        isTableLoading,
        errors,
    };
}
