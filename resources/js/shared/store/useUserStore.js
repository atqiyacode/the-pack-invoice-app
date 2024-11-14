import { defineStore } from 'pinia';
import { ref } from 'vue';
import { ApiService } from '../../infrastructure/api/ApiService';
import { useRouter } from 'vue-router';
export const useUserStore = defineStore(
    'User',
    () => {
        const router = useRouter();

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
                        router.push({
                            name: 'login'
                        });
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
