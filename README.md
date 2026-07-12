# LiveChat Platform

LiveChat is a modern Laravel-based collaboration platform designed for teams, project stakeholders. It helps users review projects, leave contextual feedback, discuss updates, and manage approvals in a structured and professional environment.

## Overview

This application is built to make collaboration easier for internal teams, HR departments, project leads, and reviewers. Instead of relying on scattered emails or informal chat, users can work directly inside a centralized platform where feedback is tied to specific project items and can be discussed in real time.

## Why this project matters

The platform is especially useful for organizations that need:

- Clear communication around internal projects and documents
- Structured feedback from multiple reviewers
- HR-friendly collaboration for onboarding, approval, and internal processes
- A streamlined workflow for comments, discussions, and decisions

## Key Features

- Project-based review workspace
- Contextual commenting directly on project pages
- Threaded replies for detailed discussions
- Approval and rejection workflow for feedback items
- Mention support for direct collaboration
- Real-time chat with conversations and group messaging
- Read receipts and typing indicators for active communication
- Onboarding flow for new users
- Admin dashboard for managing users and projects
- Modern interface powered by Tailwind CSS and Vite

## Use Cases

This project is well suited for HR and internal operations teams to:

- Review onboarding materials and internal documents
- Gather stakeholder feedback in a structured way
- Coordinate reviews across departments
- Keep communication organized and traceable
- Reduce manual back-and-forth during routine approvals

## Technology Stack

- PHP 8.3
- Laravel 13
- MySQL-compatible database support
- Redis for cache and session support
- Vite for frontend assets
- Tailwind CSS for styling
- Reverb for real-time features
- PHPUnit for testing

## Installation

1. Clone the repository
2. Install PHP dependencies:

```bash
composer install
```

3. Create your environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database connection and Redis cache settings in the .env file.

5. Run database migrations:

```bash
php artisan migrate
```

6. Install frontend dependencies and build assets:

```bash
npm install
npm run build
```

7. Start the application:

```bash
php artisan serve
```

## Development Commands

Run tests:

```bash
php artisan test
```

Start queue worker if needed:

```bash
php artisan queue:listen
```

Start real-time broadcasting support:

```bash
php artisan reverb:start
```

If you are using Redis for caching, make sure your Redis server is running and your .env file has the appropriate cache/Redis configuration.

## Project Structure

- app/Models: Core models such as Project, Comment, User, and Conversation
- app/Http/Controllers: Request handling for projects, comments, chat, onboarding, and admin tools
- app/Policies: Access rules and authorization logic
- database/migrations: Database schema for users, projects, comments, chat, and related entities
- resources/views: UI templates for the application
- routes/web.php: Main application routes
- tests: Automated test coverage

## License

This project is open-source and licensed under the MIT License.
