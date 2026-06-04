# Quick Start Guide - Library Management System

## 🚀 Getting Started in 5 Minutes

### Step 1: Start the Server
```bash
cd c:\xampp\htdocs\library-system
php artisan serve
```
The application will be available at `http://localhost:8000`

---

## 👤 User Account Setup

### Create Student Account
1. Click **Register** on homepage
2. Fill in details:
   - Name: Your full name
   - Email: Your email address
   - Password: At least 8 characters
3. Click **Register**
4. You'll be logged in automatically

### Create Admin Account
1. Use `php artisan tinker` in terminal:
```php
User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('password123'),
    'role' => 'admin'
])
```
2. Login with: `admin@example.com` / `password123`

---

## 📚 Student Features

### Browse Books
1. Login with student account
2. Go to **Books** page
3. View all available books with:
   - Title
   - Author
   - Quantity available
   - Book description

### Borrow a Book
1. Find the book you want
2. Click **Borrow** button
3. Your request will be pending admin approval

### Return a Book
1. After admin approves your borrow
2. Go to **Books** page
3. Click **Return** button on the book

### View History
- Check your profile for borrow history (coming soon)

---

## 🛠️ Admin Features

### Access Admin Panel
1. Login with admin account
2. Click **Admin** dropdown in navbar
3. Select **Dashboard**

### Dashboard
View real-time statistics:
- Total Users
- Total Books
- Total Borrows
- Active Borrows
- Pending Approvals

### Manage Users
1. Go to **Admin** → **Manage Users**
2. View all users in table
3. Edit user:
   - Click **Edit**
   - Change name, email, or role
   - Click **Update User**
4. Delete user:
   - Click **Delete**
   - Confirm deletion

### Manage Books
1. Go to **Admin** → **Manage Books**
2. View all books in table
3. Edit book:
   - Click **Edit**
   - Update title, author, ISBN, or quantity
   - Click **Update Book**
4. Delete book:
   - Click **Delete**
   - Confirm deletion

### Manage Borrow Requests
1. Go to **Admin** → **Manage Borrows**
2. View all borrow records with:
   - User name
   - Book title
   - Dates
   - Current status
3. Approve request:
   - Click **Approve** button
   - Status changes to "approved"
4. Reject request:
   - Click **Reject** button
   - Request is deleted

---

## 🔌 API Usage

### Get All Books
```bash
curl http://localhost:8000/api/books
```

### Search Books
```bash
curl "http://localhost:8000/api/books/search?q=java"
```

### Request to Borrow (Authenticated)
```bash
curl -X POST http://localhost:8000/api/borrows \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"book_id": 1}'
```

### Admin: Approve Borrow
```bash
curl -X POST http://localhost:8000/api/admin/borrows/1/approve \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 🗄️ Database

### Tables Created
1. **users** - User accounts with roles
2. **books** - Book catalog
3. **borrow_records** - Borrowing history

### Sample Data (Add Manually)
1. Login as admin
2. Go to **Manage Books**
3. Click **Add Book** button (add when available)
4. Fill in details and submit

---

## 🔑 Default Test Credentials

### Admin
```
Email: admin@example.com
Password: password123
```

### Student (After Self-Registration)
```
Register with any email and password
```

---

## 📱 Key Pages Reference

| Page | URL | Access |
|------|-----|--------|
| Homepage | `/` | Anyone |
| Register | `/register` | Guests only |
| Login | `/login` | Guests only |
| Books | `/books` | Authenticated |
| Admin Dashboard | `/admin/dashboard` | Admin only |
| Manage Users | `/admin/users` | Admin only |
| Manage Books | `/admin/books` | Admin only |
| Manage Borrows | `/admin/borrows` | Admin only |

---

## ⚙️ Configuration

### Database
Edit `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_system
DB_USERNAME=root
DB_PASSWORD=
```

### Application
```
APP_NAME="Library System"
APP_ENV=local
APP_DEBUG=true
```

---

## 🐛 Troubleshooting

### "Class not found" Error
```bash
php artisan optimize
php artisan cache:clear
```

### Migration Issues
```bash
php artisan migrate:reset
php artisan migrate
```

### Permission Denied
Ensure the `storage` and `bootstrap/cache` directories are writable:
```bash
chmod -R 755 storage bootstrap/cache
```

### Routes Not Working
Clear cache:
```bash
php artisan route:cache
php artisan cache:clear
```

---

## 📚 Learn More

For detailed API documentation, see `API_DOCUMENTATION.md`
For complete build information, see `BUILD_SUMMARY.md`

---

## 💡 Tips

1. **Test with Postman** - Import the API collection for easy testing
2. **Use Student Account First** - Understand student flow before admin
3. **Check Console** - Run migrations before first use
4. **Keep Quantities Updated** - Admin controls book availability
5. **Monitor Approvals** - Regularly check pending borrow requests

---

## 🎯 Common Tasks

### Add a New Book
1. Login as admin
2. Go to Manage Books
3. Fill in book details
4. Click Add/Create
5. Book appears in student view

### Approve Student Request
1. Login as admin
2. Go to Manage Borrows
3. Find pending request
4. Click Approve
5. Student can now borrow

### View Book Statistics
1. Login as admin
2. Go to Dashboard
3. See total books and active borrows
4. Click "Manage Books" to edit

---

**Ready to use!** 🎉
Start with `/register` to create a student account or use the admin credentials above.
