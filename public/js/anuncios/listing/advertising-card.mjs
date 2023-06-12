export const editButton = (action) => {
    const card = document.createElement('div');
    const btn = document.createElement('button');

    btn.innerHTML = 'icon';
    btn.onclick = action;
    
    card.classList.add('advertising-card-edit-button');
    card.appendChild(btn);

    return card;
}

export const deleteButton = (action) => {
    const card = document.createElement('div');
    const btn = document.createElement('button');

    btn.innerHTML = 'icon';
    btn.onclick = action;
    
    card.classList.add('advertising-card-delete-button');
    card.appendChild(btn);

    return card;
}

export const buyButton = (action) => {
    const card = document.createElement('div');
    const btn = document.createElement('button');

    btn.innerHTML = 'icon';
    btn.onclick = action;
    
    card.classList.add('advertising-card-buy-button');
    card.appendChild(btn);

    return card;
}

export const ownerButton = (onEdit, onDelete) => {
    const buttons = document.createElement('div');

    buttons.classList.add('advertising-card-buttons');
    buttons.appendChild(editButton(onEdit))
    buttons.appendChild(deleteButton(onDelete))
    
    return buttons;
}

export const viewButton = (onBuy) => {
    const buttons = document.createElement('div');
    
    buttons.classList.add('advertising-card-buttons');
    buttons.appendChild(buyButton(onBuy))
    
    return buttons;
}

export const formatDescription = (description) => {
    return description.length >= 100 ? description.substr(0, 100) + '...' : description;
}

export const titleContainer = (title) => {
    const container = document.createElement('div');
    const elemnt = document.createElement('h3');

    elemnt.innerHTML = title;

    container.classList.add('advertising-card-title');
    container.appendChild(elemnt);

    return container;
}

export const descriptionContainer = (description) => {
    const container = document.createElement('div');
    const elemnt = document.createElement('div');

    elemnt.innerHTML = formatDescription(description);

    container.classList.add('advertising-card-description');
    container.appendChild(elemnt);

    return container;
}

export const priceContainer = (price) => {
    const container = document.createElement('div');
    const elemnt = document.createElement('div');

    elemnt.innerHTML = price;

    container.classList.add('advertising-card-price');
    container.appendChild(elemnt);

    return container;
}

export const imageContainer = (src) => {
    const container = document.createElement('div');
    const image = document.createElement('div');

    image.classList.add('advertising-card-photo-image');
    image.style.backgroundImage = `url('${src}')`;

    container.classList.add('advertising-card-photo');
    container.appendChild(image);

    return container;
}

export const advertisingCard = ({ image, title, description, price, isAdmin, onEdit, onDelete, onBuy }) => {
    const card = document.createElement('div');
    const content = document.createElement('div');

    content.classList.add('advertising-card-content');
    content.appendChild(titleContainer(title));
    content.appendChild(descriptionContainer(description));
    content.appendChild(priceContainer(price));
    content.appendChild(isAdmin ? ownerButton(onEdit, onDelete) : viewButton(onBuy));

    card.classList.add('advertising-card');
    card.appendChild(imageContainer(image));
    card.appendChild(content);
    
    return card;
}