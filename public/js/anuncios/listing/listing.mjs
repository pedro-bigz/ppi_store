import { ajax, baseUrl, infinityScrool } from '../../helpers/index.mjs'
import { advertisingCard } from './advertising-card.mjs';
import * as search from './search.mjs';

const baseImageUrl = 'http://ppi.com/images';
const defaultImageSrc = 'sem_foto.png';
const collection = {
    items: [],
    loaded: 0,
    filters: {},
};
const pagination = {
    page: 1,
    perPage: 6,
    numPages: 1,
    total: 1,
    orderBy: 'created_at',
    orderDirection: 'desc',
}

export const resetCollection = () => {
    const container = document.querySelector('.advertising-card-container');
    collection.items = [];
    while (container.firstChild) {
        container.removeChild(container.firstChild);
    }
}

export const listAnuncios = (url, callback, resetPage = false) => {
    const options = {
        params: {
            orderBy: pagination.orderBy,
            orderDirection: pagination.orderDirection,
            page: pagination.page,
            per_page: pagination.perPage,
        }
    }

    if (resetPage === true) {
        options.params.page = 1;
        resetCollection();
    }

    Object.assign(options.params, collection.filters)

    ajax.get(url, options)
        .then(response => callback(response?.data))
        .catch(error => console.log(error))
}

export const setAnuncios = (anuncios) => {
    pagination.page++;
    pagination.numPages = anuncios.numPages;
    pagination.total = anuncios.total;

    collection.items = [ ...collection.items, ...anuncios?.data ];
    collection.loaded = collection.items.length;

    render(anuncios?.data);
}

export const onEditAnuncio = (anuncioId) => {
    window.open(`${baseUrl}/anuncios/${anuncioId}/edit`, '_blank');
}

export const onDeleteAnuncio = (anuncioId, card) => {
    card.parentNode.removeChild(card);
    ajax.delete(`${baseUrl}/anuncios/${anuncioId}`)
        .then(response => {
            console.log(response);
        })
        .catch(error => {
            console.log(error);
        });
}

export const goToAnuncio = (anuncioId) => {
    window.open(`${baseUrl}/anuncios/${anuncioId}/show`, '_blank');
}

export const onBuyAnuncio = (anuncioId) => {
    goToAnuncio(anuncioId);
}

export const load = () => {
    init();
    search.load(collection, () => {
        listAnuncios(baseUrl, setAnuncios, true)
    });
}

export const init = () => {
    infinityScrool(() => {
        if (pagination.page < pagination.numPages || collection.loaded === 0) {
            listAnuncios(baseUrl, setAnuncios);
        }
    })
}

export const render = (anuncios) => {
    const container = document.querySelector('.advertising-card-container');
    const loading = container.nextElementSibling;
 
    if (pagination.page >= pagination.numPages) {
        loading.classList.add('ended');
    }

    const ensureImageSrc = (src) => {
        return baseImageUrl + '/' + (src || defaultImageSrc);
    }

    anuncios.forEach((anuncio) => {
        const card = advertisingCard({
            image: ensureImageSrc(anuncio?.image?.filename),
            title: anuncio.titulo,
            description: anuncio.descricao,
            price: anuncio.preco,
            isAdmin: anuncio.owner,
            onEdit: (card, elemnt) => onEditAnuncio(anuncio.id, card, elemnt),
            onDelete: (card, elemnt) => onDeleteAnuncio(anuncio.id, card, elemnt),
            onBuy: (card, elemnt) => onBuyAnuncio(anuncio.id, card, elemnt),
        });
        card.addEventListener("click", () => goToAnuncio(anuncio.id));
        container.appendChild(card);
    });
}