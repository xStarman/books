import axios from "axios";
import { API_URL } from "./env";

export const objectToUri = (obj?: any, prefix = ''): string => {
    if (!obj) return '';

    const query = Object.keys(obj).map((key) => {
        const value = obj[key];

        if (obj.constructor === Array) key = `${prefix}[]`;
        else if (obj.constructor === Object) key = prefix ? `${prefix}[${key}]` : key;

        if (typeof value === 'object') return objectToUri(value, key);
        else return `${key}=${encodeURIComponent(value)}`;
    });

    return [].concat.apply([], query as any[]).join('&');
};

export const api = axios.create({
    baseURL: API_URL,
    withCredentials: true,
    withXSRFToken: true,
    timeout: 0,
});