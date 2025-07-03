
    function validateForm() {
        let isValid = true;
        const username = document.getElementById("username").value; 
        // Clear previous error messages
        document.getElementById("userNameError").innerText = "";
        document.getElementById("emailError").innerText = "";
        document.getElementById("passwordError").innerText = "";
        // Validate username        
        if (username.length < 3) {
            document.getElementById("userNameError").innerText = "Username must be at least 3 characters long.";
            isValid = false;
        }       
        // Validate email   
        const email = document.getElementById("email").value;               
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;          
        if (!emailPattern.test(email)) {
            document.getElementById("emailError").innerText = "Please enter a valid email address.";
            isValid = false;
        }       
        // Validate password        
        const password = document.getElementById("password").value;     
        if (password.length < 6) {
            document.getElementById("passwordError").innerText = "Password must be at least 6 characters long.";
            isValid = false;
        }
        return isValid;
    } 
    

        
    function searchFunction() {
            alert("Search functionality will be implemented here!");
        }

    function showChart() {
            alert("Chart display functionality will be implemented here!");
        }

    function filterProducts() {
        const category = document.getElementById("category").value;
        const maxPrice = document.getElementById("price").value;
        const products = document.querySelectorAll(".product");

            products.forEach(product => {
                const productCategory = product.getAttribute("data-category");
                const productPrice = parseFloat(product.getAttribute("data-price"));

                if ((category === "all" || productCategory === category) && (maxPrice === "" || productPrice <= maxPrice)) {
                    product.style.display = "block";
                } else {
                    product.style.display = "none";
                }
            });

        }
