import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useCrewProfile() {
    const router = useRouter();
    const crewProfiles = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const crewProfile = ref( {
        business_unit: '',
        crw_recruitment_approval_id: '',
        crw_recruitment_approval_name: '',
        hired_by: '',
        agency_id: '',
        agency_name: '',
        rank_id: '',
        department_id: '',
        first_name: '',
        last_name: '',
        father_name: '',
        mother_name: '',
        date_of_birth: '',
        gender: '',
        religion: '',
        marital_status: '',
        nationality: 'Bangladeshi',
        nid_no: '',
        passport_no: '',
        passport_issue_date: '',
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

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getCrewProfiles(page,businessUnit) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/crw/crw-crew-profiles',{
                params: {
                    page: page || 1,
                    business_unit: businessUnit,
                },
            });
            crewProfiles.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeCrewProfile(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let formData = new FormData();
        formData.append('attachment', form.attachment);
        formData.append('picture', form.picture);
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

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let formData = new FormData();
        formData.append('attachment', form.attachment);
        formData.append('picture', form.picture);
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(
                `/crw/crw-crew-profiles/${profileId}`,
                formData
            );
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

    async function deleteCrewProfile(profileId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-crew-profiles/${profileId}`);
            notification.showSuccess(status);
            await getCrewProfiles(indexPage.value, indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        crewProfiles,
        crewProfile,
        getCrewProfiles,
        storeCrewProfile,
        showCrewProfile,
        updateCrewProfile,
        deleteCrewProfile,
        isLoading,
        errors,
    };
}
