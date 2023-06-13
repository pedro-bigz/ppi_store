import { baseUrl, apiForm, useAlerts, ajax } from '../../helpers/index.mjs';
import { form } from '../../helpers/index.mjs'

const alert = useAlerts();

export const search = (data, collection, loader) => {
    collection.items = [];
    collection.filters = {
        search: data.search,
        searchIn: data.columns,
    };
    loader();
}

export const load = (collection, loader) => {
    form('.search-bar', [
        'columns',
        'search',
    ], (_, data) => {
        search(data, collection, loader)
    })
} 