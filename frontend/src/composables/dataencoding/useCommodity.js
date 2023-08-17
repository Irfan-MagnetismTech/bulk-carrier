import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";

export default function useCommodity() {
    const router = useRouter();
    const commodities = ref([]);
    const commodity = ref({});
    const error = ref([]);
    const isLoading = ref(false);

    function getcommodities() {
        NProgress.start();
        isLoading.value = true;
        
        Api.get("dataencoding/commodities")
            .then((response) => {
                commodities.value = response.data;
                console.log("commodities retrieved.");
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    function storeCommodity(form) {
        console.log(form); 
        NProgress.start();
        isLoading.value = true;
        
        Api.post("dataencoding/commodities", form)
            .then(() => {
                commodity.value = {};
                console.log(`Commodity stored.`);
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    function showCommodity(commodityId) {
        NProgress.start();
        isLoading.value = true;
        
        Api.get(`dataencoding/commodities/${commodityId}`)
            .then((response) => {
                commodity.value = response.data;
                console.log("Commodity retrieved.");
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    function updateCommodity(form, commodityId) {
        NProgress.start();
        isLoading.value = true;
        
        Api.put(`dataencoding/commodities/${commodityId}`, form)
            .then((response) => {
                commodity.value = response.data;
                console.log("Commodity updated.");
                router.push({ name: "commodities.index" });
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                isLoading.value = false;
                NProgress.done();
            });
    }

    function deleteCommodity(commodityId) {
        NProgress.start();
        isLoading.value = true;
        
        Api.delete(`dataencoding/commodities/${commodityId}`)
            .then(() => {
                getcommodities();
                console.log("Commodity deleted.");
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
        commodities,
        commodity,
        getcommodities,
        storeCommodity,
        showCommodity,
        updateCommodity,
        deleteCommodity,
        isLoading,
        error,
    };
}
