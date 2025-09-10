# Railway Deployment Guide

## Quick Deploy to Railway

1. **Connect Repository to Railway**
   - Go to https://railway.app
   - Create new project
   - Connect your GitHub repository

2. **Add MySQL Database**
   - In Railway dashboard, click "New" → "Database" → "Add MySQL"
   - Railway will automatically set environment variables

3. **Set Environment Variables**
   Railway will auto-detect most variables, but make sure these are set:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-app-name.up.railway.app
   ```

4. **Deploy**
   - Railway will automatically build and deploy
   - The `release` command in Procfile will run migrations
   - Your app will be available at the Railway URL

## Files for Railway:
- ✅ `nixpacks.toml` - Build configuration  
- ✅ `Procfile` - Process configuration with auto-migration
- ✅ `.env.production` - Production environment template
- ✅ Database migrations will run automatically

## Post-Deploy:
- Check Railway logs for any issues
- Visit your app URL to verify deployment
- Database tables will be created automatically
