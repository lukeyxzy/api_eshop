import { changePassword } from "../../api/userApi.js";
import { getTokenFromLocalStorage } from "../../storage/tokenStorage.js";
import { insertResponse } from "../../utils/insertResponse.js";


export async function addChangePassEvListener() {
    const changePasswordForm = document.querySelector("#change-password");
    changePasswordForm.addEventListener("submit", handleChangePasswordSubmit );
}
    
async function handleChangePasswordSubmit(e) {
    e.preventDefault();

    const formData = getFormData();

    const validateError = validateFormData(formData);
    
    if (validateError) {
        insertResponse(validateError);
        return;
    }

    const token = getTokenFromLocalStorage();
    const result = await changePassword(formData, token);

    if(result["error"]) {
       insertResponse(result["error"], true);
    }
    if(result["success"]) {
        insertResponse("Heslo úspěšně změněno.");
        removeDataFromInputs();
    }
    

}


function getFormData() {
    const data = {
     oldPassword : document.querySelector("#old-password").value,
      newPassword : document.querySelector("#new-password").value,
    newPasswordAgain : document.querySelector("#new-password-again").value
}
return data;
}

function validateFormData(data) {
        if(data.newPassword !== data.newPasswordAgain) {
        return "Nová hesla nejsou stejná!";
    }
}
function removeDataFromInputs() {
            document.querySelector("#old-password").value = "";
        document.querySelector("#new-password").value = "";
        document.querySelector("#new-password-again").value = "";
}
