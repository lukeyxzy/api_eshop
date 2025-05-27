import { fetchHeader } from "../../api/headerApi.js";
import { fetchCategories } from "../../api/categoriesApi.js";
import { renderHeaderCategories } from "./renderHeaderCategories.js";
import { renderHeaderUserArea } from "./renderHeaderUserArea.js";
import { loadHeaderCart } from "../cart/headerCart.js";
import { addEventListenerToLogOutBtn } from "./headerHelpers.js";
import { getTokenDataFromToken } from "../tokens/tokens.js";
import { validateTokenData } from "../tokens/tokens.js";

export async function loadHeader() {
    const headerContainer = document.querySelector("#headerContainer");
    
    const headerHTML = await fetchHeader();
    headerContainer.innerHTML = headerHTML;

    const categoriesList = await fetchCategories();
    renderHeaderCategories(categoriesList);

    const tokenData = await getTokenDataFromToken();

    const validatedTokenData = validateTokenData(tokenData);

    const innerHeaderSection = document.getElementById("innerHeaderSection");
    renderHeaderUserArea(validatedTokenData, innerHeaderSection);

    if(validatedTokenData) {
         const logOutButton = document.getElementById("logOut");
        addEventListenerToLogOutBtn(logOutButton);
    }

    if(validatedTokenData && validatedTokenData.role == 1) {
        return;
    }
    loadHeaderCart();

}




