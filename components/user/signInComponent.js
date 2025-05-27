
import { userLogin } from "../../api/userApi.js";
import { insertResponse } from "../../utils/insertResponse.js";
import { handleSuccesFromLoginRegister } from "./userHelpers.js";

export function addSignInEvListener() {

    const loginForm = document.querySelector("#loginForm");
    loginForm.addEventListener("submit", handleSignInSubmit);


}



async function handleSignInSubmit(e) {
        e.preventDefault(); 
    
        const formData = getSignInFormData();
        
        const validateError = validateSignInForm(formData);
        if(validateError) {
            insertResponse(validateError);
            return;
        }


        const result = await userLogin(formData);


    if(result.token) {
     handleSuccesFromLoginRegister(result.token);
    }
    insertResponse(result.error, true);
}

function getSignInFormData() {
    const data = {
    "email" : document.querySelector("#loginEmail").value.trim(),
    "password" : document.querySelector("#loginPassword").value.trim()
    };
    return data;
}

function validateSignInForm(data) {
    if(!data.email || !data.password) {
        return "Nejsou vyplněna všechna pole.";
    }
}