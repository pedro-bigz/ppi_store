import { ajax, baseUrl } from "./api.mjs"

export const upload = function(e) {
    const form = new FormData();

    form.append('file', this.files[0])
    console.log(e, this.files)
    ajax.post(baseUrl + '/upload', form)
        .then(console.log)
        .catch(console.error);
}