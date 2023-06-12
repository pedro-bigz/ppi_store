export const pageResolver = (modules) => {
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