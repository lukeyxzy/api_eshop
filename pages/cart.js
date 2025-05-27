import { loadMainCart } from "../components/cart/mainCart.js";
import { loadHeader } from "../components/header/headerComponent.js";
import { hideHeaderCart } from "../utils/hideHeaderCart.js";

document.addEventListener("DOMContentLoaded",async () => {
    await loadHeader();
    hideHeaderCart();
    loadMainCart();
});