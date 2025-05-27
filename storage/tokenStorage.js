



export function addTokenToLocalStorage(token) {
        localStorage.setItem("token", JSON.stringify(token));
}


export function getTokenFromLocalStorage() {
    const token = JSON.parse(localStorage.getItem("token"));
    if(token) {
        return token;
    }
    return null;
}



export function deleteActiveToken() {
    localStorage.removeItem("token");
    return true;
}