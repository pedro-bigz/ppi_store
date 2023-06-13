const baseUrl = 'http://ppi.com';
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


const api = (defaultUrl = null) => {
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

const ajax = api();


const delay = (ms) => {
    return new Promise((resolve, _) => {
        setTimeout(resolve, ms);
    })
}

const smoothDelay = (elemnt, delayMs, fadeMs) => {
    return new Promise((resolve, reject) => {
        fadeIn(elemnt, fadeMs).then(() => {
            delay(delayMs).then(() => {
                fadeOut(elemnt, fadeMs)
                    .then(resolve)
                    .catch(reject);
            });
        });
    })
}

const fadeIn = (elemnt, ms) => {
    return new Promise((resolve, _) => {
        elemnt.classList.remove('hidden');
        let opacity = 0; 
        elemnt.style.opacity = opacity;
        let timer = setInterval(function () {
            if (opacity >= 1 || opacity >= 1.0) {
                clearInterval(timer);
                resolve();
            }
            elemnt.style.opacity = opacity.toFixed(1);
            opacity += 0.1;
        }, ms);
    });
}

const fadeOut = (elemnt, ms) => {
    return new Promise((resolve, _) => {
        let opacity = 1; 
        elemnt.style.opacity = opacity;
        let timer = setInterval(function () {
            if (opacity <= 0) {
                clearInterval(timer);
                elemnt.classList.add('hidden');
                elemnt.style.opacity = 0;
                resolve();
            }
            elemnt.style.opacity = opacity.toFixed(1);
            opacity -= 0.1;
        }, ms); 
    });
}

const pageResolver = (modules) => {
    const pages = {
        'route-login': modules.auth.login,
        'route-register': modules.auth.register,
        'route-home': modules.anuncios.listing,
        'route-anuncios/create': modules.anuncios.form,
        'route-anuncios//edit': modules.anuncios.form,
        'route-anuncios//show': modules.anuncios.show,
    }
    const path = window.location.pathname;
    const pattern = /\d+(\.\d+)?/g;

    if (path === '/') {
        return pages['route-home'];
    }

    const page = path.replace(pattern, '')
                    .replace("/", "route-");
                    
    return pages[page];
}

const upload = (file) => {
    const uploadPromiseCallback = (resolve, reject) => {
        const form = new FormData();

        form.append('file', file)
        ajax.post(baseUrl + '/upload', form)
            .then(resolve)
            .catch(reject);
    };
    return new Promise(uploadPromiseCallback);
}

const infinityScrool = (callback) => {
    const loadMoreContent = () => {
        const loading = document.getElementById('loading');

        const showLoading = () => {
            loading.style.display = 'block';
        }
        const hideLoading = () => {
            loading.style.display = 'none';
        }
        
        if (loading.classList.contains('ended')) {
            return removeEventListener('scroll', checkScroll);
        }
        showLoading();
        const loaded = setTimeout(function() {
            callback();
            hideLoading();

            if (loading.classList.contains('ended')) {
                clearInterval(loaded);
            }
        }, 2000);
    }

    const checkScroll = () => {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
            loadMoreContent();
        }
    }

    window.addEventListener('load', checkScroll);
    window.addEventListener('scroll', checkScroll);
}

const alerts = (container) => {
    const alerts = {};
    const resolver = (name) => {
        if (!alerts[name]) {
            alerts[name] = container.querySelector(`.card-alert-${name}`);
        }
        return alerts[name]
    }
    const get = (name) => {
        return resolver(name);
    }
    const setMessage = (name, message) => {
        get(name).innerHTML = message;
    }
    const base = (type, message) => {
        setMessage(type, message);
        get(type).classList.toggle('hidden');

        const show = () => {
            get(type).classList.remove('hidden');
        }
        const hide = () => {
            get(type).classList.add('hidden');
        }
        const showFor = (ms) => {
            // show();
            smoothDelay(get(type), ms, 50)//.then(hide);
        }
        return { show, hide, showFor }
    }
    const success = (message) => {
        return base('success', message);
    }
    const error = (message) => {
        return base('error', message);
    }
    return { success, error, get };
};

