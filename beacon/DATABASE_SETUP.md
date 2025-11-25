# Database Connection Setup Guide

## Step 1: Create/Update .env File

1. Navigate to your project root: `C:\xampp\htdocs\BEACON\beacon\`
2. If `.env` file doesn't exist, copy `env` to `.env`:
   ```
   Copy env .env
   ```

## Step 2: Configure Database Settings

Open the `.env` file and uncomment/update these lines:

```env
#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------

database.default.hostname = localhost
database.default.database = beacon_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306
```

**Important Notes:**
- `database.default.database = beacon_db` - Your database name
- `database.default.username = root` - Default XAMPP username (change if different)
- `database.default.password = ` - Leave empty if no password, or add your MySQL password
- `database.default.hostname = localhost` - Usually localhost for XAMPP
- `database.default.port = 3306` - Default MySQL port

## Step 3: Verify Connection

After updating the `.env` file, your CodeIgniter application will automatically use these settings.

## Alternative: Direct Database.php Configuration

If you prefer to configure directly in the config file, edit:
`app/Config/Database.php`

Update the `$default` array:
```php
public array $default = [
    'DSN'          => '',
    'hostname'     => 'localhost',
    'username'     => 'root',
    'password'     => '',  // Your MySQL password (if any)
    'database'     => 'beacon_db',
    'DBDriver'     => 'MySQLi',
    // ... rest of config
];
```

## Testing the Connection

You can test the connection by creating a simple test in your controller or by checking if the database queries work.

