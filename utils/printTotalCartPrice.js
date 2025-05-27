import { getCart } from "../storage/cartStorage.js";

// do tagu s id totalCartPrice vypise celkovou cenu kosiku

export async function printTotalCartPrice() {
    const productsInCart = getCart();
    let totalPrice = 0;
    productsInCart.forEach(element => {
        totalPrice += element.calculateTotal;
    });
    document.getElementById("totalCartPrice").textContent = totalPrice;
}