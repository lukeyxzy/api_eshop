
// ziska hodnotu atributu z URL adresy podle parametru

export function getParamsFromUrl(atribute) {
    const url = new URLSearchParams(window.location.search);
    return url.get(atribute);
}


// prehodi uzivatele na jinou URL podle parametru

export function redirectUrl(url) {
    window.location.href = url;
}