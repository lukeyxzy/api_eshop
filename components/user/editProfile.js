import { getLoggedInUserData } from "../../api/userApi.js";
import { fillNameAndSurnameToInputs } from "../../utils/fillNameAndSurnameToInputs.js";
import { editOwnUserData } from "../../api/userApi.js";
import { insertResponse } from "../../utils/insertResponse.js";
import { getTokenFromLocalStorage } from "../../storage/tokenStorage.js";

export async function loadEditProfile() { 

    const token = getTokenFromLocalStorage();
    const loggedInUserData = await getLoggedInUserData(token);
    fillNameAndSurnameToInputs(loggedInUserData);

    const editUserInfoForm =document.querySelector("#edit-user-info");
    editUserInfoForm.addEventListener("submit", (e) => handleEditUserSubmit(e,token));
}



  async function handleEditUserSubmit(e,token) {
        e.preventDefault();
 

    const formData = getFormData();
    const validateError = validateFormData(formData);

    if(validateError) {
        insertResponse(validateError,true);
        return;
    }

    const response = await editOwnUserData(formData, token);
        
    if(response["error"]) {
        insertResponse(response["error"], true);
    }
    if(response["success"]) {
        insertResponse("Změny byly úspěšny provedeny.", true);
        document.querySelector("#edit-user-info-password").value = "";
    }

    }

function getFormData() {
        const data = {
            "name": document.querySelector("#edit-user-info-name").value,
            "surname": document.querySelector("#edit-user-info-surname").value,
            "password": document.querySelector("#edit-user-info-password").value
        }
        return data;
}


function validateFormData(data) {
    if(!data.name || !data.surname || !data.password) {
        return "Nejsou vyplněny všechny hodnoty.";
    }
}
