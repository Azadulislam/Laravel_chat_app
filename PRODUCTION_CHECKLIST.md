# Production Deployment Checklist

This list outlines the essential steps to prepare this Laravel application for a production environment.

## 1. Environment Configuration (.env)
- [ ] Set `APP_ENV=production`.
- [ ] Set `APP_DEBUG=false`.
- [ ] Ensure `APP_URL` is set to your production domain (e.g., `https://yourdomain.com`).
- [ ] Generate a fresh `APP_KEY` if not already set.
- [ ] Configure production Database credentials.
- [ ] Configure production Mail driver (e.g., SMTP, Mailgun, Postmark).
- [ ] Configure production Cache, Session, and Queue drivers (e.g., `redis` or `database`).

## 2. Security
- [ ] Enable SSL/HTTPS on the server.
- [ ] Set `SESSION_SECURE_COOKIE=true` in `.env`.
- [ ] Ensure all file permissions are correctly set (Storage and Cache folders must be writable by the web server).
- [ ] Verify that sensitive files (`.env`, `.git`) are not publicly accessible.

## 3. Performance Optimization
- [ ] Run `composer install --optimize-autoloader --no-dev`.
- [ ] Run `php artisan config:cache`.
- [ ] Run `php artisan route:cache`.
- [ ] Run `php artisan view:cache`.
- [ ] Run `php artisan event:cache`.
- [ ] Run `npm run build` to compile production assets via Vite.

## 4. Database & Storage
- [ ] Run `php artisan migrate --force` during deployment.
- [ ] Run `php artisan storage:link` to make public uploads accessible.
- [ ] Set up a database backup schedule.
- [ ] Verify that `storage/app/public` is persistent across deployments.

## 5. Real-time & Queues (Reverb)
- [ ] Configure Laravel Reverb for production (SSL, Port 443/8080).
- [ ] Set up a process manager like **Supervisor** to keep the queue worker (`php artisan queue:work`) and Reverb server (`php artisan reverb:start`) running.

## 6. Project Specific Tasks
- [ ] Verify `chat.auto_admin_groups` setting in `config/chat.php`.
- [ ] Ensure `TeamGroupSeeder` or necessary initial data has been seeded.
- [ ] Test the "Onboarding" flow in a production-like environment.
- [ ] Check that comment injection scripts are pointing to the correct production URLs.

## 7. Monitoring & Logging
- [ ] Set up error tracking (e.g., Sentry, Bugsnag, or Flare).
- [ ] Monitor server health (CPU, RAM, Disk).
- [ ] Ensure log rotation is enabled to prevent large log files.
