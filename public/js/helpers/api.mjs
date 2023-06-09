export const baseUrl = 'http://ppi.com';

export const api = (defaultUrl = null) => {
    const request = (url, options) => {
        return new Promise((resolve, reject) => {
            const error = {};
            fetch(url, options)
                .then(response => {
                    response.json()
                        .then(data => {
                            if (response.ok === false) {
                                return reject(data);
                            }
                            resolve(data)
                        })
                        .catch(reject)
                })
                .catch(reject);
        })
    };
    const getUrl = (url) => {
        return url || defaultUrl;
    }
    const formatOptions = (method, data, options = {}) => {
        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
            'enctype': 'multipart/form-data'
        };
        const body = {};
        if (data !== undefined) {
            body.body = data instanceof FormData ?
                data : JSON.stringify(data);
        }
        if (options == {}) {
            options = { headers };
        } else if (options.headers) {
            options.headers = { ...options.headers, ...headers };
        } else {     
            options = { ...options, headers };
        }
        return { ...options, method, ...body };
    };
    const get = (url = null, body = [], options = {}) => {
        const params = (body?.params) ? '?' + new URLSearchParams(body.params) : '';
        return request(getUrl(url) + params, formatOptions('GET', undefined, options));
    };
    const head = (url = null, options = {}) => {
        return request(getUrl(url), formatOptions('HEAD', undefined, options));
    };
    const post = (url = null, body = [], options = {}) => {
        return request(getUrl(url), formatOptions('POST', body, options));
    };
    const put = (url = null, body = [], options = {}) => {
        return request(getUrl(url), formatOptions('PUT', body, options));
    };
    const patch = (url = null, body = [], options = {}) => {
        return request(getUrl(url), formatOptions('PATCH', body, options));
    };
    const destroy = (url = null, body = [], options = {}) => {
        return request(getUrl(url), formatOptions('DELETE', body, options));
    };

    return { get, head, post, put, patch, delete: destroy };
}

export const ajax = api();