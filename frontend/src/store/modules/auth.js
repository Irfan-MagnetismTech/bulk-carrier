import axios from "axios";
import env from "../../config/env.js";

export const auth = {
    state: {
        tokenResponse: {},
        currentUser: {},
    },

    actions: {
        login(context, user) {
            return new Promise((resolve, reject) => {
                let data = {
                    client_id: env.CLIENT_ID,
                    grant_type: env.GRANT_TYPE,
                    client_secret: env.CLIENT_SECRET,
                    username: user.email,
                    password: user.password,
                };

                axios.post('oauth/token', data)
                    .then((response) => {

                        //get expirention time new date gettime + 2 hours
                        // let expirationTime = new Date().getTime() + 7200000;
                        // response.data.auto_logout_at = expirationTime;

                        context.commit("updateTokenResponse", response.data);
                        axios.defaults.headers.common["Authorization"] = `Bearer ${response.data.access_token}`;
                        resolve(response);
                    })
                    .catch((catchLoginError) => {
                        reject(catchLoginError);
                    });
            });
        },

        getCurrentUser(context) {
            return new Promise((resolve, reject) => {
                axios.get('api/administration/get-current-user')
                    .then((response) => {
                        context.commit("updateCurrentUser", response.data);
                        resolve(response);
                    })
                    .catch((catchUserError) => {
                        reject(catchUserError);
                    });
            });
        },

        logout(context) {
            return new Promise((resolve, reject) => {
                context.commit("logout");
                delete axios.defaults.headers.common["Authorization"];
                resolve();
            }).catch((catchLoginError) => {
                reject(catchLoginError);
            });
        },

    },

    mutations: {
        updateTokenResponse(state, tokenResponse) {
            state.tokenResponse = tokenResponse;
        },

        updateCurrentUser(state, currentUser) {
            state.currentUser = currentUser;
        },

        logout(state) {
            state.currentUser = null;
            state.tokenResponse = {};
        },
    },

    getters: {
        getAccessToken(state) {
            return state.tokenResponse.access_token;
        },
        // getTokenResponse(state) {
        //     return state.tokenResponse;
        // },
        getCurrentUser(state) {
            return state.currentUser;
        }
    }
};