# Event Booking API — Laravel 12

A RESTful API built with Laravel 12 for managing events, attendees, and bookings.

## Features

- Event CRUD (Create, Read, Update, Delete)
- Attendee Registration
- Event Booking with:
  - No duplicate bookings
  - No overbooking (enforces capacity limit)
- Structure for Authentication (not implemented)
- Proper Validation, Clean Architecture
- API Resources for clean responses
- Service Layer for business logic
- Testing support (Feature and Unit)

## Setup Instructions

### 1. Clone the Repository

```
git clone https://github.com/your-username/event-booking-api.git
cd event-booking-api
```

### 2. Install Dependencies

```
composer install
```

### 3. Configure Environment

```
cp .env.example .env
```

Edit `.env` and set your DB credentials:

```
DB_DATABASE=event_booking
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Generate App Key

```
php artisan key:generate
```

### 5. Run Migrations and Seeders

```
php artisan migrate --seed
```

Seeds a few sample countries for event location.

### 6. Start Development Server

```
php artisan serve
```

API runs at http://127.0.0.1:8000/api

## Running Tests

```
php artisan test
```

Ensure `.env.testing` uses a test DB (event_booking_test).

## API Endpoints

### Events

| Method | Endpoint              | Description         |
|--------|------------------------|---------------------|
| GET    | /api/events            | List all events     |
| POST   | /api/events            | Create an event     |
| GET    | /api/events/{id}       | Show event details  |
| PUT    | /api/events/{id}       | Update an event     |
| DELETE | /api/events/{id}       | Delete an event     |

### Attendees

| Method | Endpoint               | Description          |
|--------|------------------------|----------------------|
| GET    | /api/attendees         | List all attendees   |
| POST   | /api/attendees         | Register an attendee |
| PUT    | /api/attendees/{id}    | Update an attendee   |
| POST   | /api/attendees/{id}    | Show attendee details|
| DELETE | /api/attendees/{id}    | Delete an attendee   |

### Bookings

| Method | Endpoint               | Description                   |
|--------|------------------------|-------------------------------|
| POST   | /api/bookings          | Book an attendee to an event  |

## Payload Examples

### Create Event

POST /api/events

```json
{
  "title": "Laravel Conference 2025",
  "description": "A community event for Laravel developers.",
  "country_id": 1,
  "date": "2025-06-15",
  "capacity": 100
}
```

### Register Attendee

POST /api/attendees

```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "phone": "+1234567890"
}
```

### Book Event

POST /api/bookings

```json
{
  "attendee_id": 1,
  "event_id": 1
}
```

Validations ensure:
- The event is not full
- The attendee has not already booked the event

## Authentication

Not implemented, but the structure assumes:

- Authenticated users are required to create, update, or delete events
- Guests are allowed to register as attendees and book events

A future implementation can use Laravel Sanctum or Passport.

## Directory Structure

- app/Http/Controllers: Controllers
- app/Http/Requests: Validation classes
- app/Services: Business logic
- app/Models: Eloquent models
- app/Http/Resources: API response formatting
- tests/Feature: Feature tests
- database/factories: Model factories

## Docker Support

You can run the API in Docker containers using Laravel Sail or a custom docker-compose.yml.


## Docker Setup

### 1. Requirements

- Docker
- Docker Compose

### 2. Usage

Build and start the containers:

```
docker-compose up -d --build
```

### 3. Application Access

- API: http://localhost:8000/api

### 4. Artisan Commands

Run Artisan commands in the container:

```
docker exec -it app php artisan migrate
docker exec -it app php artisan test
```

### Event Bookin API Documentation

[View Postman Documentation](https://documenter.getpostman.com/view/44786553/2sB2j96UCg)