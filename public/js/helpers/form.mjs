import { useAlerts } from './card-alerts.mjs';
import { ajax } from './api.mjs';

const alerts = useAlerts();

export const form = (formId, fields, callback) => {
    const form = document.querySelector(formId);

    const getInput = (selector) => {
        return form.querySelector('#' + selector);
    }
    const onSubmit = (e) => {
        e.preventDefault();

        callback(e.target.action, Object.fromEntries(
            fields.map(name => [ name, getInput(name)?.value ])
        ))
    }

    form.addEventListener('submit', onSubmit);
}

export const apiForm = (url, data) => {
    ajax.post(url, data)
        .then(response => alerts.success(response).showFor(5000))
        .catch(error => alerts.error(error.response.data.message).showFor(5000))
}
