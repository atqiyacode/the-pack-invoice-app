import axios from 'axios';
import { useGlobalStore } from '../../shared/store/useGlobalStore';
import { useUserStore } from '../../shared/store/useUserStore';

const ApiService = axios.create({
    withCredentials: true,
    baseURL: '/',
    headers: {
        Accept: 'application/json',
        'Content-type': 'application/json'
    }
});

ApiService.interceptors.response.use(
    (response) => {
        const GlobalStore = useGlobalStore();
        GlobalStore.$patch({
            loading: false
        });
        return response;
    },
    (error) => {
        const GlobalStore = useGlobalStore();
        GlobalStore.$patch({
            loading: false
        });
        GlobalStore.handleErrors(error.response);
        return Promise.reject(error.response);
    }
);

ApiService.interceptors.request.use(
    (config) => {
        const GlobalStore = useGlobalStore();
        const UserStore = useUserStore();
        GlobalStore.$patch({
            loading: true
        });
        GlobalStore.removeError();
        config.headers.Language = `${GlobalStore.language}`;
        config.headers['X-Socket-Id'] = `${GlobalStore.socketId}`;
        config.headers['Authorization'] = `Bearer ${UserStore.token}`;
        return config;
    },
    (error) => {
        const GlobalStore = useGlobalStore();
        GlobalStore.$patch({
            loading: true
        });
        Promise.reject(error);
    }
);

export { ApiService };
