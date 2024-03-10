const scriptURL =
    "https://script.google.com/macros/s/AKfycbwnUWrY8gzGqbVZ4on6-hsPAbms8khQltLrV85cqOmO6kQklv0/exec";
const form = document.forms["submit-to-google-sheet"];

form.addEventListener("submit", (e) => {
    e.preventDefault();
    fetch(scriptURL, {
        method: "POST",
        body: new FormData(form),
    })
        .then((response) => {
            let successbox = document.querySelector(".success-message");
            successbox.innerHTML =
                '<div class="alert alert-primary">We will get back to you ASAP!</div>';
            form.name = form.email = form.message = " ";
            console.log("Success!", response);
        })
        .catch((error) => console.error("Error!", error.message));
});
