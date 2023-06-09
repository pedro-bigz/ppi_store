export const delay = (ms) => {
    return new Promise((resolve, _) => {
        setTimeout(resolve, ms);
    })
}

export const smoothDelay = (elemnt, delayMs, fadeMs) => {
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

export const fadeIn = (elemnt, ms) => {
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

export const fadeOut = (elemnt, ms) => {
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