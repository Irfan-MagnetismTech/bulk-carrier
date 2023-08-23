import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useUser() {
    const router = useRouter();
    const users = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const user = ref( {
        name: '',
        email: '',
        password: '',
        confirm_password: '',
        role: '',
    });
    const errors = ref(null);
    const isLoading = ref(false);

    async function getUsers(page) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/administration/users',{
                params: {
                    page: page || 1,
                },
            });
            users.value = data.value;
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

    async function storeUser(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/administration/users', form);
            user.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "authorization.user.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showUser(userId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/administration/users/${userId}`);
            user.value = data.value;
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

    async function updateUser(form, userId) {
        //NProgress.start();
        if(!form.port){
            alert('Please select a port');
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/users/${userId}`,
                form
            );
            user.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "authorization.user.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteUser(userId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/administration/users/${userId}`);
            notification.showSuccess(status);
            await getUsers();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateUserPassword(form) {

        if(!form.password || !form.confirm_password){
            notification.showError(422,'','Please fill in the password fields');
            return;
        }

        if(form.password !== form.confirm_password){
            notification.showError(422,'','Password and confirm password do not match');
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post(
                `/users/password/update`,
                form
            );
            notification.showSuccess(status);
            //router.push({ name: "authorization.user.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        users,
        user,
        getUsers,
        storeUser,
        showUser,
        updateUserPassword,
        updateUser,
        deleteUser,
        isLoading,
        errors,
    };
}
