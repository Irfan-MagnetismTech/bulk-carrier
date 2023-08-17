import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../../composables/useNotification.js';

export default function useMailTemplate () {
	const router = useRouter();
	const notification = useNotification();
    const $loading = useLoading();
    
    const isLoading = ref(false);
    const errors = ref(null);
    const templates = ref([]);
    const mailTemplate = ref({
        type: '',
        port: '',
        port_origin_name: '',
        title: '',
        status: '',
        body: '',
        created_by: ''
    });

    async function getTemplate() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/dataencoding/mail-templates');
            templates.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
           // NProgress.done();
        }
    }

    async function showMailTemplate(templateId)
    {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/dataencoding/mail-templates/' + templateId);
            mailTemplate.value = data.value;
            mailTemplate.value.port_origin_name = data.value.port
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function storeMailTemplate() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const requestData = mailTemplate.value
            const { data, status } = await Api.post('/dataencoding/mail-templates', requestData);
            notification.showSuccess(status);
			router.push({ name: 'dataencoding.mail-templates.index' });

        } catch (error) {
            const { data, status } = error.response;
			errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
           // NProgress.done();
            
        }
    }

    const updateMailTemplate = async () => {
       // NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const requestData = mailTemplate.value
            const { data, status } = await Api.put('/dataencoding/mail-templates/' + requestData.id, requestData);
            notification.showSuccess(status);
            router.push({ name: 'dataencoding.mail-templates.index' });
        } catch (error) {

        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteTemplate(templateId)
    {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        if (!confirm('Are you sure you want to delete this template?')) {
            return;
        }

        try {
            const { data, status } = await Api.delete('/dataencoding/mail-templates/' + templateId);
            notification.showSuccess(status);
            await getTemplate();
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        templates,
        notification,
        mailTemplate,
        getTemplate,
        deleteTemplate,
        isLoading,
        errors,
        showMailTemplate,
        storeMailTemplate,
        updateMailTemplate
    };
}