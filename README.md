# 🎬 Nox Movie (CineVerse)

A full-stack movie discovery platform built with **CodeIgniter 4** (backend) and **React** (frontend). Users can browse, search, and explore films and TV shows powered by real-time [TMDB](https://www.themoviedb.org/) data — with user authentication, trending content, detailed movie pages, and personalized recommendations.

---

## ✨ Features

- **Browse & Search** — Explore movies by title, genre, director, or cast
- **TMDB Integration** — Live data from The Movie Database (popular, top-rated, trending, now playing, upcoming)
- **User Authentication** — Register, login, and logout with session-based auth
- **Movie Detail Pages** — Full info including cast, trailers (YouTube), similar movies, and recommendations
- **Trending & Featured** — Curated sections for trending and featured content
- **Admin Panel** — Full CRUD for movies and user management
- **TMDB Import** — Bulk-import movies from TMDB directly into the local database
- **REST API** — Clean JSON API for both local and TMDB-proxied data
- **Favorites & Collections** — Personalized lists for logged-in users
- **Responsive UI** — React-based interface built for all screen sizes

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP · CodeIgniter 4 |
| Frontend | React · JavaScript · CSS |
| Database | MySQL |
| External API | TMDB (The Movie Database) |
| API Testing | Postman |

---

## 📁 Project Structure

```
nox-movie/
├── backend/                        # CodeIgniter 4 application
│   ├── app/
│   │   ├── Controllers/            # Auth, Admin, API, Pages controllers
│   │   ├── Models/                 # Movie and User models
│   │   └── Views/                  # Blade-style PHP views
│   └── .env                        # Environment config (TMDB token, DB)
├── CineVerse_API.postman_collection.json   # Full Postman API collection
├── fix_backdrops.php               # Utility script to fix backdrop URLs
├── movie_project.sql               # Database schema
└── moviestream_db.sql              # Database with seed data
```

---

## ⚙️ Installation

### Prerequisites

- PHP 8.1+
- Composer
- MySQL 5.7+ or MariaDB
- Node.js & npm (for the React frontend)
- A free [TMDB API key](https://www.themoviedb.org/settings/api)

### 1. Clone the repository

```bash
git clone https://github.com/Rayhan-Arrazy/nox-movie.git
cd nox-movie
```

### 2. Set up the backend

```bash
cd backend
composer install
cp env .env
```

Edit `.env` and configure your database and TMDB token:

```env
database.default.hostname = localhost
database.default.database = movie_project
database.default.username = root
database.default.password = your_password

TMDB_API_TOKEN = your_tmdb_read_access_token_here
```

### 3. Import the database

```bash
mysql -u root -p movie_project < ../movie_project.sql
# Optional: import seed data
mysql -u root -p movie_project < ../moviestream_db.sql
```

### 4. Run the development server

```bash
# From the backend directory
php spark serve
```

The app will be available at `http://localhost:8080`.

---

## 🔑 Default Credentials

| Role | Email | Password |
|---|---|---|
| Admin | admin@cineverse.com | admin123 |
| Client | john@example.com | client123 |

> **⚠️ Change these credentials before deploying to production.**

---

## 🌐 API Reference

A full Postman collection is included at `CineVerse_API.postman_collection.json`. Import it into Postman and set the following variables:

| Variable | Default | Description |
|---|---|---|
| `base_url` | `http://localhost:8080` | Your server URL |
| `tmdb_token` | — | Your TMDB Read Access Token |

### Local Movies API

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/movies` | List all movies (supports `?search=`, `?genre=`, `?limit=`) |
| `GET` | `/api/movies/featured` | Featured movies |
| `GET` | `/api/movies/trending` | Trending movies |
| `GET` | `/api/movies/genres` | All genres |
| `GET` | `/api/movies/:slug` | Single movie by slug |
| `POST` | `/api/movies` | Create movie *(admin)* |
| `PUT` | `/api/movies/:id` | Update movie *(admin)* |
| `DELETE` | `/api/movies/:id` | Delete movie *(admin)* |

### TMDB Proxy API

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/tmdb/popular` | Popular movies |
| `GET` | `/api/tmdb/top-rated` | Top rated movies |
| `GET` | `/api/tmdb/now-playing` | Now playing in theaters |
| `GET` | `/api/tmdb/upcoming` | Upcoming releases |
| `GET` | `/api/tmdb/trending` | Trending (`?window=day\|week`) |
| `GET` | `/api/tmdb/search?q=` | Search TMDB movies |
| `GET` | `/api/tmdb/discover` | Discover with filters |
| `GET` | `/api/tmdb/genres` | TMDB genre list |
| `GET` | `/api/tmdb/movie/:id` | Full movie detail with credits & trailers |
| `GET` | `/api/tmdb/movie/:id/similar` | Similar movies |
| `GET` | `/api/tmdb/movie/:id/recommendations` | Recommendations |
| `GET` | `/api/tmdb/movie/:id/credits` | Cast & crew |
| `GET` | `/api/tmdb/movie/:id/videos` | Trailers & clips |
| `GET` | `/api/tmdb/person/:id` | Person detail |
| `POST` | `/api/tmdb/import` | Bulk import from TMDB *(admin)* |

### Auth

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/login` | Login |
| `POST` | `/register` | Register new account |
| `GET` | `/logout` | Logout |

---

## 🛡️ Admin Panel

Access the admin dashboard at `/admin` after logging in with an admin account.

- **Movies** — Add, edit, delete, and import movies
- **Users** — View and manage all registered users
- **TMDB Import** — Bulk-import from popular, top-rated, trending, or now-playing sources

---

## 📄 Pages

| Route | Description |
|---|---|
| `/` | Home page |
| `/browse` | Browse all movies |
| `/movie/:slug` | Movie detail page |
| `/watch/:slug` | Watch page *(login required)* |
| `/favorites` | Your favorites *(login required)* |
| `/collections` | Your collections *(login required)* |
| `/settings` | Account settings *(login required)* |
| `/about` | About page |
| `/contact` | Contact page |

---
