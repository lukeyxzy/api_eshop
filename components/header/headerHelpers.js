import { deleteActiveToken } from "../../storage/tokenStorage.js";
import { insertResponse } from "../../utils/insertResponse.js";
import { redirectUrl } from "../../utils/url.js";


export function addEventListenerToLogOutBtn(logOutButton) {
    if(logOutButton) {
        logOutButton.addEventListener("click", handleLogOut);
    }
}


function handleLogOut() {
    const result = deleteActiveToken();
    if(!result) {
        insertResponse("Nepodařilo se odhlásit.");
        return;
    }
    redirectUrl("index.html");
}
