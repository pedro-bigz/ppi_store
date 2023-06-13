import { ajax, form, useAlerts, delay } from '../helpers/index.mjs'

const alerts = useAlerts();

export const login = (url, data) => {
    ajax.post(url, data)
        .then(response => {
            alerts.success(response.message).showFor(5000)
            if (response?.redirect) {
                delay(2000).then(() => {
                    window.location.replace(response.redirect);
                })
            }
        })
        .catch(error => alerts.error(error.message).showFor(5000));
}

export const load = () => {
    form('.form-login', ['email', 'password'], login);
}