
import Api from '../apis/Api';
import { useLoading } from 'vue-loading-overlay';
import useNotification from '../composables/useNotification';
import {ref} from 'vue';
export default function useBusinessInfo() {

  const $loading = useLoading();
  const notification = useNotification();
  const currencies = ref([]);
  const lc_cost_heads = ref([]);

	const isCurrencyLoading = ref(false);

  
  const getAllStoreCategories = async () => {
    try {
      const { data } = await Api.get(`/scm/store-categories`);
      
      return data;
    } catch (error) {
      // Handle error appropriately
      console.error('Error fetching payment methods:', error);
      throw error; // Rethrow the error to be handled by the caller
    }
  };

  const getAllProductTypes = async () => {
    try {
      const { data } = await Api.get(`/scm/product-types`);
      
      return data;
    } catch (error) {
      // Handle error appropriately
      console.error('Error fetching payment methods:', error);
      throw error; // Rethrow the error to be handled by the caller
    }
  };

  /**
   * Get all Currency
   * @returns {Promise<void>}
   * @throws {Error}
   * @async
   * @function
   * @name getCurrencies
   * @description Get all Currency
   * @example
   * import {getCurrencies, currencies} from '@useBusinessInfo';
   * onMounted(() => {
   * getCurrencies();
   * });
   * use currencies to get the data
   *  
   */
  const getCurrencies = async () => {
    isCurrencyLoading.value = true;
    try {
      const { data } = await Api.get(`/currencies`);
      currencies.value = data;
    } catch (error) {
      throw error;
    } finally {
      isCurrencyLoading.value = false;
    }
  };

  /**
   * Get all LC cost heads
   * @returns {Promise<void>}
   * @throws {Error}
   * @async
   * @function
   * @name getLcCostHeads
   * @description Get all LC cost heads
   * @example
   * import {getLcCostHeads, lc_cost_heads} from '@useBusinessInfo';
   * onMounted(() => {
   *  getLcCostHeads();
   * });
   * use lc_cost_heads to get the data
   * 
   */
  const getLcCostHeads = async () => {
    try {
      const { data } = await Api.get(`/scm/lc-cost-heads`);
      lc_cost_heads.value = data;
    } catch (error) {
      throw error;
    }
  };

  const getMaterialCostingHead = async () => {
    try {
      const { data } = await Api.get(`/scm/material-costing-heads`);
      return data;
    } catch (error) {
      throw error;
    }
  }

  return {
    getAllStoreCategories,
    getAllProductTypes,
    getCurrencies,
    currencies,
    getLcCostHeads,
    lc_cost_heads,
    isCurrencyLoading,
    getMaterialCostingHead,
  };
}