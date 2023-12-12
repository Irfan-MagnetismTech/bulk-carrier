import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from "sweetalert2";

export default function useRecruitmentApproval() {
    const router = useRouter();
    const recruitmentApprovals = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const recruitmentApproval = ref( {
        applied_date: '',
        page_title: '',
        subject: '',
        total_approved: '',
        crew_agreed_to_join: '',
        crew_selected: '',
        crew_panel: '',
        crew_rest: '',
        body: '',
        remarks: '',
        business_unit: '',
        crwRecruitmentApprovalLines: [
            {
                crw_rank_id: '',
                candidate_name: '',
                candidate_contact: '',
                candidate_email: '',
                remarks: '',
                isRankNameDuplicate: false
            }
        ]
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getRecruitmentApprovals(filterOptions) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;
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
            const {data, status} = await Api.get('/crw/crw-recruitment-approvals',{
                params: {
                   page: filterOptions.page || 1,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            recruitmentApprovals.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
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

    async function storeRecruitmentApproval(form) {

        const isUnique = checkUniqueArray(form);

        if(isUnique){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            try {
                const { data, status } = await Api.post('/crw/crw-recruitment-approvals', form);
                recruitmentApproval.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "crw.recruitmentApprovals.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function showRecruitmentApproval(recruitmentApprovalId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-recruitment-approvals/${recruitmentApprovalId}`);
            recruitmentApproval.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateRecruitmentApproval(form, recruitmentApprovalId) {

        const isUnique = checkUniqueArray(form);

        if(isUnique){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            try {
                const { data, status } = await Api.put(
                    `/crw/crw-recruitment-approvals/${recruitmentApprovalId}`,
                    form
                );
                recruitmentApproval.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "crw.recruitmentApprovals.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function deleteRecruitmentApproval(recruitmentApprovalId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-recruitment-approvals/${recruitmentApprovalId}`);
            notification.showSuccess(status);
            await getRecruitmentApprovals(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    function checkUniqueArray(form){
        const itemNamesSet = new Set();
        let isHasError = false;
        const messages = ref([]);
        const hasDuplicates = form.crwRecruitmentApprovalLines.some((item,index) => {
            if (itemNamesSet.has(item.crw_rank_id)) {
                let data = `Duplicate Rank Name [line no: ${index + 1}]`;
                messages.value.push(data);
                form.crwRecruitmentApprovalLines[index].isRankNameDuplicate = true;
            } else {
                form.crwRecruitmentApprovalLines[index].isRankNameDuplicate = false;
            }
            itemNamesSet.add(item.crw_rank_id);
        });

        if (messages.value.length > 0) {
            let rawHtml = ` <ul class="text-left list-disc text-red-500 mb-3 px-5 text-base"> `;
            if (Object.keys(messages.value).length) {
                for (const property in messages.value) {
                    rawHtml += `<li> ${messages.value[property]} </li>`
                }
                rawHtml += `</ul>`;

                Swal.fire({
                    icon: "",
                    title: "Correct Please!",
                    html: `
                ${rawHtml}
                        `,
                    customClass: "swal-width",
                });
                return false;
            }
        } else {
            return true;
        }
    }

    return {
        recruitmentApprovals,
        recruitmentApproval,
        getRecruitmentApprovals,
        storeRecruitmentApproval,
        showRecruitmentApproval,
        updateRecruitmentApproval,
        deleteRecruitmentApproval,
        checkUniqueArray,
        isLoading,
        isTableLoading,
        errors,
    };
}
