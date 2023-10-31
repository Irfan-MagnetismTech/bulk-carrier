
import Api from '../apis/Api';
import { useLoading } from 'vue-loading-overlay';
import useNotification from '../useNotification.js';

export default function useBusinessInfo() {

  const $loading = useLoading();
  const notification = useNotification();
  


  
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

  const getCurrencies = async () => {
    try {
      const { data } = await Api.get(`/currencies`);
      return data;
    } catch (error) {
      throw error;
    }
  };

  return {
    getAllStoreCategories,
    getAllProductTypes
  };
}