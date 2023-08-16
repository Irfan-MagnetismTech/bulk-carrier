import NProgress from "nprogress";
import { reactive, ref } from "vue";
import Api from "../apis/Api";
import useNotification from '../composables/useNotification';


export default function useFileUploader() {
    const filePath = ref(null);
    const uploadedFileId = ref(null);
    const notification = useNotification();
    const errors = ref(null);
    const isLoading = ref(false);

    async function process(file, uploadUrl) {
        NProgress.start();
        isLoading.value = true;
        let formData = new FormData();
        formData.append('file', file);
        axios({
            url: uploadUrl,
            data: formData,
            method: 'POST'
        }).then((response) => {
            // console.log(response.data);
            filePath.value = response.data.file_name;
            uploadedFileId.value = response.data.file_id
        }).catch((error) => {
            console.log(error);
        }).finally(() => {
            NProgress.done();
            isLoading.value = false;
        });
    }

    async function removeFile(fileid, notificationId = null) {
        NProgress.start();
        isLoading.value = true;
        // delete file from server
        await Api.post(`/delete-file`, { id: fileid, notificationId: notificationId })
        .then((response) => {
            filePath.value = null;
        }).catch((error) => {
            console.log(error);
        }).finally(() => {
            NProgress.done();
            isLoading.value = false;
        });
    }

    return {
        process,
        notification,
        errors,
        filePath,
        uploadedFileId,
        removeFile,
        isLoading
    }
}