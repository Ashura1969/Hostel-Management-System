function validateAdminForm() {
    let firstName = document.forms["adminForm"]["first_name"].value.trim();
    let lastName = document.forms["adminForm"]["last_name"].value.trim();
    let email = document.forms["adminForm"]["email"].value.trim();
    let phone_no = document.forms["adminForm"]["phone_no"].value.trim();
    let currentPassword = document.forms["adminForm"]["current_password"].value.trim();
    let newPassword = document.forms["adminForm"]["new_password"].value.trim();

    if (!firstName || !lastName || !email || !phone_no || !currentPassword) {
        alert("All fields except new password are required!");
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

    if (newPassword.length > 0 && newPassword.length < 6) {
        alert("New password must be at least 6 characters long!");
        return false;
    }

    return true;
}
