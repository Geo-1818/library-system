# Library Management System - Complete Implementation

## System Overview

A comprehensive library management system built with Laravel, featuring role-based access control (RBAC) for Admin and Student users with full REST API support.

---

## Features

### Student/User Features
- ✅ User Registration and Authentication
- ✅ Browse Available Books
- ✅ Request to Borrow Books
- ✅ View Personal Borrow History
- ✅ Return Books

### Admin Features
- ✅ Complete User Management (Create, Read, Update, Delete)
- ✅ Complete Book Management (Create, Read, Update, Delete)
- ✅ Manage Borrow Records (Approve/Reject Requests)
- ✅ Admin Dashboard with Statistics
- ✅ Role Assignment

---

## Project Structure

```
library-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── BookController.php
│   │   │   │   ├── BorrowRecordController.php
│   │   │   │   └── AdminController.php
│   │   │   ├── AuthController.php
│   │   │   ├── AdminController.php
│   │   │   ├── BookController.php
│   │   │   └── BorrowRecordController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── StudentMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Book.php
│       └── BorrowRecord.php
├── routes/
│   ├── web.php (Web routes for UI)
│   ├── api.php (REST API routes)
│   └── console.php
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   ├── users.blade.php
│   │   ├── edit-user.blade.php
│   │   ├── books.blade.php
│   │   ├── edit-book.blade.php
│   │   └── borrow-records.blade.php
│   └── books/
│       └── index.blade.php
└── database/
    ├── migrations/
    └── seeders/
```

---

## Database Schema

### Users Table
```
id, name, email, password, role (admin|student), created_at, updated_at
```

### Books Table
```
id, title, author, isbn, quantity, description, created_at, updated_at
```

### Borrow Records Table
```
id, user_id, book_id, borrow_date, return_date, status (pending|approved|borrowed|returned), created_at, updated_at
```

---

## API Endpoints

### Authentication Endpoints

#### Register
```
POST /register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Login
```
POST /login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

#### Logout
```
POST /logout
Authorization: Bearer {token}
```

---

### Book Endpoints (Public)

#### Get All Books
```
GET /api/books
Response:
{
    "success": true,
    "data": {
        "data": [...],
        "current_page": 1,
        "total": 10,
        "per_page": 15
    }
}
```

#### Get Single Book
```
GET /api/books/{id}
Response:
{
    "success": true,
    "data": {
        "id": 1,
        "title": "...",
        "author": "...",
        "isbn": "...",
        "quantity": 5,
        "description": "..."
    }
}
```

#### Search Books
```
GET /api/books/search?q=java
Response:
{
    "success": true,
    "data": [...]
}
```

---

### Admin Book Endpoints (Admin Only)

#### Create Book
```
POST /api/admin/books
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "The Great Gatsby",
    "author": "F. Scott Fitzgerald",
    "isbn": "978-0743273565",
    "quantity": 5,
    "description": "A classic novel..."
}
Response:
{
    "success": true,
    "message": "Book created successfully",
    "data": {...}
}
```

#### Update Book
```
PUT /api/admin/books/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "...",
    "author": "...",
    "quantity": 10
}
```

#### Delete Book
```
DELETE /api/admin/books/{id}
Authorization: Bearer {token}
Response:
{
    "success": true,
    "message": "Book deleted successfully"
}
```

---

### Borrow Endpoints

#### Request to Borrow (Authenticated Users)
```
POST /api/borrows
Authorization: Bearer {token}
Content-Type: application/json

{
    "book_id": 1
}
Response:
{
    "success": true,
    "message": "Borrow request submitted",
    "data": {
        "id": 1,
        "user_id": 1,
        "book_id": 1,
        "borrow_date": "2026-06-04T10:00:00Z",
        "status": "pending"
    }
}
```

#### Get User's Borrow History
```
GET /api/borrows
Authorization: Bearer {token}
Response:
{
    "success": true,
    "data": [...]
}
```

#### Return a Book
```
POST /api/borrows/{id}/return
Authorization: Bearer {token}
Response:
{
    "success": true,
    "message": "Book returned successfully",
    "data": {...}
}
```

---

### Admin Borrow Management (Admin Only)

