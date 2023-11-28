import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';

export default function useVoyageBoatNote() {
	const router = useRouter();
	const voyageBoatNotes = ref([]);
	const $loading = useLoading();
	const notification = useNotification();

	const voyageBoatNote = ref({
		
		business_unit: '',
		ops_voyage_id: '',
		ops_vessel_id: '',
		type: '',
		vessel_draft: '',
		water_density: '',
		opsVoyage: '',
		opsVoyageBoatNoteLines: []
	});
	const filterParams = ref(null);
	const errors = ref(null);
	const isLoading = ref(false);
	const isTableLoading = ref(false);

	async function getVoyageBoatNotes(filterOptions) {
		//NProgress.start();
		// const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;
        let loader = null;
        if (!filterOptions.isFilter) {
            loader = $loading.show({ 'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2' });
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
			const { data, status } = await Api.get('/ops/voyage-boat-notes', {
				params: {
					page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
				}
			});
			voyageBoatNotes.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			//notification.showError(status);
		} finally {
			//NProgress.done();
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

	async function storeVoyageBoatNote(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			let formData = new FormData();
			
			form.opsVoyageBoatNoteLines.map((element, index) => {
				formData.append('attachments['+index+']', element.attachment ?? null);
				element.attachment = null;
			})


			formData.append('info', JSON.stringify(form));

			const { data, status } = await Api.post('/ops/voyage-boat-notes', formData);
			notification.showSuccess(status);
			await router.push({ name: 'ops.voyage-boat-notes.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showVoyageBoatNote(voyageBoatNoteId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/voyage-boat-notes/${voyageBoatNoteId}`);
			voyageBoatNote.value = data.value;
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

	async function updateVoyageBoatNote(form, voyageBoatNoteId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			let formData = new FormData();
			
			form.opsVoyageBoatNoteLines.map((element, index) => {
				formData.append('attachments['+index+']', element.attachment ?? null);
				element.attachment = null;
			})


			formData.append('info', JSON.stringify(form));
			formData.append('_method', 'PUT');

			const { data, status } = await Api.post(
				`/ops/voyage-boat-notes/${voyageBoatNoteId}`,
				formData
			);
			// voyageBoatNote.value = data.value;
			notification.showSuccess(status);
			await router.push({ name: 'ops.voyage-boat-notes.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteVoyageBoatNote(voyageBoatNoteId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/voyage-boat-notes/${voyageBoatNoteId}`);
			notification.showSuccess(status);
			await getVoyageBoatNotes(filterParams.value);
		} catch (error) {
			const { data, status } = error.response;
			// notification.showError(status);
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchVoyageBoatNotes(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-voyage-boat-notes?name=${searchParam}`);
			voyageBoatNotes.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loading(false)
			//NProgress.done();
		}
	}

	return {
		voyageBoatNotes,
		voyageBoatNote,
		getVoyageBoatNotes,
		storeVoyageBoatNote,
		showVoyageBoatNote,
		updateVoyageBoatNote,
		deleteVoyageBoatNote,
		searchVoyageBoatNotes,
		isLoading,
		isTableLoading,
		errors,
	};
}
