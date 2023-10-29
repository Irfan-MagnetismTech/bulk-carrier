
import Api from '../apis/Api';

export default function useBusinessInfo() {

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

  return {
    getAllStoreCategories,
    getAllProductTypes
  };
}