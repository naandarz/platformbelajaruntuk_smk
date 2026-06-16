function togglePasswordField(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;

    if (input.type === "password") {
        input.type = "text";
        if (btn) btn.innerText = "🙈";
    } else {
        input.type = "password";
        if (btn) btn.innerText = "👁";
    }
}

function generateTempPassword(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;

    const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789";
    let pass = "RPL-";
    for (let i = 0; i < 8; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    input.value = pass;
    input.type = "text";
}
