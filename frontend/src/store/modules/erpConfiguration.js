// Retrieve Units, Vehicle Types and Payment Methods
import axios from "axios";
import env from '../../config/env';


const baseURL = env.BASE_API_URL + 'api';

export const erpConfiguration = {
    state: {
        allVehicleTypes: [],
        allUnits: [],
        allPaymentMethods: [],
        allTanks: [],
        materialSummary: [],
        hasViewedPreviousShiftPerformance: 0
    },
    actions: {
        async getAllUnits({ commit }) {
          axios.get(baseURL+`/configuration/units`)
              .then(allUnit => {
                commit("SET_ALL_UNITS", allUnit);
              })
              .catch(error => {
                // Handle the error
                console.error('An error occurred:', error);
              });
        },
        async getAllVehicleTypes({ commit }) {
          axios.get(baseURL+`/vehicle-types`)
              .then(vehicleTypes => {
                commit("SET_ALL_VEHICLE_TYPES", vehicleTypes);
              })
              .catch(error => {
                // Handle the error
                console.error('An error occurred:', error);
              });
        },
        async getAllPaymentMethod({ commit }) {
          axios.get(baseURL+`/payment-methods`)
              .then(paymentMethods => {
                commit("SET_ALL_PAYMENT_METHODS", paymentMethods);
              })
              .catch(error => {
                // Handle the error
                console.error('An error occurred:', error);
              });
        },
        async getAllTanks({ commit }) {
            axios.get(baseURL+`/configuration/get-tank-status-from-iot`)
                .then(allTank => {
                    commit("SET_ALL_TANKS", allTank);
                })
                .catch(error => {
                    // Handle the error
                    console.error('An error occurred:', error);
                });
        },
        async getMaterialSummary({ commit }) {
            axios.get(baseURL+`/revenue/material-summary-report`)
                .then(materialSummary => {
                    commit("SET_MATERIAL_SUMMARY", materialSummary);
                })
                .catch(error => {
                    // Handle the error
                    console.error('An error occurred:', error);
                });
        },
        viewPreviousShiftPerformance({ commit }) {
          commit("UPDATE_SHIFT_VIEW", 1)
        },
        clearErpConfiguration({ commit }) {
          commit("CLEAR_DATA", "ALL")
        }
    },
    mutations: {
        SET_ALL_UNITS(state, allUnit) {
          state.allUnits = allUnit.data;
        },
        SET_ALL_VEHICLE_TYPES(state, vehicleTypes) {
            state.allVehicleTypes = vehicleTypes.data;
        },
        SET_ALL_PAYMENT_METHODS(state, paymentMethods) {
            state.allPaymentMethods = paymentMethods.data;
        },
        SET_ALL_TANKS(state, allTank) {
            state.allTanks = allTank.data;
        },
        SET_MATERIAL_SUMMARY(state, materialSummary) {
            state.materialSummary = materialSummary.data;
        },
        UPDATE_SHIFT_VIEW(state, viewStatus) {
          state.hasViewedPreviousShiftPerformance = viewStatus;
        },
        CLEAR_DATA(state) {
          state.allUnits = []
          state.allVehicleTypes = []
          state.allPaymentMethods = []
          state.allTanks = []
          state.materialSummary = []
          state.hasViewedPreviousShiftPerformance = 0
        },
    }
}