const useAlerts = (container = document) => {
    return alerts(container);
}

const alert = useAlerts();

const form = {
	form(formId, fields, callback) {
	    const form = document.querySelector(formId);

	    const getInput = (selector) => {
	        return form.querySelector('#' + selector);
	    }
	    
	    const onSubmit = (e) => {
	        e.preventDefault();

	        callback(e.target.action, Object.fromEntries(
	            fields.map(name => [ name, getInput(name)?.value ])
	        ))
	    }

	    form.addEventListener('submit', onSubmit);

	    return { form, getInput, onSubmit };
	},

	apiForm(url, data) {
	    ajax.post(url, data)
	        .then(response => {
	            alert.success(response.message).showFor(5000)

	            if (response?.redirect) {
	                delay(2000).then(() => {
	                    window.location.replace(response.redirect);
	                })
	            }
	        })
	        .catch(error => {
	            console.log(error);
	            alert.error(error.response.data.message).showFor(5000)
	        })
	}
}

const register = {
	register(url, data) {
	    ajax.post(url, data)
	        .then(response => alert.success(response.message).showFor(5000))
	        .catch(error => alert.error(error.message || 'Erro').showFor(5000))
	},

	load() {
	    form.form('.form-register', ['email', 'first_name', 'last_name', 'password', 'fone'], this.register);
	}
}

const login = {
	login(url, data) {
	    ajax.post(url, data)
	        .then(response => {
	            alert.success(response.message).showFor(5000)
	            if (response?.redirect) {
	                delay(2000).then(() => {
	                    window.location.replace(response.redirect);
	                })
	            }
	        })
	        .catch(error => alerts.error(error.message).showFor(5000));

    },
    load() {
	    form.form('.form-login', ['email', 'password'], this.login);
	}
}

const auth = { login, register };

const editButton = (card, action) => {
    const container = document.createElement('div');
    const btn = document.createElement('button');

    btn.innerHTML = edit();
    btn.onclick = function() {
        action(card, this);
    }
    
    container.classList.add('advertising-card-edit-button');
    container.appendChild(btn);

    return container;
}

const deleteButton = (card, action) => {
    const container = document.createElement('div');
    const btn = document.createElement('button');

    btn.innerHTML = thrash();
    btn.onclick = function() {
        action(card, this);
    }
    
    container.classList.add('advertising-card-delete-button');
    container.appendChild(btn);

    return container;
}

const buyButton = (card, action) => {
    const container = document.createElement('div');
    const btn = document.createElement('button');

    btn.innerHTML = buy();
    btn.onclick = function() {
        action(card, this);
    }
    
    container.classList.add('advertising-card-buy-button');
    container.appendChild(btn);

    return container;
}

const ownerButton = (card, onEdit, onDelete) => {
    const buttons = document.createElement('div');

    buttons.classList.add('advertising-card-buttons');
    buttons.appendChild(editButton(card, onEdit))
    buttons.appendChild(deleteButton(card, onDelete))
    
    return buttons;
}

const viewButton = (card, onBuy) => {
    const buttons = document.createElement('div');
    
    buttons.classList.add('advertising-card-buttons');
    buttons.appendChild(buyButton(card, onBuy))
    
    return buttons;
}

const formatDescription = (description) => {
    return description.length >= 100 ? description.substr(0, 100) + '...' : description;
}

const titleContainer = (title) => {
    const container = document.createElement('div');
    const elemnt = document.createElement('h3');

    elemnt.innerHTML = title;

    container.classList.add('advertising-card-title');
    container.appendChild(elemnt);

    return container;
}

const descriptionContainer = (description) => {
    const container = document.createElement('div');
    const elemnt = document.createElement('div');

    elemnt.innerHTML = formatDescription(description);

    container.classList.add('advertising-card-description');
    container.appendChild(elemnt);

    return container;
}

