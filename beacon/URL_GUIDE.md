# BEACON Application - URL Access Guide

## ⚠️ Important: Do NOT Access View Files Directly

**WRONG:** `http://localhost:3000/beacon/app/Views/home.php` ❌  
**CORRECT:** `http://localhost:3000/beacon/public/` ✅

## Correct URLs for Port 3000

### Base URL Configuration
- **Base URL:** `http://localhost:3000/beacon/public/`
- **Port:** 3000
- **Document Root:** Should point to `beacon/public/` folder

### Available Routes

#### Home Page
- **URL:** `http://localhost:3000/beacon/public/`
- **Route:** `/` → `Home::index`

#### Authentication Routes
- **Login:** `http://localhost:3000/beacon/public/auth/login`
- **Register:** `http://localhost:3000/beacon/public/auth/register`
- **Process Login (POST):** `http://localhost:3000/beacon/public/auth/login`
- **Process Register (POST):** `http://localhost:3000/beacon/public/auth/register`

#### Organization Routes
- **Launch Organization:** `http://localhost:3000/beacon/public/organization/launch`
- **Process Launch (POST):** `http://localhost:3000/beacon/public/organization/launch`

#### Admin Routes
- **Admin Login:** `http://localhost:3000/beacon/public/admin`
- **Admin Dashboard:** `http://localhost:3000/beacon/public/admin/dashboard`
- **Admin Logout:** `http://localhost:3000/beacon/public/admin/logout`
- **Process Admin Login (POST):** `http://localhost:3000/beacon/public/admin/login`

## Why Direct File Access Doesn't Work

When you access view files directly (e.g., `app/Views/home.php`):
1. CodeIgniter framework is not loaded
2. Helper functions like `base_url()` are not available
3. PHP throws fatal errors → White screen
4. No routing, no controllers, no security

## Quick Reference

| What You Want | Correct URL |
|--------------|-------------|
| Home Page | `http://localhost:3000/beacon/public/` |
| Login | `http://localhost:3000/beacon/public/auth/login` |
| Register | `http://localhost:3000/beacon/public/auth/register` |
| Admin Login | `http://localhost:3000/beacon/public/admin` |
| Launch Org | `http://localhost:3000/beacon/public/organization/launch` |

## Server Configuration

If you want cleaner URLs (without `/beacon/public/`), you need to:
1. Configure your web server (XAMPP/Apache) to point to the `public` folder
2. Update `app/Config/App.php` baseURL to `http://localhost:3000/`
3. Update `public/.htaccess` RewriteBase to `/`

For now, always include `/beacon/public/` in your URLs.

