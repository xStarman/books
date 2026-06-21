import axios from "axios";
import { API_URL } from "./env";

export const objectToUri = (obj?: any, prefix = ''): string => {
    if (!obj) return '';

    const query = Object.keys(obj)
        .filter((key) => obj[key] !== undefined && obj[key] !== null)
        .map((key) => {
            const value = obj[key];

            if (obj.constructor === Array) key = `${prefix}[]`;
            else if (obj.constructor === Object) key = prefix ? `${prefix}[${key}]` : key;

            if (typeof value === 'object') return objectToUri(value, key);
            else return `${key}=${encodeURIComponent(value)}`;
        });

    return [].concat.apply([], query as any[]).filter(Boolean).join('&');
};

export const api = axios.create({
    baseURL: API_URL,
    timeout: 0,
});