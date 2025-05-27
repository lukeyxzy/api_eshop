import { loadHeader } from "../components/header/headerComponent.js";
import { denyAccesToNonUsers } from "../components/user/userHelpers.js";
import { loadUpOrders } from "../components/order/orderComponent.js";
import { getTokenFromLocalStorage } from "../storage/tokenStorage.js";


document.addEventListener("DOMContentLoaded", async () => {
    loadHeader();
    const token = getTokenFromLocalStorage();
    denyAccesToNonUsers();
    loadUpOrders(token);
})