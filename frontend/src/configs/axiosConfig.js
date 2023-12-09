import axios from "axios";
import {store} from "../store";

const instance = axios.create({
    baseURL: 'http://localhost/untitled/public',
});

instance.interceptors.request.use((config) => {
    const state = store.getState();
    const token = state.user.token;
    if(token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default instance;