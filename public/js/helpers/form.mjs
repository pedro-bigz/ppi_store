import { useAlerts } from './card-alerts.mjs';
import { delay } from './effects.mjs';
import { ajax } from './api.mjs';

const alerts = useAlerts();

export const getInput = (selector) => {
    return form.querySelector('#' + selector);
}

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

    return { form, getInput, onSubmit };
}

export const apiForm = (url, data) => {
    ajax.post(url, data)
        .then(response => {
            alerts.success(response.message).showFor(5000)

            if (response?.redirect) {
                delay(2000).then(() => {
                    window.location.replace(response.redirect);
                })
            }
        })
        .catch(error => {
            console.log(error);
            alerts.error(error.response.data.message).showFor(5000)
        })
}
