


export function fillNameAndSurnameToInputs(loggedInUserData) {
document.getElementById("edit-user-info-name").value = loggedInUserData["name"];
document.getElementById("edit-user-info-surname").value = loggedInUserData["surname"];
}