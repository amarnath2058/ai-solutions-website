async function login() {
    const username = document.getElementById("adminUsername").value;
    const password = document.getElementById("adminPassword").value;
    const rememberMe = document.getElementById("rememberMe").checked;
    
    const errorDiv = document.getElementById("loginError");
    errorDiv.innerHTML = "";
    
    if (!username || !password) {
        errorDiv.innerHTML = "❌ Please enter username and password!";
        return;
    }
    
    errorDiv.innerHTML = "⏳ Logging in...";
    
    try {
        const response = await fetch('api/admin_login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        });
        
        const result = await response.json();
        
        if (result.success) {
            if (rememberMe) {
                localStorage.setItem("admin_remembered", "true");
            }
            sessionStorage.setItem("admin_logged_in", "true");
            sessionStorage.setItem("admin_name", result.user.name);
            window.location.href = "dashboard.php";
        } else {
            errorDiv.innerHTML = "❌ " + result.message;
        }
    } catch (error) {
        errorDiv.innerHTML = "❌ Network error. Make sure Apache is running!";
        console.error("Error:", error);
    }
}

// Allow Enter key to submit
document.getElementById("adminPassword").addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
        login();
    }
});