#### Get All Borrow Records
```
GET /api/admin/borrows
Authorization: Bearer {token}
Response:
{
    "success": true,
    "data": [...]
}
```

#### Approve Borrow Request
```
POST /api/admin/borrows/{id}/approve
Authorization: Bearer {token}
Response:
{
    "success": true,
    "message": "Borrow approved",
    "data": {...}
}
```

#### Reject Borrow Request
```
POST /api/admin/borrows/{id}/reject
Authorization: Bearer {token}
Response:
{
    "success": true,
    "message": "Borrow rejected"
}
```

---

### User Management Endpoints (Admin Only)

#### Get All Users
```
GET /api/admin/users
Authorization: Bearer {token}
Response:
{
    "success": true,
    "data": [...]
}
```

#### Update User
```
PUT /api/admin/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "...",
    "email": "...",
    "role": "admin|student"
}
```

#### Delete User
```
DELETE /api/admin/users/{id}
Authorization: Bearer {token}
```

---

### Dashboard Endpoint (Admin Only)

#### Get Dashboard Statistics
```
GET /api/admin/dashboard
Authorization: Bearer {token}
Response:
{
    "success": true,
    "data": {
        "totalUsers": 50,
        "totalBooks": 100,
        "totalBorrows": 200,
        "activeBorrows": 25,
        "pendingApprovals": 5
    }
}
```

---

## Web Routes

### Public Routes
- `GET /` - Redirect to books
- `GET /register` - Registration form
- `POST /register` - Store registration
- `GET /login` - Login form
- `POST /login` - Authenticate user

### Authenticated User Routes
- `GET /books` - View books
- `POST /books` - Create book (admin)
- `POST /borrow/{id}` - Borrow a book
- `POST /return/{id}` - Return a book
- `POST /logout` - Logout

### Admin Routes (Prefix: `/admin`)
- `GET /dashboard` - Admin dashboard
- `GET /users` - Manage users
- `GET /users/{id}/edit` - Edit user
- `PUT /users/{id}` - Update user
- `DELETE /users/{id}` - Delete user
- `GET /books` - Manage books
- `GET /books/{id}/edit` - Edit book
- `PUT /books/{id}` - Update book
- `DELETE /books/{id}` - Delete book
- `GET /borrows` - Manage borrow records
- `POST /borrows/{id}/approve` - Approve borrow
- `POST /borrows/{id}/reject` - Reject borrow

---

## Setup Instructions

### 1. Database Configuration
```bash
# Update .env file with your database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_system
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Run Migrations
```bash
php artisan migrate
```

### 3. Create Admin User (Optional)
```bash
php artisan tinker
> User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('password'), 'role' => 'admin'])
```

### 4. Start Development Server
```bash
php artisan serve
```

Navigate to `http://localhost:8000`

---

## User Roles & Permissions

### Student
- ✅ View all books
- ✅ Request to borrow books
- ✅ Return borrowed books
- ✅ View own borrow history
- ❌ Cannot manage books
- ❌ Cannot manage users

### Admin
- ✅ All Student permissions
- ✅ Manage all users
- ✅ Manage all books
- ✅ Approve/reject borrow requests
- ✅ View dashboard
- ✅ View all borrow records

---

## API Testing

### Using Postman
1. Import the API collection
2. Set authorization header: `Authorization: Bearer {token}`
3. Test endpoints as documented above

### Using cURL
```bash
curl -X GET http://localhost:8000/api/books \
  -H "Accept: application/json"
```

---

## Error Handling

All API responses follow this format:

### Success Response
```json
{
    "success": true,
    "message": "Operation successful",
    "data": {...}
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error message here"
}
```

### Validation Errors
```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

---

## Security Features

- ✅ Password hashing (bcrypt)
- ✅ CSRF protection
- ✅ Role-based middleware
- ✅ Session-based authentication
- ✅ Input validation & sanitization
- ✅ Authorization checks on all admin endpoints

---

## Development Notes

- All controllers use proper request validation
- Models include relationships for easy data retrieval
- Middleware enforces role-based access control
- Views use Blade templating with Bootstrap 5 styling
- API endpoints return consistent JSON responses
- Database uses soft deletes where appropriate

---

## Version History

- **v1.0** - Initial implementation with full CRUD operations for books, users, and borrow records
