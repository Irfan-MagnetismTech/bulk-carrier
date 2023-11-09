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
	const errors = ref(null);
	const isLoading = ref(false);

	async function getVoyageBoatNotes(page, businessUnit) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/ops/voyage-boat-notes', {
				params: {
					page: page || 1,
					business_unit: businessUnit
				}
			});
			voyageBoatNotes.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			//notification.showError(status);
		} finally {
			//NProgress.done();
			loader.hide();
			isLoading.value = false;
		}
	}

	async function storeVoyageBoatNote(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			let formData = new FormData();
			
			form.opsVoyageBoatNoteLines.map((element, index) => {
				if(form.type == 'Boat Note') {
					element.boat_note_qty = element.quantity
				} else if(form.type == 'Final Survey') {
					element.final_survey_qty = element.quantity
				} else if(form.type == 'Receipt Copy') {
					element.final_received_qty = element.quantity
				}

				element.quantity = null;
				formData.append('attachments['+index+']', element.attachment ?? null);


			})


			formData.append('info', JSON.stringify(form));

			const { data, status } = await Api.post('/ops/voyage-boat-notes', formData);
			notification.showSuccess(status);
			// router.push({ name: 'ops.voyage-boat-notes.index' });
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
			formData.append('attachment', form.attachment);
			formData.append('info', JSON.stringify(form));
			formData.append('_method', 'PUT');

			const { data, status } = await Api.post(
				`/ops/voyage-boat-notes/${voyageBoatNoteId}`,
				formData
			);
			// voyageBoatNote.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.voyage-boat-notes.index' });
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
			await getVoyageBoatNotes();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
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
		errors,
	};
}
