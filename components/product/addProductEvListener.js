import { fetchCategories } from "../../api/categoriesApi.js";
import { fillCategoriesToInput } from "../../utils/fillCategoriesToInput.js";
import { addProduct } from "../../api/productsApi.js";
import { insertResponse } from "../../utils/insertResponse.js";
import { getTokenFromLocalStorage } from "../../storage/tokenStorage.js";


export async function addProductEvListener() {
    const result = await fetchCategories();

    fillCategoriesToInput(result);

    const addProductForm = document.querySelector("#addProduct")
    addProductForm.addEventListener("submit", handleAddProductSubmit)

}


async function handleAddProductSubmit(e) {
    e.preventDefault();


    const addProductData = getAddProductData();
    const validateError = validateAddProductForm(addProductData);

    if(validateError) {
        insertResponse(validateError, true);
        return;
    }

    const formData = formNewFormData(addProductData);

    const token = await getTokenFromLocalStorage();
    const result = await addProduct(formData, token);

    if(result["error"]) {
       insertResponse(result["error"]);
    }
     if(result["success"]) {
      insertResponse("Produkt úspěšně přídán");
        removeFormValues();
        }
    }

function getAddProductData() {
    const categoryToggle =  document.querySelector("#categoryToggler");
    const data = {
     name : document.getElementById("name").value,
     description : document.getElementById("description").value,
     price : document.getElementById("price").value,
     image : document.getElementById("image").files[0],
     categoryId : categoryToggle.getAttribute("data-index")
}
return data;
}

function validateAddProductForm(data) {
   if(!data.categoryId || !data.name || !data.description || !data.image || !data.price) {
    return "Nejsou vyplněna všechna pole";
   }

}

function formNewFormData(data) {

    const formData = new FormData();
    formData.append("name", data.name);
    formData.append("description", data.description);
    formData.append("price", data.price);
    formData.append("image", data.image);
    formData.append("categoryId", data.categoryId);
    return formData;
}


function removeFormValues() {
        document.getElementById("name").value = "";
        document.getElementById("description").value = "";
        document.getElementById("price").value = "";
        document.getElementById("image").value = null;
        document.querySelector("#categoryToggler").value = "";
        document.querySelector("#categoryToggler").setAttribute("data-index", "");
}