
export async function fillCategoriesToInput(categoriesList) {
    const container = document.querySelector("#categoryChooseMenu");
    const dropDownToggle = document.querySelector(".dropdown-toggle");
    categoriesList.forEach(element =>{
        const li = document.createElement("li");
        container.appendChild(li);
        li.classList.add("dropdown-item");
        li.innerHTML = element.name;
        li.addEventListener("click", () => {
            dropDownToggle.value = element.name;
            dropDownToggle.setAttribute("data-index", element.id);
        })
        })
    }
