import NProgress from "nprogress";
import { reactive, ref } from "vue";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';


export default function useLoadList() {
    const loadList = reactive({
        file: null,
        start: 1,
        filetype: '',
        voyage_id: null,
    });
    const allLoadLists = ref([]);
    const totalContainer = ref (null)
    const totalDg = ref (null)
    const totalRefer = ref (null)
    const notification = useNotification();
    const errors = ref(null);
    const isLoading = ref(false);

    async function process(loadList) {
        NProgress.start();
        isLoading.value = true;

        try {
            if(loadList.filetype){
                loadList.tdr_status = 'final';
            }else{
                loadList.tdr_status = '';
            }

            let formData = new FormData();
            formData.append("file", loadList.file);
            formData.append("start", loadList.start);
            formData.append("voyage_id", loadList.voyage_id);
            formData.append("file_type", loadList.tdr_status);
            formData.append("forceDuplicateStowage", loadList.forceDuplicateStowage);

            let apiUrl = '';

            if(loadList.tdr_status == 'final') {
                apiUrl = '/operation/final-tdr';
            } else {
                apiUrl = '/operation/loadlist';
            }

            const { data, status } = await Api.post(apiUrl, formData);
    
            allLoadLists.value = data.value;
            totalContainer.value = data.value.total;
            totalDg.value = data.value.dg;
            totalRefer.value = data.value.refer;
            notification.showSuccess(status,"Load list uploaded successfully");
        } catch (error) {
            const { data, status } = error.response;
            data.errors = [data.message, data.type, data.value];
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function quickAgentEntry(agentCodes)
    {
        NProgress.start();
        isLoading.value = true;

        try {

            const { data, status } = await Api.post('/operation/quick-agent-entry', agentCodes);
            notification.showSuccess(status, "Local Agent Created Successfully. Please Reupload your TDR.");
        } catch (error) {
            const { data, status } = error.response;
            data.errors = [data.message, data.type, data.value];
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function updateLoadList(form,voyageId, type) {

        try {
            const { data, status } = await Api.post(
                `/operation/update-loadlist/${voyageId}`,
                {
                    data: form,
                    type: type
                }
            );
            allLoadLists.value = data.value;
            notification.showSuccess(status,"Load list updated successfully");
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        loadList,
        allLoadLists,
        totalContainer,
        totalDg,
        totalRefer,
        process,
        quickAgentEntry,
        updateLoadList,
        isLoading,
        errors,
    };
}
