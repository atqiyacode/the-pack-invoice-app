import { defineStore } from 'pinia';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

export const useGlobalStore = defineStore(
    'Global',
    () => {
        const router = useRouter();
        const language = ref('id');
        const socketId = ref('');

        const error = ref({});
        const errors = ref([]);
        const loading = ref(false);

        const alertCountdown = ref(0);
        const showTooManyAttempts = ref(false);

        const handleErrors = async (err) => {
            const statusCode = err.status;
            if (statusCode === 503) {
                isMaintenance.value = true;
            }
            if (statusCode === 500) {
                error.value = err.statusText;
            }
            if (statusCode === 400) {
                error.value = err.statusText;
            }
            if (statusCode === 422) {
                errors.value = err.data.errors;
            }
            if (statusCode === 401) {
                clearCurrentSession();
            }
            if (statusCode === 404) {
                const message = err.data.message ? err.data.message : 'API Route Not Found';
                errorToast(message, 'bottom', 7000, 'success');
            }
            if (statusCode === 429) {
                alertCountdown.value = 60;
                showTooManyAttempts.value = true;
                alertErrorCountdown();
            }
        };

        const alertErrorCountdown = () => {
            let intervalId = null;
            // Start countdown interval
            intervalId = setInterval(() => {
                alertCountdown.value--;

                if (alertCountdown.value <= 1) {
                    clearInterval(intervalId);
                    showTooManyAttempts.value = false;
                }
            }, 1000); // decrease countdown every 1 second
        };

        const removeError = () => {
            errors.value = {};
            error.value = null;
        };

        const clearCurrentSession = () => {
            localStorage.removeItem('Invoice');
            localStorage.removeItem('User');
            router.push({ name: 'login' });
        };

        return {
            language,
            socketId,
            error,
            errors,
            loading,
            handleErrors,
            removeError,
            clearCurrentSession
        };
    },
    {
        persist: {
            key: 'global',
            pick: ['language', 'socketId', 'alertCountdown', 'showTooManyAttempts'],
            storage: localStorage
        }
    }
);
