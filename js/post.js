function post(name, email, comment) {

    const city = document.getElementById("form-details-city").innerText;
    const timestamp = document.getElementById("timestamp").value;

    const url = "/starevents/api/register/";
    const data = {"name":name,"email":email,"city":city,"timestamp":timestamp,"comment":comment};
    const dataTosend = JSON.stringify(data);

    let dataReceived = ""; 
    fetch(url, {
        credentials: "omit",
        mode: "same-origin",
        method: "post",
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: dataTosend
    })
        .then(response => {
            if (response.status === 200) {
                success(); // Success message
                return response.json();
            } else {
                console.log("Status: " + response.status)
                return Promise.reject("server");
            }
        })
        .then(data => {
            dataReceived = JSON.stringify(data)
        })
        .catch(error => {
            message("Something went wrong. Email us!");
            if (error === "server") {
                return console.log("err: "+error);
            }
        })
}

function validateForm() {
    message("", true);

    const nameId = document.getElementById("register-name");
    const emailId = document.getElementById("register-email");
    const commentId = document.getElementById("register-comment");

    nameId.classList.remove("error");
    emailId.classList.remove("error");
    commentId.classList.remove("error");

    const nameIsValid = validateName(nameId);
    const emailIsValid = validateEmail(emailId);
    const commentIsValid = validateLength("Comment", commentId, 0, 200);

    if ( nameIsValid && emailIsValid && commentIsValid ) {
        if ( post(nameId.value, emailId.value, commentId.value) === false )
            message("Unknown error. Please email us.");
    }
    
}

function validateLength(field, fieldId, minLength, maxLength) {
    const str = fieldId.value;
    if ( str.length < minLength ) {
        message(field+" is too short!");
        fieldId.classList.add("error");
        return false;
    } else if ( str.length > maxLength ) {
        message(field+" exeeds maximum length of "+maxLength+" characters!");
        fieldId.classList.add("error");
        return false;
    }
    return true;
}

function validateName(nameId) {

    if ( validateLength("Name", nameId, 2, 50) ) {
        const name = nameId.value;
        // UTF-8 letters and spaces are allowed
        const re = new RegExp("^[\\p{L}\\s]+$", "u");
        if ( re.test(name) ) {
            return true;
        } else {
            nameId.classList.add("error");
            message("Name must contain only letters and space(s)!");
            return false;
        }
    } else return false;
}

function validateEmail(emailId) {

    if ( validateLength("Email", emailId, 7, 200) ) {
        const email = emailId.value;
        atpos = email.indexOf("@");
        dotpos = email.lastIndexOf(".");

        if (atpos < 1 || ( dotpos - atpos < 2 )) {
        emailId.classList.add("error");
        message("Error in e-mail field!");
        return false;
        } else {
            return true;
        }
    } else return false;
}

function message(message, clear = false) {
    let messages = document.getElementById("form-messages");
    if ( clear === true ) {
        messages.innerText = "";
        messages.classList.remove("error");
        messages.style.visibility = "hidden";
        messages.style.display = "none";
    } else {
        
        if ( messages.innerText == "" ) {
            const description = "Can't send registration data. Please fix following errors:";
            const descriptionContainer = document.createElement("p");
            descriptionContainer.innerHTML = description;
            const messageContainer = document.createElement("ul");
            messageContainer.setAttribute("id", "message-list");
            messages.append(descriptionContainer);
            messages.append(messageContainer);
        }

        const messageList = document.getElementById("message-list");
        const messageItem = document.createElement("li");
        messageItem.innerText = message;
        messageList.append(messageItem);

        messages.classList.add("error");
        messages.style.visibility = "visible";
        messages.style.display = "block";

        return false;
    }
}

function success(show = true) {
    const messages = document.getElementById("form-success");
    const regForm = document.getElementById("register-form");
    if ( show === true ) {
        messages.style.visibility = "visible";
        messages.style.display = "block";
        regForm.style.visibility = "hidden";
        regForm.style.display = "none";
        
    } else {
        messages.style.visibility = "hidden";
        messages.style.display = "none";
        regForm.style.visibility = "visible";
        regForm.style.display = "block";
    }
}

function hideForm() {
    message("", true); // Remove previous messages.
    success(false); // Remove success message.
    const regForm = document.getElementById("register-form");
    regForm.style.visibility = "hidden";
    regForm.style.display = "none";
}