import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useAdvisory() {
    const router = useRouter();
    const $loading = useLoading();
    const outboxMails = ref([]);
    const notification = useNotification();
    const advisoryFormParams = ref( {
        voyage_id: '',
        type: '',
    });

    const errors = ref(null);
    const isLoading = ref(false);
    const externalEmail = ref([]);

    async function getOutboxMails(form)
    {
       // NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/operation/voyages/outbox-mails', form);
            outboxMails.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //NProgress.done();
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getHrlinesCC()
    {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/operation/external-emails');
            externalEmail.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //NProgress.done();
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateHrlinesCC(externalEmail)
    {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put('/operation/external-emails/'+externalEmail.id, 
            {
                hrlines_cc_email_advisory: externalEmail.hrlines_cc_email_advisory,
                external_export_receiver: externalEmail.external_export_receiver,
                external_import_receiver: externalEmail.external_import_receiver,
            }
            );
            externalEmail.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //NProgress.done();
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        outboxMails,
        advisoryFormParams,
        getOutboxMails,
        isLoading,
        errors,
        externalEmail,
        updateHrlinesCC,
        getHrlinesCC
    };
}
