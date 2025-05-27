import { placeOrder } from "../../api/OrderApi.js";
import { getLoggedInUserData } from "../../api/userApi.js";
import { prepareOrderData } from "./orderHelpers.js";
import { prepareOrderItemsData } from "./orderHelpers.js";
import { getCart, removeEverythingFromCart } from "../../storage/cartStorage.js";
import {  insertResponse } from "../../utils/insertResponse.js";
import { fillUpDeliveryInfo } from "./placeOrderHelpers.js";
import { getTokenFromLocalStorage } from "../../storage/tokenStorage.js";
import { redirectUrl } from "../../utils/url.js";


export async function placeOrderComponent() {

        const token = getTokenFromLocalStorage();
        let loggedIn = false;

        if(token) {
             const user = await getLoggedInUserData(token);
        if(!user.error) {
             fillUpDeliveryInfo(user.name, user.surname, user.email);
             loggedIn = true;
        }
        }
        
        const placeOrderForm = document.getElementById("placeOrder");
        placeOrderForm.addEventListener("submit", (e) => handlePlaceOrderSubmit(token, e));
}



            
    async function handlePlaceOrderSubmit(token, e)  {
            e.preventDefault();

            const orderItems = getCart();

            if(!orderItems) {
            redirectUrl("cart.html");
            }
            
            const paymentMethod = document.querySelector('input[name="payment"]:checked');
            if(!paymentMethod) {
                insertResponse("Vyberte způsob platby", true);
                return;
            }

        
            const paymentMethodValue = paymentMethod.value;
            const preparedOrderItems = prepareOrderItemsData(orderItems);


            const orderData = prepareOrderData(token, preparedOrderItems, paymentMethodValue);     
            const result = await placeOrder(orderData, token);       
            if(result["error"]) {
                insertResponse(result["error"]);
                return;
            }   

            removeEverythingFromCart();
            redirectUrl(`thank_you.html?id=${result}`);
}








