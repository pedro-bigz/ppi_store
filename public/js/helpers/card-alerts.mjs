import { smoothDelay } from './effects.mjs';

export const alerts = (container) => {
    const alerts = {};
    const resolver = (name) => {
        if (!alerts[name]) {
            alerts[name] = container.querySelector(`.card-alert-${name}`);
        }
        return alerts[name]
    }
    const get = (name) => {
        return resolver(name);
    }
    const setMessage = (name, message) => {
        get(name).innerHTML = message;
    }
    const base = (type, message) => {
        setMessage(type, message);
        get(type).classList.toggle('hidden');

        const show = () => {
            get(type).classList.remove('hidden');
        }
        const hide = () => {
            get(type).classList.add('hidden');
        }
        const showFor = (ms) => {
            // show();
            smoothDelay(get(type), ms, 50)//.then(hide);
        }
        return { show, hide, showFor }
    }
    const success = (message) => {
        return base('success', message);
    }
    const error = (message) => {
        return base('error', message);
    }
    return { success, error, get };
};

export const useAlerts = (container = document) => {
    return alerts(container);
}