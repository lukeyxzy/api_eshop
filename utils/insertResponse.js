

export function insertResponse(message, timeout) {
      const responseInfo = document.getElementById("responseInfo");
      if (responseInfo) {
        responseInfo.textContent = message;
      }
      if(timeout) {
            setTimeout(()=> {responseInfo.innerHTML = "&nbsp;"} , 1000);
      }
}


