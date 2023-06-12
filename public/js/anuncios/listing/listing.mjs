import { ajax, baseUrl } from '../../helpers/index.mjs'
import { advertisingCard } from './advertising-card.mjs';

const baseImageUrl = 'http://ppi.com/images';
const defaultImageSrc = 'sem_foto.png';
const collection = {
    items: [],
    loaded: 0
};
const pagination = {
    page: 1,
    perPage: 6,
    orderBy: 'created_at',
    orderDirection: 'desc',
}

export const listAnuncios = (url, callback) => {
    const options = {
        params: {
            orderBy: pagination.orderBy,
            orderDirection: pagination.orderDirection,
            page: pagination.page,
            per_page: pagination.perPage,
        }
    }
    ajax.get(url, options)
        .then(response => callback(response?.data?.data))
        .catch(error => console.log(error))
}

export const setAnuncios = (anuncios) => {
    pagination.page++;
    console.log(anuncios);
    collection.items = [ ...collection.items, ...anuncios ];
    collection.loaded = collection.items.length;

    render(anuncios);
}

export const onEditAnuncio = (anuncioId) => {
    window.open(`${baseUrl}/anuncios/${anuncioId}/edit`, '_blank');
}

export const onDeleteAnuncio = (anuncioId) => {
    ajax.delete(`${baseUrl}/anuncios/${anuncioId}`)
        .then(response => {
            console.log(response);
        })
        .catch(error => {
            console.log(error);
        });
}

export const onBuyAnuncio = (anuncioId) => {
    window.open(`${baseUrl}/anuncios/${anuncioId}/buy`, '_blank');
}

export const load = () => {
    listAnuncios(baseUrl, setAnuncios);
}

export const render = (anuncios) => {
    const container = document.querySelector('.advertising-card-container');
 
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
            onEdit: () => onEditAnuncio(anuncio.id),
            onDelete: () => onDeleteAnuncio(anuncio.id),
            onBuy: () => onBuyAnuncio(anuncio.id)
        });
        container.appendChild(card);
    });
}