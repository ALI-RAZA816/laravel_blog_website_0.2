# SlowLiving – Blog CMS built with Laravel

SlowLiving is a full-featured blog platform with a public website and a role-based admin panel. Visitors can read posts, comment, subscribe to a newsletter and contact the team. Staff manage posts, categories, comments, users, messages and site settings from a dashboard.

The front end is server-rendered Blade pages enhanced with jQuery/AJAX. All data is served by JSON endpoints that live in the same Laravel application.

> **Author:** Ali Raza Mujahid  
> **Live demo:** _add your Laravel Cloud URL here_  
> **Repository:** _add your GitHub URL here_

---

## Table of contents

1. [Features](#features)
2. [Tech stack](#tech-stack)
3. [Roles and permissions](#roles-and-permissions)
4. [Screenshots](#screenshots)
5. [Getting started (local)](#getting-started-local)
6. [Environment variables](#environment-variables)
7. [Project structure](#project-structure)
8. [Database](#database)
9. [Routes and endpoints](#routes-and-endpoints)
10. [Security](#security)
11. [Queues, mail and real-time](#queues-mail-and-real-time)
12. [Testing](#testing)
13. [Deployment (Laravel Cloud)](#deployment-laravel-cloud)
14. [Roadmap](#roadmap)

---

## Features

### Public website
- Home page with latest posts, popular posts (by views), categories and pagination
- Single post page with rich-text content, author, category and tags
- Comments on posts (login required, shown publicly after moderation)
- Title search using MySQL FULLTEXT (with a LIKE fallback for very short words)
- Newsletter subscription with **double opt-in** (signed confirmation link) and one-click signed unsubscribe link
- Contact form that stores the message and emails the admins
- About and Contact pages
- Site title, logo, description, copyright and social links are loaded from the admin settings

### Authentication
- Registration, login and logout (session-based, "remember me" supported)
- Forgot password with a **6-digit OTP** sent by email (hashed in the database, valid for 2 minutes, limited attempts), then a reset step
- Blocked accounts are logged out automatically on their next request

### Admin panel
- **Dashboard** – totals for posts, comments, users and views, recent posts and comments, and a monthly views chart
- **Posts** – create, edit, delete and bulk delete; cover image upload, rich-text editor (Summernote), up to 5 tags, draft or published status, search and filters, monthly posts chart
- **Categories** – create, edit, delete (a category that still has posts cannot be deleted)
- **Comments** – approve, reject or mark as spam, filter by status, delete
- **Users** – create (with optional welcome email), edit, change role or status, upload avatar, delete; safety rules stop admins from demoting, blocking or deleting themselves or the last admin
- **Messages** – inbox for contact-form messages with read/unread state, reply by email and average response time; new messages appear in real time
- **Settings** – site title, description, copyright, logo and social links
- Newsletter email is sent to confirmed subscribers when a post is published

---

## Tech stack

| Layer | Technology |
| --- | --- |
| Backend | PHP 8.3+, Laravel 13 |
| Database | MySQL (FULLTEXT search requires MySQL) |
| Auth | Laravel session auth, Gates for roles, Laravel Sanctum installed |
| Front end | Blade, Bootstrap 5, jQuery (AJAX), Font Awesome |
| Editor / charts / safety | Summernote, Chart.js, DOMPurify |
| Real time | Laravel Reverb, Laravel Echo, Pusher JS |
| Queue / cache | Database driver (Redis/Valkey optional) |
| Mail | SMTP (any provider) |
| Tests | Pest |

---

## Roles and permissions

Permissions are defined as Gates in `app/Providers/AppServiceProvider.php` and applied to routes with `can:` middleware.

| Capability | Admin | Editor | Author | User |
| --- | :---: | :---: | :---: | :---: |
| Manage users | ✅ | | | |
| Manage site settings | ✅ | | | |
| Read and reply to messages | ✅ | | | |
| Moderate comments | ✅ | | | |
| Manage categories | ✅ | ✅ | | |
| See and edit **all** posts | ✅ | ✅ | | |
| Create and edit **own** posts | ✅ | ✅ | ✅ | |
| View all comments | ✅ | ✅ | | |
| Write, edit and delete own comments | ✅ | ✅ | ✅ | ✅ |

New visitors who register get the **user** role. Only an admin can assign other roles.

---

## Screenshots

_Add screenshots here, for example:_

```
docs/screenshots/home.png
docs/screenshots/post.png
docs/screenshots/dashboard.png
docs/screenshots/messages.png
```

---

## Getting started (local)

### Requirements
- PHP 8.3 or newer (with `pdo_mysql`, `mbstring`, `gd`, `openssl`)
- Composer
- MySQL 8+ (or MariaDB 10.5+)
- Node.js 20+ and npm (only needed for the optional Vite build)

### Installation

```bash
# 1. Clone and enter the project
git clone <your-repo-url> slowliving
cd slowliving

# 2. Install PHP dependencies
composer install

# 3. Create the environment file and app key
cp .env.example .env
php artisan key:generate
```

### Configure the database
Create an empty MySQL database, then edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=slowliving
DB_USERNAME=root
DB_PASSWORD=
```

> The default `.env.example` uses SQLite. Switch to MySQL, because the post search relies on a FULLTEXT index.

### Migrate and seed

```bash
php artisan migrate
php artisan db:seed      # optional: demo users, categories, posts, comments, etc.
```

The seeder also creates placeholder images in `public/posts-images` and `public/uploads` (needs the GD extension).

**Demo login (local only):** `admin@example.com` with the password from `SEED_PASSWORD` in `.env` (default `password`). All seeded users share this password, so **never run the seeder in production**.

### Run the app

```bash
php artisan serve                 # http://localhost:8000
php artisan queue:work            # in a second terminal, sends emails
php artisan reverb:start          # in a third terminal, optional real-time inbox
```

The pages do not use the Vite bundle, so `npm run build` is optional.

---

## Environment variables

| Variable | Purpose |
| --- | --- |
| `APP_NAME`, `APP_URL` | Site name and base URL. `APP_URL` must match the real URL, because signed newsletter links use it |
| `APP_ENV`, `APP_DEBUG` | Use `production` and `false` in production |
| `DB_*` | MySQL connection |
| `SESSION_DRIVER` | `database` or Redis/Valkey in production (needs the `sessions` table for `database`) |
| `SESSION_SECURE_COOKIE` | Set to `true` when serving over HTTPS |
| `QUEUE_CONNECTION` | `database` (default) – requires a running queue worker |
| `CACHE_STORE` | `database` or Redis/Valkey |
| `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_SCHEME`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` | Outgoing mail (OTP, replies, newsletter, welcome emails) |
| `BROADCAST_CONNECTION` | `reverb` for real-time messages, `null` to disable broadcasting |
| `REVERB_APP_ID`, `REVERB_APP_KEY`, `REVERB_APP_SECRET`, `REVERB_HOST`, `REVERB_PORT`, `REVERB_SCHEME` | Reverb WebSocket server |
| `SEED_PASSWORD` | Password given to demo users by the seeder (local only) |

Never commit your `.env` file. Use a mail provider **app password** or API key, and rotate it immediately if it is ever exposed.

---

## Project structure

```
app/
  Events/                  Domain events (new message, subscribed user, user registered...)
  Listeners/               Queued listeners that send emails
  Http/
    Controllers/           Public controllers (posts, comments, newsletter, password reset...)
    Controllers/Admin/     Admin controllers (posts, users, categories, comments, messages, settings, dashboard)
    Middleware/            EnsureUserIsActive, RoleMiddleware
    Requests/              Form Request validation classes
  Mail/                    Mailable classes (OTP, reply, newsletter, welcome, new message)
  Models/                  Eloquent models
  Providers/               AppServiceProvider (rate limiters and authorization Gates)
  Support/ImageUpload.php  Helper for saving and deleting uploaded images
bootstrap/app.php          Middleware, routing and exception setup
config/                    Framework and package configuration
database/
  migrations/              Schema
  seeders/                 DatabaseSeeder and JSON demo data (seeders/data)
public/
  css/                     Page and layout stylesheets
  js/                      jQuery/AJAX scripts, one per feature
resources/views/
  layouts/                 app.blade.php (public) and admin.blade.php (admin)
  components/              header and footer
  pages/                   Public and admin pages
  mail/                    Email templates
routes/
  web.php                  All web and JSON endpoints
  channels.php             Broadcast channel authorization
tests/                     Pest tests
```

---

## Database

| Table | Description |
| --- | --- |
| `users` | Accounts with `role` (admin, editor, author, user) and `status` (active, inactive, blocked) |
| `categories` | Name, unique slug, icon, description, post count |
| `posts` | Title, HTML content, cover image, tags (JSON), `published` (published or draft), views counter; FULLTEXT index on title |
| `comments` | Text, `status` (pending, approved, rejected, spam), linked to user and post |
| `post_views` | One row per user per post, used to count unique views |
| `monthly_reports` | Posts per author per month (post charts) |
| `last_months_views` | Monthly views snapshots (dashboard chart) |
| `messages` | Contact-form messages, read state and reply information |
| `newsletters` | Subscribers with `confirmed_at` |
| `reset_passwords` | Hashed OTP, expiry and verification state |
| `settings` | Single row of site settings |
| `personal_access_tokens`, `jobs`, `failed_jobs`, `cache` | Sanctum, queue and cache support |

Deleting a category that still has posts is blocked by a `RESTRICT` foreign key. Deleting a user deletes that user's posts and comments.

---

## Routes and endpoints

All routes are in `routes/web.php`.

### Public pages
`/`, `/about`, `/contact`, `/login`, `/register`, `/single-view?post={id}`, `/forgotton`, `/verify`, `/reset-password`

### Public JSON endpoints

| Method | Path | Description |
| --- | --- | --- |
| GET | `/public-setting` | Site title, logo, social links |
| GET | `/public-post?page=&query=` | Published posts (paginated) and popular posts |
| GET | `/public-category` | Categories with post counts |
| GET | `/public-single-post/{id}` | One published post |
| GET | `/fetch-post-comments/{id}` | Approved comments of a post |
| POST | `/send-message` | Contact form |
| POST | `/newsletter-subscribe` | Start newsletter subscription |
| GET | `/newsletter/confirm/{id}` | Signed confirmation link |
| GET | `/newsletter/unsubscribe/{id}` | Signed unsubscribe link |

### Authentication

| Method | Path | Description |
| --- | --- | --- |
| POST | `/registerUser` | Create account |
| POST | `/loginUser` | Log in |
| POST | `/logoutUser` | Log out (any logged-in user) |
| POST | `/forgot-password` | Send OTP |
| POST | `/verify-otp` | Verify OTP |
| POST | `/resetpassword` | Set a new password |
| GET | `/fetch-record` | Current password-reset state |

### Admin and staff endpoints (login and permission required)

| Area | Permission | Endpoints |
| --- | --- | --- |
| Users | `manage-users` | `/users`, `/add-user`, `/edit-user`, `/addUser`, `/fetchusers`, `/fetchedituser/{id}`, `/edituser/{id}`, `/deleteUser/{id}` |
| Settings | `manage-settings` | `/setting`, `/savesettings`, `/fetchsettings`, `/removelogo` |
| Messages | `manage-messages` | `/messages`, `/fetch-messages`, `/fetch-single-message/{id}`, `/mark-as-read/{id}`, `/mark-as-unread/{id}`, `/delete-message/{id}`, `/send-reply` |
| Comment moderation | `moderate-comments` | `/update-comment-status/{id}` |
| Categories | `manage-categories` | `/categories`, `/addCategory`, `/fetch-single-category/{id}`, `/updateCategory/{id}`, `/deleteCategory/{id}` |
| Dashboard and posts | `write-posts` | `/dashboard`, `/dashboardData`, `/posts`, `/add-post`, `/edit-post`, `/post-preview`, `/addpost`, `/fetchpost`, `/fetchSingle/{id}`, `/updatepost/{id}`, `/deletepost/{id}`, `/deleteposts` |
| Comments | `use-comments` | `/comments`, `/fetchComment`, `/add-comment`, `/update-comment/{id}`, `/delete-comment/{id}` |

A health check is available at `/up`.

### Rate limits

| Limiter | Limit |
| --- | --- |
| Public pages | 120 requests/min per IP |
| Public read APIs | 60 requests/min per IP |
| Login, register, password reset | 5/min per email and IP, 30/min per IP |
| Contact form and newsletter | 3/min and 30/day per IP |
| Logged-in panel | 120/min per user |
| Comments | 10/min per user |

---

## Security

- CSRF protection on every state-changing request (token sent in the AJAX header)
- Authorization through Gates and `can:` middleware, plus Form Request `authorize()` checks
- Authors can only see and edit their own posts; other people's posts return 404
- Passwords hashed with bcrypt; password-reset OTPs are hashed and expire after 2 minutes, with limited attempts
- Password-reset and newsletter responses do not reveal whether an email exists
- Rate limiting on public, authentication and form endpoints
- Newsletter confirmation and unsubscribe links are **signed URLs**
- Uploaded images are validated by type (JPG/PNG) and size (3 MB) and stored under hashed file names
- Post HTML is sanitized with DOMPurify before it is displayed
- User-supplied text must be HTML-escaped before it is inserted into the page (use the `esc()` helper in `public/js/helper.js`)
- Blocked users are logged out by the `EnsureUserIsActive` middleware

Before going live, set `APP_DEBUG=false`, use HTTPS with `SESSION_SECURE_COOKIE=true`, rotate any secret that was ever shared, and do **not** run the demo seeder.

---

## Queues, mail and real-time

- These actions are queued and need `php artisan queue:work`: reply to a contact message, notify admins of a new message, welcome email for new users, newsletter email when a post is published.
- The password-reset OTP and the newsletter confirmation email are sent immediately.
- Real-time admin notifications use Laravel Reverb on the private channel `admin-messages` (authorized for admins only). Set `BROADCAST_CONNECTION=null` to turn real-time updates off; everything else keeps working.

---

## Testing

The project uses [Pest](https://pestphp.com):

```bash
php artisan test
```

At the moment only the default example tests exist. Feature tests for authentication, permissions (Gates) and the post workflow are on the roadmap.

---

## Deployment (Laravel Cloud)

1. Push the project to GitHub and make sure `.env` is **not** committed.
2. Create an application in [Laravel Cloud](https://cloud.laravel.com) and connect the repository.
3. Attach a **MySQL** database (the search needs FULLTEXT support).
4. Set environment variables: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://your-domain`, `SESSION_DRIVER=database`, `SESSION_SECURE_COOKIE=true`, `QUEUE_CONNECTION=database`, `CACHE_STORE=database`, `LOG_LEVEL=warning`, `BROADCAST_CONNECTION=null` (or attach a WebSockets resource), and your mail credentials.
5. Build command: `composer install --no-dev && npm ci && npm run build`
6. Deploy command: `php artisan migrate --force`
7. Add a background process: `php artisan queue:work --tries=3`
8. Create your first admin account (do not run the demo seeder in production).

**File storage note:** Laravel Cloud's filesystem is ephemeral, so files written to `public/uploads` and `public/posts-images` are lost on every deploy. For production, store uploads in Laravel Cloud Object Storage (S3-compatible) instead of the local disk.

---

## Roadmap

- [ ] Move image uploads to object storage
- [ ] Feature tests for auth, roles and posts
- [ ] Server-side rendering and meta tags for posts (better SEO and link previews)
- [ ] Email verification for new accounts
- [ ] Enforce the "maintenance mode" switch from the settings page
- [ ] Replace `alert()` messages with toast notifications
- [ ] Count views from anonymous visitors
- [ ] Bundle front-end libraries with Vite instead of CDN links

---

## License

_Choose a license (for example MIT) and add a `LICENSE` file, or remove this section._