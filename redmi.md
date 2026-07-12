# Comment Platform

Comment is a modern Laravel-based collaboration platform designed for teams, project stakeholders, and HR-focused workflows. It helps users review projects, leave contextual feedback, discuss updates, and manage approvals in a structured and professional environment.

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

## HR-Focused Use Cases

This project is well suited for HR and internal operations teams to:

- Review onboarding materials and internal documents
- Gather stakeholder feedback in a structured way
- Coordinate reviews across departments
- Keep communication organized and traceable
- Reduce manual back-and-forth during routine approvals

## Technology Stack

- PHP 8.3
- Laravel 13
- Vite
- Tailwind CSS
- Reverb
- PHPUnit

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

## Development Notes

- Use php artisan test to run the test suite.
- Use php artisan queue:listen for background jobs.
- Use php artisan reverb:start for real-time communication features.
