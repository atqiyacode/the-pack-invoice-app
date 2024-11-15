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
        const token = ref('');
        const getSession = async () => {
            await sanctumCsrf();
            await ApiService.get(`api/user`)
                .then(async (res) => {
                    user.value = res.data;
                    isLoggedIn.value = true;
                })
                .catch((err) => {
                    router.push({
                        name: 'login'
                    });
                    isLoggedIn.value = false;
                });
        };

        const sanctumCsrf = async () => {
            await fetch(`sanctum/csrf-cookie`, {
                credentials: 'include'
            });
        };

        return {
            token,
            isLoggedIn,
            // function
            getSession
        };
    },
    {
        persist: true
    }
);
