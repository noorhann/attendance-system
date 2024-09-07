# Attendance System Backend
The Attendance System is a simple backend API for tracking employee attendance. The system allows users to check in and out multiple times a day, track total hours worked within a specified time frame, and receive notifications with their monthly working statistics.

## Installation
Ensure you have Docker installed.

```bash
docker-compose up -d
```
#### Accessing MySQL through PHPMyAdmin:

After running the Docker containers, you can access PHPMyAdmin to manage the database.
PHPMyAdmin should be available at 
```bash
http://localhost:8080
```
#### running Migrations inside Docker:

You may need to run migrations inside the Docker container by executing:


```bash
docker exec laravel_app php artisan migrate
```

#### Create .env file:

Copy .env.example to .env and configure your database and other services:

```bash
cp .env.example .env
```

#### Update .env file

Set your database credentials and other environment variables
```bash
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=attendance
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=b9e0664b9d3d4b
MAIL_PASSWORD=cb54ed50fab92b
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```
## API Endpoints

| Method | Endpoint            | Description                             |
|--------|---------------------|-----------------------------------------|
| POST   | `/api/user/login`         | User login                              |
| POST   | `/api/user/checkIn`      | Check-in the user                       |
| POST   | `/api/user/checkOut`     | Check-out the user                      |
| GET    | `/api/user/getTotalHours`    | Get the user's total hours between two given dates          |


