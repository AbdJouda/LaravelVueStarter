# Laravel & Vue 3 Starter Template

This project is a **Laravel backend (API-only) with an integrated Vue.js 3 frontend**. It serves as a robust starter template, including essential authentication and user management features with role and permissions control.

## 📌 Features

### Backend (Laravel)
- ✅ API-only architecture (No Blade views)
- ✅ Authentication via **Laravel Sanctum**
- ✅ Social Authentication (**Google, Facebook**)
- ✅ Multi-language support
- ✅ API versioning
- ✅ **WebSockets** via Laravel Reverb
- ✅ User Management (CRUD)
- ✅ Role & Permissions Management (CRUD) via **Spatie Laravel Permission**
- ✅ Redis support for caching and queues
- ✅ Image processing with **Intervention Image**
- ✅ Model caching with **genealabs/laravel-model-caching**

### Frontend (Vue.js 3)
- ✅ Vue Router for navigation
- ✅ State management with **Pinia**
- ✅ Form validation using **VeeValidate & Yup**
- ✅ OAuth login support via **vue3-google-login** & **vue3-facebook-login**
- ✅ TailwindCSS for styling
- ✅ WebSockets support with **Laravel Echo & Pusher**

## 🚀 Installation

### 📌 Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- Redis (for caching & queue handling)

### 🔧 Setup (Laravel + Vue.js)
```sh
# Clone the repository
git clone https://github.com/AbdJouda/LaravelVueStarter.git
cd LaravelVueStarter

# Install backend & frontend dependencies
composer install
npm install

# Copy environment variables
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file

# Run migrations and seed data
php artisan migrate --seed

# Start Redis (ensure Redis is installed and running)
redis-server

# Start Laravel server
php artisan serve

# Start Vite development server
npm run dev
```

## 🔑 Environment Variables
For **Social Authentication**, configure the following in your `.env` file:
```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=

FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URI=
```

## 🔐 API Authentication
The API uses **Laravel Sanctum** for authentication. To authenticate a user:
```http
POST /shared/auth/login
Content-Type: application/json
{
  "email": "user@example.com",
  "password": "password"
}
```
Response:
```json
{
    "payload": {
        "message": "",
        "data": { ... },
        "meta": {
            "token": "your-access-token",
            "type": "Bearer",
            "permissions": ["*"],
            "expires_in": null
        }
    }
}
```

## 🔄 WebSockets (Laravel Reverb)
Ensure Redis is running and start WebSockets:
```sh
php artisan reverb:start
```

## 📦 Deployment
- Configure `.env` variables for production
- Run `npm run build` to generate frontend assets
- Set up Redis & queue workers

## 📜 License
This project is licensed under the MIT License.

