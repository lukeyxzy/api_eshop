import { check_token } from "../../api/userApi.js";
import { deleteActiveToken, getTokenFromLocalStorage } from "../../storage/tokenStorage.js";
import { redirectUrl } from "../../utils/url.js";


export async function getTokenDataFromToken() {

    const token = getTokenFromLocalStorage();

        if(token) {
        const tokenData = await check_token(token);
        return tokenData;
        }
        return null;
}



export function validateTokenData(tokenData) {
        
        if(tokenData && tokenData["expired"] == true) {
            deleteActiveToken();
            redirectUrl("sign-in.html");
        }
        return tokenData;
}