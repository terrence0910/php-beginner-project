# PHP Beginner Project - Setup Guide

## 📦 Installation & Setup

### Prerequisites
- PHP 7.4 or higher (PHP 8.0+ recommended)
- A terminal/command line
- A text editor (VS Code, Sublime Text, Notepad++, etc.)
- Git (optional, but recommended)

### Step-by-Step Setup

#### 1. Clone or Download the Project

**Using Git (Recommended):**
```bash
git clone https://github.com/terrence0910/php-beginner-project.git
cd php-beginner-project
```

**Without Git:**
- Download as ZIP from GitHub
- Extract the folder
- Open terminal in the extracted folder

#### 2. Verify PHP Installation

Check that PHP is installed on your system:

```bash
php --version
```

You should see output like: `PHP 8.0.0 (cli) ...`

If you get "command not found", [install PHP](https://www.php.net/downloads) for your operating system.

#### 3. Start the Development Server

Navigate to the project directory and start PHP's built-in web server:

```bash
php -S localhost:8000
```

You should see:
```
Development Server started at http://localhost:8000
```

#### 4. Open in Web Browser

Open your browser and go to:
```
http://localhost:8000
```

You should see the welcome page of the Task Management application.

### File Permissions (Linux/Mac Only)

If you encounter permission errors, set proper permissions:

```bash
# Allow the data directory to be written to
chmod 755 data/

# Make config.php readable
chmod 644 config.php
```

## 🔑 Test Credentials

The project comes with sample users. Use these credentials to test the login functionality:

### Sample Users

| Username | Email | Password |
|----------|-------|----------|
| john_doe | john@example.com | password123 |
| jane_smith | jane@example.com | password123 |

## 📁 File Permissions Required

The following directories need write permissions for the application to save data:

- `data/` - Where JSON files are stored (must be writable)

## 🐛 Troubleshooting

### Issue: "Cannot open database file" or "Permission denied"
**Solution:**
- Make sure `data/` directory exists
- Check that `data/` has write permissions
- On Linux/Mac: Run `chmod 755 data/`
- On Windows: Right-click folder → Properties → Security → Edit permissions

### Issue: "Headers already sent" Error
**Cause:** Output before session_start() is called
**Solution:**
- Make sure there are no blank lines before `<?php` in any file
- Check that no HTML is output before `session_start()`
- Look in included files (header.php) for output before session calls

### Issue: 404 Page Not Found
**Cause:** File path doesn't match
**Solution:**
- Make sure you're running the server from the project root
- Check the URL matches the file structure
- Clear browser cache (Ctrl+Shift+Del or Cmd+Shift+Del)

### Issue: "localhost:8000 refused to connect"
**Cause:** PHP server not running or wrong port
**Solution:**
- Make sure you ran `php -S localhost:8000`
- Check if port 8000 is in use: try `php -S localhost:8001`
- On Windows, you may need to allow PHP through firewall

### Issue: JSON Data Not Saving
**Cause:** File permissions or corrupted JSON
**Solution:**
- Check `data/` folder permissions
- Check if files are valid JSON (use [JSONLint](https://jsonlint.com/))
- Make sure there are no syntax errors in data files

## 🔍 How to Debug

### Using var_dump()
To inspect a variable's structure:
```php
var_dump($variable);
// Shows type and value of variable
```

### Using print_r()
To print variable in readable format:
```php
print_r($array);
// Good for arrays and objects
```

### Check Error Logs
If something fails silently:
1. Open `config.php`
2. Look for error reporting settings
3. Check browser console (F12) for JavaScript errors

### Use die() or exit()
Stop execution and show a message:
```php
die("Error occurred here");
// Script stops and shows message
```

## 📚 Project Structure Reference

```
php-beginner-project/
├── README.md                 # Project overview
├── SETUP.md                 # This file
├── config.php               # Core configuration & helpers
├── index.php                # Main entry point (created in Step 2)
├── css/
│   └── style.css           # Styling (created in Step 4)
├── includes/
│   ├── header.php          # Navigation (created in Step 4)
│   ├── footer.php          # Footer (created in Step 4)
│   ├── functions.php       # Helper functions (created in Step 2)
│   └── protect.php         # Auth check (created in Step 2)
├── pages/
│   ├── login.php           # Login page (created in Step 2)
│   ├── register.php        # Registration (created in Step 2)
│   ├── dashboard.php       # Task list (created in Step 3)
│   ├── create-task.php     # Create task (created in Step 3)
│   ├── edit-task.php       # Edit task (created in Step 3)
│   └── logout.php          # Logout (created in Step 2)
└── data/
    ├── users.json          # User data
    └── tasks.json          # Task data
```

## 🚀 Development Workflow

### Daily Development

1. **Start the server:**
   ```bash
   php -S localhost:8000
   ```

2. **Make changes** to PHP files

3. **Refresh browser** to see changes (Ctrl+R or Cmd+R)

4. **Check for errors** in browser console and PHP output

5. **Stop server when done:** (Ctrl+C in terminal)

### Testing Your Code

As you develop each feature:

1. Test with **valid input** (normal cases)
2. Test with **invalid input** (edge cases)
3. Test with **empty input** (error handling)
4. Test with **special characters** (security)

## 📖 Learning Tips

### Best Practices

1. **Read the Comments** - Every function has detailed comments
2. **Experiment** - Change values and see what happens
3. **Use var_dump()** - Inspect data structures
4. **Check the Console** - Browser F12 shows JavaScript errors
5. **Read Error Messages** - They tell you what's wrong

### Common Mistakes to Avoid

- ❌ Forgetting semicolons at end of statements
- ❌ Using `=` instead of `==` in conditions
- ❌ Not closing quotes or parentheses
- ❌ Forgetting `$` before variable names
- ❌ Mixing up array keys with indices

### Debugging Checklist

Before asking for help, check:
- ✅ Are there any PHP error messages?
- ✅ Is the browser console showing errors? (F12)
- ✅ Are file paths correct?
- ✅ Do files have proper permissions?
- ✅ Is PHP version compatible?
- ✅ Are all curly braces and parentheses closed?

## 🎓 Next Steps After Setup

1. **Review the config.php file** - Understand the core functions
2. **Examine data/users.json** - See the user data structure
3. **Examine data/tasks.json** - See the task data structure
4. **Read through comments** - Learn the "why" behind the code
5. **Wait for Step 2** - Authentication system

## 📞 Getting Help

When you encounter an issue:

1. **Read the error message carefully** - It usually tells you the problem
2. **Check this troubleshooting section** - Your issue might be here
3. **Look at the code comments** - They explain how things work
4. **Google the error** - Most PHP errors have solutions online
5. **Check PHP documentation** - php.net has all the answers

## ✅ Verification Checklist

After setup, verify everything works:

- ✅ PHP server starts without errors
- ✅ Browser loads http://localhost:8000
- ✅ No permission errors in terminal
- ✅ data/ folder exists and is readable
- ✅ users.json contains valid JSON
- ✅ tasks.json contains valid JSON

If all items are checked, you're ready to start learning PHP!

---

**Questions? Review the comments in config.php and other files - they explain everything!**
