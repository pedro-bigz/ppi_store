export const api = (defaultUrl = null) => {
    const request = (url, options) => {
        return new Promise((resolve, reject) => {
            const error = {};
            const fetchSuccess = (response) => {
                return resolve(response);
            }
            const fetchError = () => {
                return reject(error);
            }
            fetch(url, options)
                .then(async response => {
                    const data = await response.json();
                    if (response.ok === false) {
                        error.response = { ...response, data };
                        throw new Error(response.statusText);
                    }
                    return data
                })
                .then(fetchSuccess)
                .catch(fetchError);
        })
    };
    const formatOptions = (method, body = [], options = {}) => {
        const headers = { 'X-Requested-With': 'XMLHttpRequest' };
        if (options == {}) {
            options = { headers };
        } else if (options.headers) {
            options.headers = { ...options.headers, ...headers };
        } else {     
            options = { ...options, headers };
        }
        return { ...options, method, body: JSON.stringify(body) };
    };
    const get = (url = null, body = [], options = {}) => {
        return request(url || defaultUrl, formatOptions('GET', body, options));
    };
    const head = (url = null, body = [], options = {}) => {
        return request(url || defaultUrl, formatOptions('HEAD', body, options));
    };
    const post = (url = null, body = [], options = {}) => {
        return request(url || defaultUrl, formatOptions('POST', body, options));
    };
    const put = (url = null, body = [], options = {}) => {
        return request(url || defaultUrl, formatOptions('PUT', body, options));
    };
    const patch = (url = null, body = [], options = {}) => {
        return request(url || defaultUrl, formatOptions('PATCH', body, options));
    };
    const destroy = (url = null, body = [], options = {}) => {
        return request(url || defaultUrl, formatOptions('DELETE', body, options));
    };

    return { get, head, post, put, patch, delete: destroy };
}

export const ajax = api();