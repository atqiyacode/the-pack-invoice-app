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
                        UserStore.$patch({
                            isLoggedIn: true
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
                            isLoggedIn: false
                        });
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

        return {
            form,
            // function
            processLogin,
            processLogout
        };
    },
    {
        persist: true
    }
);
