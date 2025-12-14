# ice_cream_shop

🍦 Ice Cream Shop (Specijal) – Web Application

Single Page Application (SPA) with PHP (FlightPHP), MySQL, JavaScript, HTML, CSS

📌 Project Overview

This is a fully functional single-page Ice Cream Shop Web Application built using:

PHP + FlightPHP (REST backend)

MySQL (database with 5+ entities)

JavaScript (SPA routing)

HTML/CSS/Bootstrap (frontend)

AJAX-ready architecture

OpenAPI + Swagger documentation

✅ Milestones

Milestone 1 – Project Setup & Static Frontend
✔️ Achievements:

Created full folder structure.
Implemented Single Page Application using hash-based routing (#home, #products, etc.).

Integrated Bootstrap for responsive design.

Implemented static:
    Home
    Products
    About
    Contact
    Login
    Register

-Added ERD diagram (docs/erd.png).

-Created .env file for database configuration.

Milestone 2 – Database & DAO Layer
✔️ Achievements:

-Designed relational MySQL schema with 6 entities:
    users
    categories
    products
    product_categories (M:N)
    orders
    reviews

-Created ice_cream_data.sql containing:
    Database creation
    Table definitions
    Foreign key constraints
    Seed data

Implemented full DAO layer for:
    Users
    Categories
    Products
    Orders
    Reviews

CRUD operations available in every DAO:
    create
    getById
    getAll
    update
    delete

Milestone 3 – Services, Business Logic & OpenAPI
✔️ Achievements:

-Added Service Layer for each entity:
    Data validation
    Business logic
    Error handling
    Mapping request → DAO

-Implemented full REST API using FlightPHP:
    /api/users
    /api/products
    /api/categories
    /api/orders
    /api/reviews
    (All support GET/POST/PUT/DELETE.)

-Implemented OpenAPI Documentation (openapi.yaml).

-Added Swagger UI documentation page (frontend/docs.html) that loads the OpenAPI spec.

Milestone 4 – Authentication, Authorization & Middleware

✔️ Achievements:

-Implemented JWT-based authentication using FlightPHP.

-Added secure user registration and login endpoints:
    /api/auth/register
    /api/auth/login

-Passwords are securely hashed using password_hash() (BCRYPT).

-Implemented authentication middleware:
-Extracts and validates JWT tokens from Authorization: Bearer header.
-Attaches authenticated user data to request lifecycle.
-Implemented role-based authorization:

-Admin users (role_id = 1) can perform full CRUD on all entities.
-Regular users (role_id = 2) have restricted access (read-only where applicable).

-Protected backend routes using middleware:
-Unauthorized requests return 401 Unauthorized.

-Frontend fully connected with backend:
Login and Register forms communicate with backend API.
JWT token stored in localStorage.
Role-based UI rendering (admin-only buttons hidden for regular users).
Admin actions enforced on both frontend and backend.
Added dynamic frontend behavior:
Authenticated users can access protected features.
Admin-only controls (e.g., delete product) shown only for admins.



📁 Project Structure

project-root/
│── frontend/
│   ├── index.html
│   ├── docs.html  (Swagger UI)
│   ├── css/
│   ├── js/
│   └── views/
│
│── backend/
│   ├── index.php
│   ├── config.php
│   ├── openapi.yaml
│   ├── dao/
│   ├── services/
│   └── flight/
│
│── database/
│   └── ice_cream_data.sql
│
│── docs/
│   └── erd.png
│
└── .env



🗄️ Database
Main Entities:

| Entity             | Description                |
| ------------------ | -------------------------- |
| users              | registered users           |
| products           | ice cream products         |
| categories         | product categories         |
| product_categories | many-to-many linking table |
| orders             | customer orders            |
| reviews            | ratings & reviews          |


🔧 Tech Stack
Backend: 
    PHP
    FlightPHP
    PDO

Frontend: 
    HTML5
    CSS
    Bootstrap
    JS
    SPA

Other: 
    MySql
    SWagger UI
    OpenAPI 3.0


📜 Author
    Anid Ali
    