export const form = (formId, fields, callback) => {
    const form = document.querySelector(formId);

    const getInput = (selector) => {
        return form.querySelector('#' + selector);
    }
    const onSubmit = (e) => {
        e.preventDefault();

        callback(e.target.action, Object.fromEntries(
            fields.map(name => [ name, getInput(name).value ])
        ))
    }

    form.addEventListener('submit', onSubmit);
}