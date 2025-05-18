<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mYkasir - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Login</h1>
        <form id="login-form" class="space-y-4">
            <div>
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <div id="email-error" class="text-red-500 text-xs italic" style="display: none;"></div>
            </div>
            <div>
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <div id="password-error" class="text-red-500 text-xs italic" style="display: none;"></div>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline w-full">Login</button>
        </form>
        <div class="mt-6 text-center">
            <a href="/register" class="text-blue-500 hover:underline">Don't have an account? Register</a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#login-form').submit(function(event) {
                event.preventDefault();
                $('#email-error').hide();
                $('#password-error').hide();

                var email = $('#email').val().trim();
                var password = $('#password').val().trim();
                var hasErrors = false;

                if (!email) {
                    $('#email-error').text('Email is required').show();
                    hasErrors = true;
                } else if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
                    $('#email-error').text('Invalid email format').show();
                    hasErrors = true;
                }

                if (!password) {
                    $('#password-error').text('Password is required').show();
                    hasErrors = true;
                }

                if (hasErrors) {
                    return;
                }

                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({ email: email, password: password }),
                    success: function(data) {
                        if (data.token) {
                            document.cookie = `auth_token=${data.token}; path=/; max-age=86400`; // 24 hours
                            window.location.href = '/products/' + data.user_id;
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        var response = xhr.responseJSON;
                        if (response && response.message) {
                            alert(response.message);
                        } else {
                            alert('An error occurred during login.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>