const priceContainer = (price) => {
    const container = document.createElement('div');
    const elemnt = document.createElement('div');

    elemnt.innerHTML = price;

    container.classList.add('advertising-card-price');
    container.appendChild(elemnt);

    return container;
}

const imageContainer = (src) => {
    const container = document.createElement('div');
    const image = document.createElement('div');

    image.classList.add('advertising-card-photo-image');
    image.style.backgroundImage = `url('${src}')`;

    container.classList.add('advertising-card-photo');
    container.appendChild(image);

    return container;
}

const advertisingCard = ({ image, title, description, price, isAdmin, onEdit, onDelete, onBuy }) => {
    const card = document.createElement('div');
    const content = document.createElement('div');

    content.classList.add('advertising-card-content');
    content.appendChild(titleContainer(title));
    content.appendChild(descriptionContainer(description));
    content.appendChild(priceContainer(price));
    content.appendChild(isAdmin ? ownerButton(card, onEdit, onDelete) : viewButton(card, onBuy));

    card.classList.add('advertising-card');
    card.appendChild(imageContainer(image));
    card.appendChild(content);
    
    return card;
}

const search = (data, collection, loader) => {
    collection.items = [];
    collection.filters = {
        search: data.search,
        searchIn: data.columns,
    };
    loader();
}

const load = (collection, loader) => {
    form.form('.search-bar', [
        'columns',
        'search',
    ], (_, data) => {
        search(data, collection, loader)
    })
} 

