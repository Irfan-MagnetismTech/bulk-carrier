import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useCrewProfile() {
    const router = useRouter();
    const crewProfiles = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const crewProfile = ref( {
        business_unit: '',
        crw_recruitment_approval_id: '',
        crw_recruitment_approval_name: null,
        hired_by: '',
        agency_id: null,
        agency_name: null,
        rank_id: '',
        crw_rank_id: '',
        department_name: '',
        first_name: '',
        last_name: '',
        father_name: '',
        mother_name: '',
        date_of_birth: null,
        gender: '',
        religion: '',
        marital_status: '',
        nationality: 'Bangladeshi',
        nid_no: '',
        passport_no: '',
        passport_issue_date: null,
        blood_group: '',
        height: '',
        weight: '',
        pre_address: '',
        pre_city: '',
        pre_mobile_no: '',
        pre_email: '',
        per_address: '',
        per_city: '',
        per_mobile_no: '',
        per_email: '',
        picture: '',
        attachment: '',
        employee_type: 'Crew',
        is_officer: 0,
        is_training_data_required: true,
        is_experience_data_required: true,
        is_other_data_required: true,
        is_reference_data_required: true,
        is_nominee_data_required: true,
        educations: [
            {
                exam_title: '',
                major: '',
                institute: '',
                result: '',
                passing_year: '',
                duration: '',
                achievement: '',
            }
        ],
        trainings: [
            {
                training_title: '',
                covered_topic: '',
                year: '',
                institute: '',
                duration: '',
                location: '',
            }
        ],
        experiences: [
            {
                employer_name: '',
                from_date: '',
                till_date: '',
                last_designation: '',
                reason_for_leave: '',
            }
        ],
        languages: [
            {
                language_name: '',
                writing: '',
                reading: '',
                speaking: '',
                listening: '',
            }
        ],
        references: [
            {
                name: '',
                organization: '',
                designation: '',
                address: '',
                contact_office: '',
                contact_personal: '',
                email: '',
                relation: '',
            }
        ],
        nominees: [
            {
                name: '',
                profession: '',
                address: '',
                relationship: '',
                contact_no: '',
                email: '',
                is_relative: '',
            }
        ],
    });

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);

    async function getCrewProfiles(filterOptions) {

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
            const {data, status} = await Api.get('/crw/crw-crew-profiles',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            crewProfiles.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
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

    async function storeCrewProfile(form) {

        if(!form.is_training_data_required){
            form.trainings = [];
        }
        if(!form.is_experience_data_required){
            form.experiences = [];
        }
        if(!form.is_other_data_required){
            form.languages = [];
        }
        if(!form.is_reference_data_required){
            form.references = [];
        }
        if(!form.is_nominee_data_required){
            form.nominees = [];
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let formData = new FormData();

        if(form.attachment){
            formData.append('attachment', form.attachment);
        }
        if(form.picture){
            formData.append('picture', form.picture);
        }

        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post('/crw/crw-crew-profiles', formData);
            crewProfile.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.profiles.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCrewProfile(profileId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-crew-profiles/${profileId}`);
            crewProfile.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCrewProfile(form, profileId) {

        if(!form.is_training_data_required){
            form.trainings = [];
        }
        if(!form.is_experience_data_required){
            form.experiences = [];
        }
        if(!form.is_other_data_required){
            form.languages = [];
        }
        if(!form.is_reference_data_required){
            form.references = [];
        }
        if(!form.is_nominee_data_required){
            form.nominees = [];
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            formData.append('attachment', form.attachment);
        }
        if(form.picture){
            formData.append('picture', form.picture);
        }
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(
                `/crw/crw-crew-profiles/${profileId}`,
                formData
            );
            notification.showSuccess(status);
            await router.push({ name: "crw.profiles.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCrewProfile(profileId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-crew-profiles/${profileId}`);
            notification.showSuccess(status);
            await getCrewProfiles(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    function checkValidation(openTab, tabNumber, props,requiredFields){
        //const requiredFields = ['first_name'];
        for (const field of requiredFields) {
            const element = document.getElementById(field);
            if (openTab.value === 1) {
                if(!props.form[field]){
                    notification.showError(422, '', 'Please fill all required fields');
                    return false;
                }
            }
            if(openTab.value === 2){
                let educationFieldStatus = true;
                props.form.educations.forEach((value, index) => {
                    if(!props.form.educations[index][field]){
                        educationFieldStatus = false;
                    }
                });
                if(!educationFieldStatus){
                    notification.showError(422, '', 'Please fill all required fields');
                    return false;
                }
            }
            if(openTab.value === 3){
                let trainingFieldStatus = true;
                props.form.trainings.forEach((value, index) => {
                    if(!props.form.trainings[index][field]){
                        trainingFieldStatus = false;
                    }
                });
                if(!trainingFieldStatus){
                    notification.showError(422, '', 'Please fill all required fields');
                    return false;
                }
            }
            if(openTab.value === 4){
                let experienceFieldStatus = true;
                props.form.experiences.forEach((value, index) => {
                    if(!props.form.experiences[index][field]){
                        experienceFieldStatus = false;
                    }
                });
                if(!experienceFieldStatus){
                    notification.showError(422, '', 'Please fill all required fields');
                    return false;
                }
            }
            if(openTab.value === 5){
                let otherFieldStatus = true;
                props.form.languages.forEach((value, index) => {
                    if(!props.form.languages[index][field]){
                        otherFieldStatus = false;
                    }
                });
                if(!otherFieldStatus){
                    notification.showError(422, '', 'Please fill all required fields');
                    return false;
                }
            }
            if(openTab.value === 6){
                let referenceFieldStatus = true;
                props.form.references.forEach((value, index) => {
                    if(!props.form.references[index][field]){
                        referenceFieldStatus = false;
                    }
                });
                if(!referenceFieldStatus){
                    notification.showError(422, '', 'Please fill all required fields');
                    return false;
                }
            }
            if(openTab.value === 7){
                let nomineesFieldStatus = true;
                props.form.nominees.forEach((value, index) => {
                    if(!props.form.nominees[index][field]){
                        nomineesFieldStatus = false;
                    }
                });
                if(!nomineesFieldStatus){
                    notification.showError(422, '', 'Please fill all required fields');
                    return false;
                }
            }
        }

        return true;
    }

    return {
        crewProfiles,
        crewProfile,
        getCrewProfiles,
        storeCrewProfile,
        showCrewProfile,
        updateCrewProfile,
        deleteCrewProfile,
        checkValidation,
        isTableLoading,
        isLoading,
        errors,
    };
}
