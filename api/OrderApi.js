






export async function placeOrder(orderData, token) {
    const response = await fetch("backend/orderRouter.php/placeOrder", {
        method: "POST",
      headers: {"Content-type": "application/json", "Authorization": `Bearer ${token}`},
        body: JSON.stringify(orderData)
    })
    const data = response.json();
    return data;
}




export async function fetchOrders(token) {
    const response = await fetch("backend/orderRouter.php/getOrders", {
        method: "GET",
        headers: {"Content-type": "application/json", "Authorization": `Bearer ${token}`}
    });
    const data = await response.json();
    return data;
}



export async function fetchOrderDetails(orderId, token) {
    const response = await fetch(`backend/orderRouter.php/getOrderDetails?id=${orderId}`, {
        method: "GET",
          headers: {"Content-type": "application/json", "Authorization": `Bearer ${token}`}
    });
    const data = await response.json();
    return data;
}