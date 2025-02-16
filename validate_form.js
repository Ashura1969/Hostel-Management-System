function validateForm() {
    let firstName = document.forms["residentForm"]["first_name"].value.trim();
    let lastName = document.forms["residentForm"]["last_name"].value.trim();
    let password = document.forms["residentForm"]["password"].value.trim();
    let age = document.forms["residentForm"]["age"].value.trim();
    let school = document.forms["residentForm"]["school"].value.trim();
    let address = document.forms["residentForm"]["address"].value.trim();
    let email = document.forms["residentForm"]["email"].value.trim();
    let phone_no = document.forms["residentForm"]["phone_no"].value.trim();

    if (!firstName || !lastName || !password || !age || !school || !address || !email || !phone_no) {
        alert("All fields are required!");
        return false;
    }

    let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        alert("Invalid email format!");
        return false;
    }

    let phonePattern = /^[0-9]{10,15}$/;
    if (!phonePattern.test(phone_no)) {
        alert("Invalid phone number! Must be 10-15 digits.");
        return false;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters long!");
        return false;
    }

    return true;
}
