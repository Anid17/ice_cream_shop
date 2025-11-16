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