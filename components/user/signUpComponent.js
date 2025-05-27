import { userRegister } from "../../api/userApi.js";
import { insertResponse } from "../../utils/insertResponse.js";
import { handleSuccesFromLoginRegister } from "./userHelpers.js";




export function addSignUpEvListener() {
    const registrationForm = document.querySelector("#registrationForm");
    registrationForm.addEventListener("submit", handleSignUpSubmit);
}

async function handleSignUpSubmit(e) {
    e.preventDefault();

    const formData = getSignUpFormData();

    const validateError = validateSignUpForm(formData);
    if(validateError) {
        insertResponse(validateError,true);
        return;
    }

    const result = await userRegister(formData);

    if(result.token) {
     handleSuccesFromLoginRegister(result.token);
    }
    insertResponse(result.error, true);
}


function getSignUpFormData() {
    const data = {
        name : document.getElementById("registrationName").value.trim(),
        surname : document.getElementById("registrationSurname").value.trim(),
        email : document.getElementById("registrationEmail").value.trim(),
        password: document.getElementById("registrationPassword").value,
        passwordAgain: document.getElementById("registrationPasswordAgain").value,
    };
    return data;
}

function validateSignUpForm(data) {
    if (!data.name || !data.surname || !data.email || !data.password || !data.passwordAgain) {
        return "Nejsou vyplněna všechna pole.";
    }
    if(data.password !== data.passwordAgain) {
        return "Hesla se neshodují."
    }

}
