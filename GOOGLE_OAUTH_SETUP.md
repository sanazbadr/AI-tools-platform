# Google OAuth2 Setup Guide for AI Tools Archeoam

## Overview
This guide will help you set up Google OAuth2 authentication for your AI Tools Archeoam application. The system now supports both Google authentication and the existing Archeoam login system.

## Step 1: Create Google OAuth2 Credentials

### 1.1 Go to Google Cloud Console
1. Visit [Google Cloud Console](https://console.cloud.google.com/)
2. Sign in with your Google account
3. Create a new project or select an existing one

### 1.2 Enable Google+ API
1. Go to "APIs & Services" > "Library"
2. Search for "Google+ API" or "Google Identity"
3. Click on "Google Identity" and enable it

### 1.3 Create OAuth2 Credentials
1. Go to "APIs & Services" > "Credentials"
2. Click "Create Credentials" > "OAuth 2.0 Client IDs"
3. Choose "Web application" as the application type
4. Fill in the following details:
   - **Name**: AI Tools Archeoam
   - **Authorized JavaScript origins**:
     ```
     https://ai.archeoam.com
     ```
   - **Authorized redirect URIs**:
     ```
     https://ai.archeoam.com/auth/google/callback
     ```
5. Click "Create"

### 1.4 Copy Credentials
After creation, you'll get:
- **Client ID**: Copy this
- **Client Secret**: Copy this

## Step 2: Update Environment Variables

### 2.1 Update .env File
Replace the placeholder values in your `.env` file:

```env
# Google OAuth2 Configuration
GOOGLE_CLIENT_ID=your_actual_google_client_id_here
GOOGLE_CLIENT_SECRET=your_actual_google_client_secret_here
GOOGLE_REDIRECT_URI=https://ai.archeoam.com/auth/google/callback
```

### 2.2 Clear Laravel Cache
Run these commands on your server:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Step 3: Test the Implementation

### 3.1 Test Google Login
1. Visit `https://ai.archeoam.com/login`
2. Click on "Google Login" tab
3. Click "Continue with Google"
4. You should be redirected to Google's consent screen
5. After authorization, you should be redirected back to the dashboard

### 3.2 Test Archeoam Login
1. Click on "Archeoam Login" tab
2. Use existing Archeoam credentials
3. Verify that the original login still works

## Step 4: Database Verification

### 4.1 Check Database Structure
Verify that the migration ran successfully:

```sql
DESCRIBE users;
```

You should see these new columns:
- `google_id` (VARCHAR, nullable, unique)
- `avatar` (VARCHAR, nullable)

### 4.2 Test User Creation
After a successful Google login, check the database:

```sql
SELECT id, name, email, google_id, avatar, created_at FROM users WHERE google_id IS NOT NULL;
```

## Step 5: Security Considerations

### 5.1 HTTPS Requirement
- Google OAuth2 requires HTTPS
- Ensure your domain has a valid SSL certificate
- The redirect URI must use HTTPS

### 5.2 Environment Variables
- Never commit real credentials to version control
- Use environment variables for all sensitive data
- Keep your Google Client Secret secure

### 5.3 Session Security
- Sessions are automatically managed by Laravel
- Users can log out from both authentication methods
- Session data includes authentication type for tracking

## Step 6: Troubleshooting

### 6.1 Common Issues

**Error: "redirect_uri_mismatch"**
- Check that the redirect URI in Google Console matches exactly
- Ensure no trailing slashes or extra characters

**Error: "invalid_client"**
- Verify Client ID and Client Secret are correct
- Check that the OAuth2 credentials are for a web application

**Error: "access_denied"**
- User may have denied permission
- Check Google Console for any API restrictions

### 6.2 Debug Mode
If you need to debug, temporarily enable debug mode:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Check the Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

## Step 7: Production Deployment

### 7.1 Final Checklist
- [ ] Google OAuth2 credentials configured
- [ ] Environment variables updated
- [ ] HTTPS certificate installed
- [ ] Database migration completed
- [ ] Both login methods tested
- [ ] Error handling verified
- [ ] Logs monitored for issues

### 7.2 Monitoring
- Monitor Laravel logs for authentication errors
- Check Google Cloud Console for API usage
- Verify user creation in database
- Test logout functionality

## Features Implemented

### ✅ Google Authentication
- OAuth2 flow with Google
- Automatic user creation
- Profile picture and email verification
- Session management

### ✅ Dual Authentication System
- Google login (default tab)
- Archeoam login (existing system)
- Tab-based interface
- Seamless switching

### ✅ User Management
- Automatic plan assignment (Basic for Google users)
- Avatar support
- Email verification for Google users
- Backward compatibility with existing users

### ✅ Security
- CSRF protection
- Session security
- HTTPS requirement
- Environment variable protection

## Support

If you encounter any issues:
1. Check the Laravel logs: `storage/logs/laravel.log`
2. Verify Google Console settings
3. Test with debug mode enabled
4. Ensure all environment variables are set correctly

The system is now ready for production use with both Google and Archeoam authentication methods! 