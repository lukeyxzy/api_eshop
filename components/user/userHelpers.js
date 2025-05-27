import { check_token } from "../../api/userApi.js";
import { addTokenToLocalStorage } from "../../storage/tokenStorage.js";
import { redirectUrl } from "../../utils/url.js";


export async function denyAccesToNonUsers() {
 
}


export function handleSuccesFromLoginRegister(token) {
addTokenToLocalStorage(token);
redirectUrl("index.html");
}