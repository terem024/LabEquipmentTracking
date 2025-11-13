document.addEventListener("DOMContentLoaded", function () {
  const cookiePopup = document.createElement("div");
  cookiePopup.classList.add("cookie-popup");
  cookiePopup.innerHTML = `
    <p>We use cookies to enhance your experience. By accepting, your login info will be remembered next time.</p>
    <div class="cookie-buttons">
      <button id="acceptCookies">Accept</button>
      <button id="declineCookies">Decline</button>
    </div>
  `;
  document.body.appendChild(cookiePopup);

  if (getCookie("cookiesAccepted") === "true") {
    cookiePopup.style.display = "none";
  }

  document.getElementById("acceptCookies").addEventListener("click", function () {
    setCookie("cookiesAccepted", "true", 30); 
    cookiePopup.style.display = "none";

    const usernameElement = document.querySelector(".admin-area span");
    if (usernameElement) {
      const username = usernameElement.textContent.replace("Welcome, ", "").trim();
      setCookie("username", username, 30);
    }
  });

  document.getElementById("declineCookies").addEventListener("click", function () {
    setCookie("cookiesAccepted", "false", 30);
    cookiePopup.style.display = "none";
  });

  function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = `${name}=${value};expires=${d.toUTCString()};path=/`;
  }

  function getCookie(name) {
    const cname = name + "=";
    const decodedCookie = decodeURIComponent(document.cookie);
    const ca = decodedCookie.split(";");
    for (let c of ca) {
      while (c.charAt(0) === " ") c = c.substring(1);
      if (c.indexOf(cname) === 0) return c.substring(cname.length, c.length);
    }
    return "";
  }

  console.log("Cookies.js loaded!");

});
