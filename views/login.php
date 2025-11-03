<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Sneakerness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-black">
                <i data-feather="lock" class="h-6 w-6 text-white"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Admin Login
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Sign in to access the admin panel
            </p>
        </div>
        
        <!-- Error Message -->
        <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <span class="block sm:inline">Invalid username or password</span>
        </div>
        
        <!-- Role Selection -->
        <div class="flex space-x-4 mb-6">
            <button type="button" id="adminBtn" class="flex-1 py-3 px-4 border-2 border-black bg-black text-white rounded-md font-medium transition-colors">
                <i data-feather="shield" class="w-5 h-5 inline mr-2"></i>
                Admin Login
            </button>
            <button type="button" id="userBtn" class="flex-1 py-3 px-4 border-2 border-gray-300 bg-white text-gray-700 rounded-md font-medium transition-colors hover:border-black">
                <i data-feather="user" class="w-5 h-5 inline mr-2"></i>
                User Login
            </button>
        </div>

        <form class="mt-8 space-y-6" id="loginForm">
            <input type="hidden" id="loginType" name="loginType" value="admin">
            
            <div class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input id="username" name="username" type="text" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-black focus:border-black focus:z-10 sm:text-sm" 
                           placeholder="Enter your username">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-black focus:border-black focus:z-10 sm:text-sm" 
                           placeholder="Enter your password">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i data-feather="log-in" class="h-5 w-5 text-gray-300 group-hover:text-gray-200"></i>
                    </span>
                    Sign in
                </button>
            </div>
            
            <div class="text-center">
                <a href="/" class="text-sm text-gray-600 hover:text-black">
                    ← Back to website
                </a>
            </div>
        </form>
        
        <!-- Demo Credentials -->
        <div id="demoCredentials" class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
            <h3 class="text-sm font-medium text-blue-800">Demo Credentials:</h3>
            <div id="adminCredentials">
                <p class="text-sm text-blue-600 mt-1">
                    <strong>Admin Username:</strong> admin<br>
                    <strong>Admin Password:</strong> password
                </p>
            </div>
            <div id="userCredentials" class="hidden">
                <p class="text-sm text-blue-600 mt-1">
                    <strong>User Username:</strong> user<br>
                    <strong>User Password:</strong> user123
                </p>
            </div>
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        feather.replace();
        
        // Handle role selection
        const adminBtn = document.getElementById('adminBtn');
        const userBtn = document.getElementById('userBtn');
        const loginType = document.getElementById('loginType');
        const adminCredentials = document.getElementById('adminCredentials');
        const userCredentials = document.getElementById('userCredentials');
        
        adminBtn.addEventListener('click', () => {
            // Switch to admin mode
            adminBtn.classList.add('border-black', 'bg-black', 'text-white');
            adminBtn.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
            userBtn.classList.add('border-gray-300', 'bg-white', 'text-gray-700');
            userBtn.classList.remove('border-black', 'bg-black', 'text-white');
            
            loginType.value = 'admin';
            adminCredentials.classList.remove('hidden');
            userCredentials.classList.add('hidden');
            
            feather.replace();
        });
        
        userBtn.addEventListener('click', () => {
            // Switch to user mode
            userBtn.classList.add('border-black', 'bg-black', 'text-white');
            userBtn.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
            adminBtn.classList.add('border-gray-300', 'bg-white', 'text-gray-700');
            adminBtn.classList.remove('border-black', 'bg-black', 'text-white');
            
            loginType.value = 'user';
            userCredentials.classList.remove('hidden');
            adminCredentials.classList.add('hidden');
            
            feather.replace();
        });
        
        // Handle login form
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const loginData = {
                username: formData.get('username'),
                password: formData.get('password'),
                loginType: formData.get('loginType')
            };
            
            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(loginData)
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    // Redirect based on role
                    if (result.role === 'admin') {
                        window.location.href = '/admin';
                    } else {
                        window.location.href = '/dashboard';
                    }
                } else {
                    // Show error message
                    document.getElementById('errorMessage').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Login error:', error);
                document.getElementById('errorMessage').classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
