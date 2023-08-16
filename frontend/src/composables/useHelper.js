
import Api from "../apis/Api";
import {ref} from "vue";

export default function useHelper() {

  const allAccount = ref([]);
  const isLoading = ref(false);

  function numberFormat(value){
    let nf = Intl.NumberFormat('en', {
      minimumFractionDigits: 2,
    });
    return nf.format(value);
  }

  const getAllUnit = async () => {
    try {
      const { data } = await Api.get(`/configuration/units`);
      
      return data;
    } catch (error) {
      // Handle error appropriately
      console.error('Error fetching units:', error);
      throw error; // Rethrow the error to be handled by the caller
    }
  };

  const getAllVehicleType = async () => {
    try {
      const { data } = await Api.get(`/vehicle-types`);
      
      return data;
    } catch (error) {
      // Handle error appropriately
      console.error('Error fetching vehicle types:', error);
      throw error; // Rethrow the error to be handled by the caller
    }
  };

  const getAllPaymentMethods = async () => {
    try {
      const { data } = await Api.get(`/payment-methods`);
      
      return data;
    } catch (error) {
      // Handle error appropriately
      console.error('Error fetching payment methods:', error);
      throw error; // Rethrow the error to be handled by the caller
    }
  };

  const getMajorFuels = async () => {
    try {
      const { data } = await Api.get(`/common/search-fuel-with-rate`);
      
      return data;
    } catch (error) {
      // Handle error appropriately
      console.error('Error fetching Major Fuels :', error);
      throw error; // Rethrow the error to be handled by the caller
    }
  };

  const getDefaultVendor = async () => {
    try {
      const { data } = await Api.get(`/common/default-vendor`);
      
      return data;
    } catch (error) {
      // Handle error appropriately
      console.error('Error fetching Default Vendor :', error);
      throw error; // Rethrow the error to be handled by the caller
    }
  };

  const printPopup = (newWindowContent) => {
    var newWindow = window.open("", "", "width=500");
    newWindow.document.write(newWindowContent);
  }

  function datePickerFormat(date){
    const parsedDate = new Date(date);
    const year = parsedDate.getFullYear();
    const month = String(parsedDate.getMonth() + 1).padStart(2, '0');
    const day = String(parsedDate.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  return {
    numberFormat,
    getAllUnit,
    getAllVehicleType,
    getAllPaymentMethods,
    getMajorFuels,
    getDefaultVendor,
    datePickerFormat,
    printPopup,
  };
}