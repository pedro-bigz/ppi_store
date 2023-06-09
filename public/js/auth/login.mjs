import { ajax, useAlerts } from '../helpers/index.mjs'

const alerts = useAlerts();

export const login = (url, data) => {
    ajax.post(url, data)
        .then(response => alerts.success(response).showFor(5000))
        .catch(error => alerts.error(error.response.data.message).showFor(5000))
}

export const load = () => {
    const form = document.querySelector('.form-login');

    const getInput = (selector) => {
        return form.querySelector('#' + selector);
    }
    const onSubmit = (e) => {
        e.preventDefault();

        const email = getInput('email').value;
        const password = getInput('password').value;

        login(e.target.action, { email, password })
    }

    form.addEventListener('submit', onSubmit);
}