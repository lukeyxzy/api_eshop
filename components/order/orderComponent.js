import { fetchOrderDetails, fetchOrders } from "../../api/OrderApi.js";
import { renderOrderDetails, renderOrderItems } from "./renderOrderDetails.js";
import { renderOrders } from "./renderOrders.js";
import { getPaymentMethodNameFromId } from "./orderHelpers.js";
import { insertResponse } from "../../utils/insertResponse.js";
import { getTokenFromLocalStorage } from "../../storage/tokenStorage.js";
    

export async function loadUpOrders(token) {

    const orderList = await fetchOrders(token);

    if(orderList["error"]) {
        insertResponse(orderList["error"]);
        return;
    }
    const ordersWrapper = document.getElementById("ordersWrapper");
    renderOrders(orderList, ordersWrapper);

}




export async function loadOrderDetails(orderId) {
    const token = getTokenFromLocalStorage();
    const result = await fetchOrderDetails(orderId, token);

    if(result.error) {
        document.querySelector(".innerPart").innerHTML = `<p class='fs-3'>${result.error}</p>`;
        return;
    }

    const paymentMethodName = getPaymentMethodNameFromId(result["orderDetails"]["payment_method"]);


    renderOrderDetails(result["orderDetails"], orderId, paymentMethodName);
    renderOrderItems(result["orderItems"]);
}