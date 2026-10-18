# Financy Backend — Mini Finance ERP

A finance-focused backend application built with Laravel, designed to handle customer invoices, payment processing, payment provider webhooks, and financial reporting. The project is being developed incrementally toward a Mini ERP, with a focus on reliable payment workflows, data consistency, and backend engineering best practices.

## Overview

Financy Backend is a practical backend engineering project that simulates core financial operations in a business application.

It provides APIs for managing customers, invoices, payments, and payment webhook events. It integrates with Tap Payments in test mode and processes webhook events through Laravel's queue system.

The next development phase will introduce accounting capabilities, including journal entries and double-entry bookkeeping, to connect operational transactions with accounting records.

## Tech Stack

- **Language:** PHP 8.4
- **Backend Framework:** Laravel 13
- **Database:** PostgreSQL
- **Authentication:** Laravel API token authentication
- **Payments:** Tap Payments integration (test mode)
- **Queues:** Laravel Queue, database driver, background jobs
- **Frontend Integration:** RESTful APIs consumed by a React dashboard
- **Data Tables:** Yajra Laravel DataTables for server-side processing
- **Infrastructure:** Docker, Redis
- **API Testing:** Payment provider test flows and webhook integration testing

## Implemented Features

### 1. Authentication

- API-based authentication using Laravel tokens.
- Protected API endpoints.
- Structured API responses.

### 2. Customer Management

- Customer records with UUIDs.
- Customer contact information and address.
- Active/inactive status.
- Soft deletion support.

### 3. Invoice Management

- Invoice creation and customer relationships.
- Invoice numbers, issue dates, and due dates.
- Subtotal, tax, discount, total, and paid amount tracking.
- Invoice lifecycle statuses:
  - Draft
  - Issued
  - Partially Paid
  - Paid
  - Cancelled
  - Overdue
- Server-side DataTables with pagination, searching, and filtering.

### 4. Payment Processing

- Payment records associated with invoices.
- Integration with Tap Payments in test mode.
- Payment status tracking.
- Idempotency protection for payment creation.
- Transaction-safe payment processing.
- Database locking where needed to protect invoice and payment state transitions.

### 5. Webhook Processing

- Receives payment status events from Tap Payments.
- Validates and processes payment provider events.
- Uses Laravel background jobs to process webhook events asynchronously.
- Protects against duplicate webhook processing.
- Updates payment and invoice states after successful payment confirmation.

### 6. Background Jobs

- Queue-based webhook processing.
- Background job execution through Laravel queue workers.
- Supervisor-based worker management in the development environment.

### 7. Payment Webhook Management

- Persistent webhook event records.
- Webhook event tracking and processing status.
- Server-side DataTables for searching, filtering, and pagination.

### 8. Dashboard Summary

- Customer and invoice counts.
- Paid and pending invoice statistics.
- Payment counts and payment status statistics.
- Paid amounts grouped by currency.

## Payment Processing Flow

The payment workflow follows this sequence:

1. A payment request is submitted through the API.
2. The backend validates the request and applies idempotency protection.
3. A payment record is created and the payment provider flow is initiated.
4. Tap Payments processes the payment in its test environment.
5. Tap sends a webhook event to the backend.
6. The webhook event is recorded and dispatched to a background job.
7. The job processes the event while protecting against duplicate processing.
8. The payment and associated invoice are updated after successful confirmation.

```text
Client
  |
  v
Laravel Payment API
  |
  v
Payment Record
  |
  v
Tap Payments
  |
  v
Webhook Endpoint
  |
  v
Webhook Event Record
  |
  v
Laravel Queue Job
  |
  v
Payment & Invoice Update
```

## Engineering Principles

The project emphasizes practical implementation of backend engineering concepts:

- **Idempotency:** Preventing duplicate payment operations and repeated webhook effects.
- **Database Transactions:** Maintaining consistency across related database changes.
- **Concurrency Control:** Using database locks where necessary to protect financial state transitions.
- **Asynchronous Processing:** Moving webhook processing into background jobs.
- **RESTful API Design:** Keeping backend resources accessible through structured APIs.
- **Server-side DataTables:** Handling searching, filtering, and pagination at the database level.
- **Separation of Concerns:** Organizing payment processing and webhook logic into dedicated application components.

## Project Structure

The application uses Laravel's standard structure, with dedicated components for business logic and background processing.

```text
app/
├── Http/
│   ├── Controllers/
│   └── Resources/
├── Jobs/
├── Models/
└── Services/

database/
├── migrations/
└── seeders/

routes/
└── api.php
```

The exact directory structure may evolve as the project grows.

## Getting Started

### Prerequisites

- Docker and Docker Compose
- Git
- Tap Payments test credentials

### Installation

1. Clone the repository:

   ```bash
   git clone <YOUR_GITHUB_REPOSITORY_URL>
   cd <YOUR_PROJECT_DIRECTORY>
   ```

2. Create your local environment file:

   ```bash
   cp .env.example .env
   ```

3. Configure your database, queue connection, and payment provider credentials in `.env`.

4. Start the Docker environment:

   ```bash
   docker compose up -d --build
   ```

5. Install dependencies and generate the application key using the appropriate PHP container or local PHP environment:

   ```bash
   composer install
   php artisan key:generate
   ```

6. Run database migrations:

   ```bash
   php artisan migrate
   ```

7. Configure a queue worker and, where applicable, Supervisor to process background jobs.

> **Note:** Adjust the commands to match the repository's Docker Compose services and existing environment configuration. Never commit real payment credentials or other secrets to GitHub.

## API Overview

The API is versioned under the `v1` prefix.

The implemented API functionality covers:

- Authentication
- Customer management
- Invoice management
- Payment processing
- Payment webhook processing
- Dashboard summary statistics

Refer to the API routes and request validation rules in the repository for the exact endpoint paths, HTTP methods, and request parameters.

## Roadmap

The next planned development phases are:

- [ ] Complete and strengthen automated tests for payment and webhook workflows.
- [ ] Introduce a Chart of Accounts.
- [ ] Implement Journal Entries and Journal Entry Lines.
- [ ] Implement double-entry bookkeeping with debit/credit validation.
- [ ] Automatically generate accounting entries for eligible financial transactions.
- [ ] Add general ledger and account balance reporting.
- [ ] Add financial reports and reconciliation workflows.
- [ ] Improve production readiness, monitoring, and operational reliability.

## Project Status

**Current stage:** Core payment processing and invoice management.

Customer management, invoice and payment DataTables, webhook tracking, Tap test-mode payment processing, queued webhook handling, and dashboard summary functionality have been implemented.

Accounting functionality and the broader ERP capabilities are planned for subsequent development phases.

## Author

**Mohamed Reda Ammar**
Senior PHP / Laravel Backend Developer

- Portfolio: [https://mohamedammar94.github.io/portfolio/](https://mohamedammar94.github.io/portfolio/)
- LinkedIn: [https://www.linkedin.com/in/mohamed-ammar-428066130/](https://www.linkedin.com/in/mohamed-ammar-428066130/)

---

This project is being developed as a practical demonstration of backend architecture, payment integration, database consistency, asynchronous processing, and financial system design.
