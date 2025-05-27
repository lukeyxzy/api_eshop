


export function activeDropDownMenu() {
    document.querySelectorAll(".dropdown-item").forEach(element => {
        element.addEventListener("click", () => {
          document.querySelector(".dropdown-toggle-option").value = element.textContent;
      })
      })
}