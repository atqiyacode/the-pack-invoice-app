import { defineStore } from 'pinia';
import { ref } from 'vue';
import { ApiService } from '../../infrastructure/api/ApiService';
export const useUserStore = defineStore(
    'User',
    () => {
        const isLoggedIn = ref(false);
        const user = ref({});
        const getSession = () => {
            return new Promise((resolve, reject) => {
                ApiService.get(`api/user`)
                    .then(async (res) => {
                        user.value = res.data;
                        isLoggedIn.value = true;
                        resolve(res);
                    })
                    .catch((err) => {
                        isLoggedIn.value = false;
                        reject(err);
                    });
            });
        };

        return {
            isLoggedIn,
            // function
            getSession
        };
    },
    {
        persist: true
    }
);
