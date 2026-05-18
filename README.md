# CEED Startup Showcase Portal

## Current System Description
The CEED Startup Showcase Portal is a PHP MVC web application for collecting, showcasing, and managing student-led startup profiles at the University of Gondar. Students register and submit startups with media, update their profiles, and interact with public feedback. Admins review and moderate content through a dedicated dashboard.

The system is designed as a learning project for PHP students: it demonstrates routing, controllers, views, database access, authentication, file uploads, and basic moderation workflows in a realistic scenario.

## Major Functions of the System
- Student registration and login
- Startup submission with media uploads
- Startup editing and deletion by the owner
- Public showcase with category filtering
- Public founder profiles
- Public comments on startup profiles
- Admin moderation of startups, users, and comments

## Security

### Key Controls
- **CSRF protection**: all POST forms include a CSRF token
- **Role-based access**: admin-only routes guarded by session checks
- **Ownership checks**: students can edit/delete only their own startups
- **Upload validation**: file type checks and size limits
- **Session protection**: session IDs regenerated on login/logout

### Known Limitations (Educational Scope)
- No email verification
- No password reset
- No rate limiting or CAPTCHA

### Recommended Hardening (If Deployed Publicly)
- Enforce HTTPS
- Move DB credentials to environment variables
- Add server-side rate limiting
- Add audit logging for admin actions

## Usability

### Target Users
- **Students**: submit and manage startups
- **Public visitors**: browse and comment
- **Admins**: moderate users and content

### UX Highlights
- Tailwind UI for consistent visual design
- Clear CTA buttons and forms
- Public showcase with category filters
- Student dashboard with profile summary

## Activities of the System

### Student Flow
1. Register and log in
2. Submit a startup with media
3. View/edit/delete their startup
4. Update personal profile and avatar

### Public Flow
1. Browse the showcase
2. Filter by category
3. View startup profile
4. Leave a comment

### Admin Flow
1. Log in
2. Review startups
3. Restrict/promote users
4. Remove inappropriate comments

## Software Architecture

### Architecture Diagram
```
Browser
    |
    v
index.php (Front Controller)
    |
    v
Controllers (Public / Student / Admin)
    |\
    | \
    |  v
    |  Repositories (PDO)
    |    |
    |    v
    |  MariaDB
    |
    v
Views (Tailwind UI)
    |
    v
Layouts
    |
    v
Browser
```

### Request Flow (Text Diagram)
```
Browser
  -> index.php (front controller)
      -> Controller (Public / Student / Admin)
          -> Repository (PDO)
              -> MariaDB (XAMPP)
          -> View (Tailwind UI)
              -> Layout (public/admin)
```

### Layered Components
| Layer | Responsibility | Key Files |
| --- | --- | --- |
| Routing | Map route query to controller | `index.php` |
| Controller | Orchestrate request flow | `src/Frontend/Controller/*`, `src/Admin/Controller/*` |
| Repository | Database access | `src/Repository/*` |
| View | UI rendering | `views/*` |
| Support | CSRF, uploads, auth | `src/Support/*`, `src/Admin/Support/*` |

## System Decomposition

### Core Modules
- **Public Module**: showcase, founder profile, startup profile
- **Student Module**: registration, login, dashboard, submit, edit, profile
- **Admin Module**: moderation and management
- **Media Module**: file uploads and media records
- **Security Module**: CSRF + session handling

### Data Model
Primary tables:
- `users`
- `startups`
- `media`
- `comments`
- `categories`

### Database Relationships
- `users (1) -> startups (many)` via `startups.owner_id`
- `categories (1) -> startups (many)` via `startups.category_id`
- `startups (1) -> media (many)` via `media.startup_id`
- `startups (1) -> comments (many)` via `comments.startup_id`

## Folder Structure
```
35 - cms/12 - Part 12/
    index.php
    inc/
    src/
        Admin/
        Frontend/
        Repository/
        Support/
        Model/
    views/
        layouts/
        public/
        frontend/startups/
        student/
        admin/
    uploads/
    storage/logs/
```

## Hardware / Software Mapping

| Area | Technology |
| --- | --- |
| Frontend | HTML, Tailwind CSS, Ubuntu font |
| Backend | PHP (MVC), PDO |
| Database | MariaDB (XAMPP) |
| Server | Apache (XAMPP) |
| OS (local) | Windows |

## Setup (Local)

### Prerequisites
- XAMPP (Apache + MariaDB)
- PHP 8.x (bundled with XAMPP)
- Browser

### Install
1. Place project in: `C:\xampp\htdocs\ContentManagementSystem`
2. Start Apache and MySQL in XAMPP
3. Create DB `cms` in phpMyAdmin
4. Import schema: `35 - cms/12 - Part 12/ceed_schema.sql`

### Run
```
http://localhost/ContentManagementSystem/35%20-%20cms/12%20-%20Part%2012/index.php?route=showcase
```

## Configuration

### Key Files
- DB connection: `35 - cms/12 - Part 12/inc/db-connect.inc.php`
- CSRF helper: `35 - cms/12 - Part 12/src/Support/CsrfHelper.php`
- Uploads: `35 - cms/12 - Part 12/src/Support/UploadService.php`

## Testing

This project currently uses manual tests.

Suggested checks:
- Register/login/logout
- Submit startup with media
- Edit and delete startup
- Comment on startup
- Admin moderation

## Non-Functional Requirements
- **Performance**: support concurrent demo users with small media uploads
- **Availability**: local demo uptime during lab sessions
- **Security**: CSRF protection and access checks on sensitive routes
- **Usability**: clear navigation, readable forms, and feedback messages
- **Maintainability**: modular controllers and repositories
- **Portability**: runs on XAMPP (Windows) and standard PHP hosting

## Future Improvements
- Add automated tests (unit + integration)
- Add email verification and password reset
- Add pagination for showcase and comments
- Add rate limiting for public comments
- Add admin audit logs
- Add image optimization for uploads

## License and Credits

Educational use only. Add a license if you plan to distribute or open-source.

Credits:
- University of Gondar CEED project concept
- Tailwind CSS for UI styling
