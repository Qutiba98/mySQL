<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            text-align: center;
        }
        .form-container {
            width: 100%;
            max-width: 600px;
        }
        .btn-custom {
            width: 100%;
            margin: 10px 0;
        }
        .error {
            color: red;
        }
    </style>
    <script>
        async function createUser(event) {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            const namePattern = /^[A-Za-z]{2,}(\s[A-Za-z]{2,}){0,3}$/;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phonePattern = /^\d{10}$/;
            const passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}$/;

            let errors = {};

            if (!name.match(namePattern)) {
                errors.name = "Name must have at least two letters.";
            }
            if (!password.match(passwordPattern)) {
                errors.password = "Password must be at least 8 characters long and include uppercase, lowercase, numbers, and special characters.";
            }
            if (password !== confirmPassword) {
                errors.confirmPassword = "Passwords do not match.";
            }
            if (!email.match(emailPattern)) {
                errors.email = "Invalid email format.";
            }
            if (!phone.match(phonePattern)) {
                errors.phone = "Phone number must be exactly 10 digits.";
            }

            if (Object.keys(errors).length > 0) {
                for (let key in errors) {
                    document.getElementById(key + 'Error').innerText = errors[key];
                }
                return;
            }

            const data = {
                name,
                email,
                phone,
                password
            };

            const response = await fetch('create.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.status === 201) {
                window.location.href = 'admin.php';
            } else {
                alert(result.message);
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Create User</h2>
            <form onsubmit="createUser(event)">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name">
                    <span id="nameError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <span id="passwordError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                    <span id="confirmPasswordError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <span id="emailError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                    <span id="phoneError" class="error"></span>
                </div>
                <button type="submit" class="btn btn-success btn-custom">Create User</button>
                <a href="admin.php" class="btn btn-primary btn-custom">Back to Admin</a>
            </form>
        </div>
    </div>
</body>
</html>
