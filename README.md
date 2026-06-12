# PHP Beginner Learning Project

## 📚 Project Overview

Welcome to the **PHP Beginner Learning Project**! This is a comprehensive, educational task management application designed to teach you PHP fundamentals through a real-world, practical application.

Instead of learning PHP through isolated code snippets, you'll build a complete web application while understanding core concepts like variables, functions, form handling, sessions, and database operations.

## ✨ Features

This project includes:

- **User Authentication System** - Registration and secure login
- **Task Management** - Create, read, update, and delete tasks
- **User Sessions** - Maintain logged-in state
- **Data Persistence** - Store data in JSON files (beginner-friendly)
- **Responsive UI** - Works on desktop and mobile devices
- **Security Features** - Password hashing, input validation, output escaping

## 🎯 What You'll Learn

By completing this project, you'll understand:

### Core PHP Concepts
- ✅ Variables and data types
- ✅ Control structures (if/else, loops)
- ✅ Functions and scope
- ✅ Arrays and array operations
- ✅ String manipulation
- ✅ Form handling ($_GET, $_POST, $_SESSION)
- ✅ Sessions and cookies
- ✅ File operations (reading/writing JSON)
- ✅ Error handling and validation
- ✅ Object-oriented programming (OOP) basics

### Web Development Practices
- ✅ HTML form creation and validation
- ✅ CSS styling and responsive design
- ✅ Security best practices
- ✅ Code organization and structure
- ✅ Debugging techniques

## 📋 Prerequisites

Before you start, make sure you have:

- **PHP 7.4** or higher (PHP 8.0+ recommended)
- A local server (Apache, Nginx, or use `php -S localhost:8000`)
- A text editor (VS Code, Sublime Text, etc.)
- Basic knowledge of HTML and CSS (helpful but not required)
- Command line/terminal access

## 🚀 Quick Start

### 1. Clone the Repository
```bash
git clone https://github.com/terrence0910/php-beginner-project.git
cd php-beginner-project
```

### 2. Start a Local Server
```bash
# Using PHP built-in server
php -S localhost:8000
```

### 3. Open in Browser
Visit `http://localhost:8000` in your web browser

### 4. Login with Sample Credentials
- **Username:** `john_doe`
- **Password:** `password123`

## 📁 Project Structure

```
php-beginner-project/
├── README.md                 # This file
├── SETUP.md                 # Detailed setup instructions
├── config.php               # Configuration and helper functions
├── index.php                # Entry point (coming in Step 2)
├── css/
│   └── style.css           # Styling (coming in Step 4)
├── includes/
│   ├── header.php          # Navigation template (coming in Step 4)
│   ├── footer.php          # Footer template (coming in Step 4)
│   ├── functions.php       # Helper functions (coming in Step 2)
│   └── protect.php         # Authentication check (coming in Step 2)
├── pages/
│   ├── login.php           # Login page (coming in Step 2)
│   ├── register.php        # Registration page (coming in Step 2)
│   ├── dashboard.php       # Task list (coming in Step 3)
│   ├── create-task.php     # Create task form (coming in Step 3)
│   ├── edit-task.php       # Edit task form (coming in Step 3)
│   └── logout.php          # Logout handler (coming in Step 2)
└── data/
    ├── users.json          # User data
    └── tasks.json          # Task data
```

## 🎓 Learning Path

This project is divided into **5 steps**:

### Step 1: Foundation & Configuration ✅ (Current)
- Project setup
- Configuration file
- Sample data files
- Directory structure

### Step 2: Authentication System (Coming Soon)
- User registration
- User login
- Session management
- Logout functionality

### Step 3: Task Management (Coming Soon)
- Create tasks
- Read/display tasks
- Update task status
- Delete tasks

### Step 4: User Interface (Coming Soon)
- HTML templates
- CSS styling
- Responsive design
- Navigation menu

### Step 5: Security & Polish (Coming Soon)
- Security hardening
- Error handling
- Validation improvements
- Code optimization

## 💡 How to Use This Project

1. **Read the Comments** - Every function and section has detailed comments explaining the "why"
2. **Experiment** - Modify the code and see what happens
3. **Test Features** - Try different inputs to understand validation
4. **Debug** - Use `var_dump()` and `print_r()` to inspect data
5. **Progress** - Complete each step before moving to the next

## 🔐 Security Notes

This project demonstrates security best practices including:
- Password hashing using `password_hash()`
- Input validation and sanitization
- Output escaping to prevent XSS
- CSRF token usage (coming in later steps)
- Session security practices

**Important**: While this project teaches security fundamentals, always follow official security guidelines for production applications.

## 🛠️ Troubleshooting

### "Cannot open database file" error
- Make sure the `data/` directory has write permissions
- On Linux/Mac: `chmod 755 data/`

### "Headers already sent" error
- Make sure there's no output before session_start()
- Check for blank lines at the end of PHP files

### 404 errors
- Make sure you're running from the project root directory
- Check that the file path in the URL matches the project structure

For more troubleshooting, see [SETUP.md](SETUP.md)

## 📚 Learning Resources

As you work through this project, these resources will help:

- [PHP Official Documentation](https://www.php.net/manual/en/)
- [PHP Security Guide](https://www.php.net/manual/en/security.php)
- [MDN Web Docs - HTML](https://developer.mozilla.org/en-US/docs/Web/HTML)
- [MDN Web Docs - CSS](https://developer.mozilla.org/en-US/docs/Web/CSS)

## 📝 Code Style

This project follows these conventions for readability:

- **Variables**: `$lower_case_with_underscores`
- **Functions**: `camelCase()`
- **Classes**: `PascalCase`
- **Constants**: `UPPER_CASE_WITH_UNDERSCORES`
- **Indentation**: 4 spaces (not tabs)
- **Comments**: Explain WHY, not WHAT

## 🤝 Contributing

Have suggestions or found issues? Feel free to:
- Open an issue
- Submit a pull request
- Share feedback

## 📄 License

This project is open source and available for educational purposes.

## 🎉 Next Steps

Once you've completed Step 1:
1. Read through the config.php file
2. Understand the folder structure
3. Review the sample data in data/users.json and data/tasks.json
4. Get ready for Step 2: Authentication System!

---

**Happy Learning! 🚀**

Questions? Review the comments in the code - they explain everything!
