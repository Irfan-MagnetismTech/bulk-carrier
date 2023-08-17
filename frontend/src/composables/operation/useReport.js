import NProgress from 'nprogress';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../../composables/useNotification.js';

export default function useReport() {
    const router = useRouter();
    const reports = ref([]);
    const report = ref(null);
	const notification = useNotification();
	const errors = ref(null);
	const isLoading = ref(false);

	function generateBayPlanPDF(voyage_id, voyageName) {
		NProgress.start();
		isLoading.value = true;
        
		Api.get(`operation/voyages/${voyage_id}/generate-bay-plan-pdf`, {
            responseType: 'blob'
        })
			.then((response) => {
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');
                let dateTime = new Date();
                
                fileLink.href = fileURL;
                fileLink.setAttribute('download', `${voyageName}_BayPlan.pdf`);
                document.body.appendChild(fileLink);
              
                fileLink.click();

				console.log('Bay plan file generated.');
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				isLoading.value = false;
				NProgress.done();
			});
	}

	function generateVoyageEdi(voyage_id, voyageName, ediType, letterCount) {
		NProgress.start();
		isLoading.value = true;

		Api.get(`operation/generate-edi/${voyage_id}/${ediType}/${letterCount}`, {
			responseType: 'blob'
		})
			.then((response) => {
				var fileURL = window.URL.createObjectURL(new Blob([response.data]));
				var fileLink = document.createElement('a');
				let dateTime = new Date();

				fileLink.href = fileURL;
				fileLink.setAttribute('download', `${voyageName}.edi`);
				document.body.appendChild(fileLink);

				fileLink.click();
			})
			.catch((error) => {
				const { data, status } = error.response;
				if(status === 422) {
					data.text().then(text => {
						const response = JSON.parse(text);
						console.log(response.value.invalid);
						let containerNo = response.value.invalid.join(', ');
						alert(response.message +"\n" + "Container Number: "+ "\n" + containerNo);
					});
				}else{
					notification.showError(status);
				}
			})
			.finally(() => {
				isLoading.value = false;
				NProgress.done();
			});
	}

	function generateVoyageExcel(voyage_id, voyageName) {
		NProgress.start();
		isLoading.value = true;

		Api.get(`operation/container-list/${voyage_id}`, {
			responseType: 'blob'
		})
			.then((response) => {
				var fileURL = window.URL.createObjectURL(new Blob([response.data]));
				var fileLink = document.createElement('a');
				let dateTime = new Date();

				fileLink.href = fileURL;
				fileLink.setAttribute('download', `${voyageName}_Containers.xlsx`);
				document.body.appendChild(fileLink);

				fileLink.click();

				console.log('Load List file generated.');
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				isLoading.value = false;
				NProgress.done();
			});
	}

	function generateVoyageSummary(voyage_id, voyageName, tdrType) {
		NProgress.start();
		isLoading.value = true;

		Api.get(`operation/tdr-report/${voyage_id}/${tdrType}`, {
			responseType: 'blob'
		})
			.then((response) => {
				var fileURL = window.URL.createObjectURL(new Blob([response.data]));
				var fileLink = document.createElement('a');
				let dateTime = new Date();

				fileLink.href = fileURL;
				fileLink.setAttribute('download', `${voyageName}.xlsx`);
				document.body.appendChild(fileLink);

				fileLink.click();

				console.log('Voyage Summary file generated.');
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				isLoading.value = false;
				NProgress.done();
			});
	}
	
	function downloadDraftTdr(voyage_id, voyageName) {

		NProgress.start();
		isLoading.value = true;

		Api.get(`operation/download-latest-draft-tdr/${voyage_id}`, {
			responseType: 'blob'
		})
			.then((response) => {
				var fileURL = window.URL.createObjectURL(new Blob([response.data]));
				var fileLink = document.createElement('a');
				let dateTime = new Date();

				fileLink.href = fileURL;
				fileLink.setAttribute('download', `${voyageName}_Draft_TDR_Containers.xlsx`);
				document.body.appendChild(fileLink);

				fileLink.click();

				console.log('Load List file generated.');
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				isLoading.value = false;
				NProgress.done();
			});
	}
	
	function downloadMloAgentList() {
		NProgress.start();
		isLoading.value = true;

		Api.get(`operation/export-mlo-agents`, {
			responseType: 'blob'
		})
			.then((response) => {
				var fileURL = window.URL.createObjectURL(new Blob([response.data]));
				var fileLink = document.createElement('a');
				let dateTime = new Date();

				fileLink.href = fileURL;
				fileLink.setAttribute('download', `MLO-Agent-List.xlsx`);
				document.body.appendChild(fileLink);

				fileLink.click();

				console.log('MLO Agent List Downloaded.');
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				isLoading.value = false;
				NProgress.done();
			});
	}

	function generateCopinoBooking(voyage_id, voyageName) {
		NProgress.start();
		isLoading.value = true;

		Api.get(`operation/copino-booking/${voyage_id}`, {
			responseType: 'blob'
		})
			.then((response) => {
				var fileURL = window.URL.createObjectURL(new Blob([response.data]));
				var fileLink = document.createElement('a');

				fileLink.href = fileURL;
				fileLink.setAttribute('download', `${voyageName}_COPINO_Booking.xlsx`);
				document.body.appendChild(fileLink);

				fileLink.click();

				console.log('Voyage Summary file generated.');
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
		reports,
		report,
		generateBayPlanPDF,
		generateVoyageEdi,
		generateVoyageExcel,
		generateVoyageSummary,
		downloadDraftTdr,
		generateCopinoBooking,
		downloadMloAgentList,
		isLoading,
		errors,
	};
}
