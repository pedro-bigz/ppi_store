export const infinityScrool = (callback) => {
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