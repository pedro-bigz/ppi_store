import { ajax, baseUrl } from "./api.mjs"

export const upload = (file) => {
    const uploadPromiseCallback = (resolve, reject) => {
        const form = new FormData();

        form.append('file', file)
        ajax.post(baseUrl + '/upload', form)
            .then(resolve)
            .catch(reject);
    };
    return new Promise(uploadPromiseCallback);
}