import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from "../useNotification";

export default function useMloAgent() {
    const router = useRouter();
    const notification = useNotification();
    const $loading = useLoading();
    const agents = ref([]);
    const agent = ref({
        name: '',
        code: '',
        port: '',
        port_origin_name: '',
        address: '',
        export_email: '',
        export_cc_email: '',
        import_email: '',
        import_cc_email: '',
        hrlines_cc_email: ''
    });
    const errors = ref(null);
    const isLoading = ref(false);

    async function getAgents(page, columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/operation/mlo-agents', {
                params: {
                    page: page || 1,
                    columns: columns || null,
                    searchKey: searchKey || null,
                    table: table || null,
                },
            });
            agents.value = data.value;
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

    async function storeAgent(form) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/operation/mlo-agents', form);
            agents.value = data.value;
            notification.showSuccess(status);
            router.push({ name: 'operation.mlo.agents.index' });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function showAgent(agentId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/operation/mlo-agents/${agentId}`);
            agent.value = data.value;
            agent.value.port_origin_name = data.value.port
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

    async function updateAgent(form, agentId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/operation/mlo-agents/${agentId}`,
                form
            );
            notification.showSuccess(status);
            router.push({ name: 'operation.mlo.agents.index' });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteAgent(agentId) {
        if (!confirm('Are you sure you want to delete this agent?')) {
            return;
        }
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        //NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete(
                `/operation/mlo-agents/${agentId}`
            );
            notification.showSuccess(status);
            await getAgents();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        agents,
        agent,
        getAgents,
        storeAgent,
        showAgent,
        updateAgent,
        deleteAgent,
        isLoading,
        errors,
    };
}
