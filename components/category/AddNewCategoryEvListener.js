import { addCategory } from "../../api/categoriesApi.js";
import { getTokenFromLocalStorage } from "../../storage/tokenStorage.js";
import { insertResponse } from "../../utils/insertResponse.js";
import { loadHeader } from "../header/headerComponent.js";


export async function AddNewCategoryEvListener() {

    const addNewCategoryForm =  document.querySelector("#addCategory");
    addNewCategoryForm.addEventListener("submit", handleAddNewCategorySubmit)

    }


async function handleAddNewCategorySubmit(e) {
e.preventDefault();

    const name = document.getElementById("catName").value;

    if(!name) {
        insertResponse("Pole není vyplněno", true);
    }

    const token = await getTokenFromLocalStorage();
    const result = await addCategory(name, token);

    
        if(result["error"]) {
            insertResponse(result["error"], true)
            return;
        }
        if(result["success"]) {
           document.getElementById("catName").value = "";
           loadHeader();
            }
   

}


    
    