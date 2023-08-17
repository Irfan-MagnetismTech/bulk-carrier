import NProgress from 'nprogress';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';

export default function useContainer() {
	const container = ref([]);
	const isLoading = ref(false);

    async function showContainer(containerId)
    {
        NProgress.start();
        isLoading.value = true;

        try {
			const { data } = await Api.get(
				`operation/containers/${containerId}`
			);
			container.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
    }

    async function updateContainer(form, containerId)
    {
        NProgress.start();
        isLoading.value = true;

        Api.put(`operation/containers/${containerId}`, form)
			.then(() => {
				console.log("Successfully updated voyage.");
                router.push({ name: "operation.voyages.index" });
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				isLoading.value = false;
				NProgress.done();
			});
    }

    return {
        container,
        showContainer,
        updateContainer,
    }

}