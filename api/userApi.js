


export async function check_token(token) {
    const response = await fetch("backend/userRouter.php/check_token", {
        method: "GET",
        headers: {
            "Content-type" : "application/json",
            "Authorization": `Bearer ${token}`
        }
        });
    const data = await response.json();
    return data;

}


export async function userRegister(data) {

    const response = await fetch("backend/userRouter.php/register", {
        method: "POST",
        headers: {"Content-Type" : "application/json"},
        body: JSON.stringify(data)
    });
return await response.json();

}


export async function userLogin(data) {
    const response = await fetch("backend/userRouter.php/sign_in", {
        method: "POST",
        headers: { "Content-type" : "application/json"},
        body: JSON.stringify(data)
    })
 return await response.json();
}

export async function getLoggedInUserData(token) {
    const response = await fetch("backend/userRouter.php/getLoggedInUserData", {
        method: "GET",
        headers: {"Content-type": "application/json", "Authorization": `Bearer ${token}`}
    });
    const data = await response.json();
      return data;
}

export async function editOwnUserData(userData, token) {
    const response = await    fetch("backend/userRouter.php/editUserData", {
        method: "POST",
        headers: {"Content-type" : "application/json", "Authorization": `Bearer ${token}`},
        body: JSON.stringify(userData)
})
const data = await response.json();
return data;
}


export async function changePassword(userData, token) {
const response = await fetch("backend/userRouter.php/changePassword",{
    method: "POST",
   headers: {"Content-type" : "application/json", "Authorization": `Bearer ${token}`},
    body: JSON.stringify(userData)
})
const data = await response.json();
return data;
}


