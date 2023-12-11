import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from "sweetalert2";

export default function useCheckList() {
    const router = useRouter();
    const checklists = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const duplicateIndexArray = ref([]);
    const checkList = ref( {
        effective_date: '',
        remarks: '',
        business_unit: '',
        crwCrewChecklistLines: [
            {
                item_name: '',
                remarks: '',
            }
        ]
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getCheckLists(filterOptions) {

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
            const {data, status} = await Api.get('/crw/crw-crew-checklists',{
                params: {
                   page: filterOptions.page || 1,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
            checklists.value = data.value;
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

    async function storeCheckList(form) {

        const isUnique = checkUniqueArray(form.crwCrewChecklistLines);
        if(isUnique){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            try {
                const { data, status } = await Api.post('/crw/crw-crew-checklists', form);
                checkList.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "crw.checklists.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function showCheckList(checkListId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-crew-checklists/${checkListId}`);
            checkList.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCheckList(form, checkListId) {

        const isUnique = checkUniqueArray(form.crwCrewChecklistLines);
        if(isUnique){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            try {
                const { data, status } = await Api.put(
                    `/crw/crw-crew-checklists/${checkListId}`,
                    form
                );
                checkList.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "crw.checklists.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function deleteCheckList(checkListId) {

        //const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-crew-checklists/${checkListId}`);
            notification.showSuccess(status);
            await getCheckLists(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //loader.hide();
            isLoading.value = false;
        }
    }

    function checkUniqueArray(lines){
        const itemNamesSet = new Set();
        let isHasError = false;
        const messages = ref([]);
        const hasDuplicates = lines.some((item,index) => {
            if (itemNamesSet.has(item.item_name)) {
                let data = `Duplicate Item Name [line no: ${index + 1}]`;
                duplicateIndexArray.value.push(index);
                messages.value.push(data);
                //return true; // Duplicate found
            }
            itemNamesSet.add(item.item_name);
            //return false; // No duplicate yet
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
                isHasError = true;
            }
        } else {
            return isHasError;
        }
    }

    return {
        checklists,
        checkList,
        getCheckLists,
        storeCheckList,
        showCheckList,
        updateCheckList,
        deleteCheckList,
        isLoading,
        isTableLoading,
        checkUniqueArray,
        duplicateIndexArray,
        errors,
    };
}
