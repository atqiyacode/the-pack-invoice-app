import { defineStore } from 'pinia';
import { ref } from 'vue';
import { ApiService } from '../../../infrastructure/api/ApiService';
import { useRouter } from 'vue-router';
import { useUserStore } from '../../../shared/store/useUserStore';
export const useAuthStore = defineStore(
    'Auth',
    () => {
        const UserStore = useUserStore();
        const router = useRouter();

        const form = ref({
            email: '',
            password: '',
            checked: false
        });

        const processLogin = () => {
            return new Promise((resolve, reject) => {
                ApiService.post(`api/login`, form.value)
                    .then(async (res) => {
                        if (!form.value.checked) {
                            resetForm();
                        }

                        UserStore.$patch({
                            isLoggedIn: true,
                            token: res.data.token
                        });
                        router.push({
                            name: 'dashboard'
                        });
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        };

        const processLogout = () => {
            return new Promise((resolve, reject) => {
                ApiService.post(`api/logout`)
                    .then(async (res) => {
                        UserStore.$patch({
                            isLoggedIn: false,
                            token: ''
                        });
                        clearCurrentSession;
                        router.push({
                            name: 'login'
                        });
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        };

        const resetForm = () => {
            form.value = {
                email: '',
                password: '',
                checked: false
            };
        };

        const clearCurrentSession = () => {
            localStorage.removeItem('Invoice');
            localStorage.removeItem('User');
        };

        return {
            form,
            // function
            resetForm,
            processLogin,
            processLogout
        };
    },
    {
        persist: true
    }
);
