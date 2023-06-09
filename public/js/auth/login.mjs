import { ajax, form, useAlerts } from '../helpers/index.mjs'

const alerts = useAlerts();

export const login = (url, data) => {
    ajax.post(url, data)
        .then(response => alerts.success(response).showFor(5000))
        .catch(error => alerts.error(error.response.data.message).showFor(5000))
}

export const load = () => {
    form('.form-login', ['email', 'password'], login);
}