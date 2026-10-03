# Student Accommodation Web Application

> **Designed & Developed by**: **Vishal Shirsath** (Senior Full-Stack Web Developer)

A responsive, full-stack student accommodation platform that enables students and young professionals to explore, view, filter, and shortlist Paying Guest (PG) accommodations, hostels, and co-living spaces across top educational hubs in India including **Nashik**, **Mumbai**, **Pune**, **Bangalore**, and **Delhi**.

---

## Table of Contents
1. [Project Overview & Developer Credit](#project-overview--developer-credit)
2. [Tech Stack](#tech-stack)
3. [Functional Requirements Coverage](#functional-requirements-coverage)
4. [Database Architecture & Schema](#database-architecture--schema)
5. [Key Features & AJAX/React Integration](#key-features--ajaxreact-integration)
6. [Setup and Local Run Instructions](#setup-and-local-run-instructions)
7. [API Documentation](#api-documentation)
8. [Deliverables Summary](#deliverables-summary)

---

## Project Overview & Developer Credit

- **Creator & Lead Developer**: **Vishal Shirsath**
- **Purpose**: Solving the challenge of finding verified, affordable student accommodations near top colleges (KK Wagh, Sandip University, MET, BYK, IIT Bombay, DU North Campus, Symbiosis, etc.).

---

## Tech Stack

- **Frontend**: HTML5, CSS3, Bootstrap 5, FontAwesome 6, JavaScript (ES6+), AJAX Fetch API
- **React Integration**: React 18 & ReactDOM (CDN / Babel integration) for component-based shortlist management
- **Backend**: PHP 8 (PDO Architecture, prepared statements)
- **Database**: MySQL (compatible with SQLite PDO zero-setup fallback)

---

## Functional Requirements Coverage

| Requirement | Implementation Status | Key File Reference |
| :--- | :--- | :--- |
| **1. Property Listing** | Completed | [`index.php`](file:///Users/vishal/VSCODE/Student_Accommodation_web/index.php), [`api/get_properties.php`](file:///Users/vishal/VSCODE/Student_Accommodation_web/api/get_properties.php) |
| **2. Property Details** | Completed | [`property-detail.php`](file:///Users/vishal/VSCODE/Student_Accommodation_web/property-detail.php), [`api/get_property.php`](file:///Users/vishal/VSCODE/Student_Accommodation_web/api/get_property.php) |
| **3. Database Design** | Completed | [`database/schema.sql`](file:///Users/vishal/VSCODE/Student_Accommodation_web/database/schema.sql), [`config/db.php`](file:///Users/vishal/VSCODE/Student_Accommodation_web/config/db.php) |
| **4. Backend Integration (PHP)** | Completed | `api/` endpoints, session handling, authentication |
| **5. JavaScript & AJAX** | Completed | [`assets/js/app.js`](file:///Users/vishal/VSCODE/Student_Accommodation_web/assets/js/app.js) (No page reload filters & interest toggle) |
| **6. React Integration** | Completed | [`assets/js/react_app.js`](file:///Users/vishal/VSCODE/Student_Accommodation_web/assets/js/react_app.js), [`shortlist.php`](file:///Users/vishal/VSCODE/Student_Accommodation_web/shortlist.php) |

---

## Database Architecture & Schema

The database is fully normalized and follows the exact specification structure:

1. **`users`**: `(id, name, email, password, phone, created_at)`
2. **`properties`**: `(id, name, city, address, price, gender, rating, description, main_image, created_at)`
3. **`amenities`**: `(id, name, icon)`
4. **`property_amenities`**: `(property_id, amenity_id)` - Junction table
5. **`interested_users`**: `(user_id, property_id, created_at)` - Shortlist junction table
6. **`property_images`**: `(id, property_id, image_url)` - Image gallery support

---

## Setup and Local Run Instructions

### Step 1: Clone / Open Project Directory
```bash
cd /Users/vishal/VSCODE/Student_Accommodation_web
```

### Step 2: Start PHP Server
Run the built-in PHP development server:
```bash
php -S localhost:8000
```

### Step 3: Access in Browser
Open your browser and navigate to:
`http://localhost:8000`

---

## Demo Test Credentials

- **Email**: `rahul@example.com`
- **Password**: `password123`

---

## Vercel Deployment Instructions

### Method 1: Deploy via Vercel Dashboard (Recommended)
1. Push your code to your GitHub repository ([`VishalShir60/Student_Accommodation_Web`](https://github.com/VishalShir60/Student_Accommodation_Web)).
2. Log into your [Vercel Dashboard](https://vercel.com/dashboard).
3. Click **"+ Create New"** -> **"Project"**.
4. Select and import your GitHub repository: `Student_Accommodation_Web`.
5. Keep default build settings (Vercel will automatically read `vercel.json`).
6. Click **Deploy**.

### Method 2: Deploy via Vercel CLI
```bash
npm install -g vercel
vercel
```
Follow the prompts to deploy directly from your local terminal.

