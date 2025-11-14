document.addEventListener("DOMContentLoaded", function () {

    // Create the banner element
    const cookieBanner = document.createElement("div");
    cookieBanner.id = "cookie-banner";
    cookieBanner.innerHTML = `
        <span>Our website uses cookies to enhance user experience.</span>
        <button id="cookie-accept-btn">OK</button>
    `;
    document.body.appendChild(cookieBanner);

    // Check cookie
    console.log("Checking cookies:", document.cookie); // Debugging statement
    const cookieExists = document.cookie.split("; ").some(row => row.startsWith("cookieAccepted="));

    if (!cookieExists) {
        cookieBanner.style.display = "flex"; // Shows it only after everything is ready
    }

    // On click
    document.getElementById("cookie-accept-btn").addEventListener("click", function () {
        document.cookie = "cookieAccepted=true; max-age=31536000; path=/";
        console.log("Cookie set:", document.cookie); // Debugging statement
        cookieBanner.style.display = "none";
    });

});