const anuncios = {
	listing: {
		resetCollection() {
		    const container = document.querySelector('.advertising-card-container');
		    collection.items = [];
		    while (container.firstChild) {
		        container.removeChild(container.firstChild);
		    }
		},

		listAnuncios(url, callback, resetPage = false) {
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
		        this.resetCollection();
		    }

		    Object.assign(options.params, collection.filters)

		    ajax.get(url, options)
		        .then(response => callback(response?.data))
		        .catch(error => console.log(error))
		},

		setAnuncios(anuncios) {
            const copy = Object.assign({}, pagination);

		    copy.page++;
		    copy.numPages = anuncios.numPages;
		    copy.total = anuncios.total;

            pagination = Object.assign({}, copy)

		    collection.items = [ ...collection.items, ...anuncios?.data ];
		    collection.loaded = collection.items.length;

		    this.render(anuncios?.data);
		},

		onEditAnuncio(anuncioId) {
		    window.open(`${baseUrl}/anuncios/${anuncioId}/edit`, '_blank');
		},

		onDeleteAnuncio(anuncioId, card) {
		    card.parentNode.removeChild(card);
		    ajax.delete(`${baseUrl}/anuncios/${anuncioId}`)
		        .then(response => {
		            console.log(response);
		        })
		        .catch(error => {
		            console.log(error);
		        });
		},

		goToAnuncio(anuncioId) {
		    window.open(`${baseUrl}/anuncios/${anuncioId}/show`, '_blank');
		},

		onBuyAnuncio(anuncioId) {
		    this.goToAnuncio(anuncioId);
		},

		load() {
		    this.init();
		    search.load(collection, () => {
		        this.listAnuncios(baseUrl, setAnuncios, true)
		    });
		},

		init() {
		    this.listAnuncios(baseUrl, setAnuncios);
		    this.listAnuncios(baseUrl, setAnuncios);
		    infinityScrool(() => {
		        if (pagination.page < pagination.numPages) {
		            this.listAnuncios(baseUrl, setAnuncios);
		        }
		    })
		},

		render(anuncios) {
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
		            onEdit: (card, elemnt) => this.onEditAnuncio(anuncio.id, card, elemnt),
		            onDelete: (card, elemnt) => this.onDeleteAnuncio(anuncio.id, card, elemnt),
		            onBuy: (card, elemnt) => this.onBuyAnuncio(anuncio.id, card, elemnt),
		        });
		        card.addEventListener("click", () => this.goToAnuncio(anuncio.id));
		        container.appendChild(card);
		    });
		},
	},
	form: {
		listarFotos(getInput) {
		    const path = window.location.pathname;
		    
		    if (path.includes('/edit')) {
		        ajax.get(`${baseUrl}${path.replace('edit', 'fotos')}`)
		            .then(response => {
		                response.fotos.forEach(foto => {
		                    this.renderImagePreview(foto.filename, getInput);
		                })
		                console.log(response);
		            })
		            .catch(error => {
		                console.log(error);
		            })
		    }
		},

		deleteImage(elemnt, filename, getInput) {
		    const bag = JSON.parse(getInput('file_bag').value || '[]');
		    const card = elemnt.parentNode;
		    const container = card.parentNode;

		    if (bag.includes(filename)) {
		        bag.splice(bag.indexOf(filename), 1);
		    }

		    getInput('file_bag').value = JSON.stringify(bag);
		    container.removeChild(card);
		},

		renderImagePreview(file, getInput) {
		    const container = document.querySelector(".preview-uploaded-container");
		    const card = document.createElement('div');
		    const image = document.createElement('div');
		    const button = document.createElement('button');

		    card.classList.add('image-preview-container');
		    image.classList.add('image-preview');
		    image.style.backgroundImage = "url('" + baseUrl + '/images/' + file + "')";

		    button.classList.add('image-preview-thrash-button')
		    button.innerHTML = thrash();
		    button.setAttribute('type', 'button');
		    button.addEventListener('click', function(e) {
		        this.deleteImage(this, file, getInput);
		    });

		    card.appendChild(image);
		    card.appendChild(button);
		    container.appendChild(card);
		},

		uploadHandler(file, getInput) {
		    upload(file)
		        .then(response => {
		            getInput('file_bag').value = JSON.stringify([
		                ...JSON.parse(getInput('file_bag').value || '[]'),
		                response.path
		            ]);

		            this.renderImagePreview(response.path, getInput);
		            alert.success(response.message).showFor(3000);
		        })
		        .catch(error => {
		            alert.error(error.response.message).showFor(3000);
		        })
		},

		render({ getInput }) {
		    const container = document.querySelector('.input-file-container');

		    const input = document.createElement('input');
		    const label = document.createElement('label');
		    const name = document.createElement('span');
		    const button = document.createElement('span');

		    input.type = input.id = input.name = 'file';
		    input.classList.add('input-file');
		    input.onchange = function() {
		        this.uploadHandler(this.files[0], getInput)
		    };
		    input.setAttribute('multiple', true);
		    input.setAttribute('accept', 'image/png, image/jpg, image/jpeg');

		    label.setAttribute('for', 'file');

		    name.setAttribute('id', 'input-file-name');
		    name.classList.add('input-file-box');

		    button.classList.add('input-file-button');
		    button.innerHTML = 'Selecionar arquivo';

		    label.appendChild(name);
		    label.appendChild(button);

		    container.appendChild(input);
		    container.appendChild(label);

		    listarFotos(getInput);
		},

		load() {
		    this.render(
		        form.form('.form-anuncio', [
		            'titulo',
		            'preco',
		            'categoria',
		            'anunciante',
		            'endereco',
		            'descricao',
		            'file_bag',
		        ], form.apiForm)
		    );
		},
	},
	show: {
		load() {
		    const container = document.querySelector('.galery-container');
		    const sidebar = container.querySelector('.galery-sidebar');
		    const items = sidebar.querySelectorAll('.galery-item');

		    const content = container.querySelector('.galery-content-preview');
		    const preview = content.querySelector('.galery-item');

		    items.forEach(item => {
		        item.addEventListener('click', (e) => {
		            preview.style.backgroundImage = item.style.backgroundImage;
		        })
		    })

		    form.form('.form-interesses', [
		        'nome',
		        'contato',
		        'mensagem'
		    ], form.apiForm);
		},
	}
}

pageResolver({ auth, anuncios }).load();