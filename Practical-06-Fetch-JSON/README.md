# Practical 6: Asynchronous Data Loading with JavaScript Fetch API & JSON

## Aim
To load, parse, and render dynamic campus data (Events, Student Directory, and FAQs) asynchronously from JSON files using the JavaScript `fetch()` API, incorporating live search, dynamic dropdown filtering, loading indicators, and client-side pagination.

---

## Features Implemented
1. **Asynchronous Fetching:** Loads structured JSON files (`events.json`, `students.json`, `faqs.json`) on demand without reloading the web page.
2. **Interactive Tab Switching:** Seamlessly toggles between Events, Students, and FAQs views.
3. **Real-time Live Search:** Filters records dynamically as the user types into the search box.
4. **Dynamic Dropdown Filtering:** Automatically extracts unique categories or departments from the dataset to build filter options.
5. **Client-Side Pagination:** Splits large datasets into manageable pages (4 items per page) with Previous/Next buttons and page count indicators.
6. **Graceful Error Handling:** Displays user-friendly error messages if data fails to load (e.g. invalid path or network error).

---

## How to Run
> **Important Note:** Modern web browsers block `fetch()` requests on local files (`file:///...`) due to the browser's **CORS (Cross-Origin Resource Sharing)** security policy.
>
> Run this practical using any local web server:
> - **Method 1 (VS Code Live Server):** Right-click `index.html` -> "Open with Live Server".
> - **Method 2 (Python HTTP Server):** Run `python3 -m http.server 8000` inside this directory and open `http://localhost:8000`.
> - **Method 3 (XAMPP / Apache):** Place the folder inside `htdocs` and visit `http://localhost/wdf/Practical-06-Fetch-JSON/`.

---

## Viva Questions & Answers

### Q1: What is the Fetch API, and how does it differ from traditional `XMLHttpRequest` (XHR)?
- **Fetch API:** A modern, Promise-based native JavaScript interface for making HTTP requests. It is cleaner, supports async/await, and avoids "callback hell".
- **Differences:**
  | Feature | `XMLHttpRequest` (XHR) | `Fetch API` |
  | :--- | :--- | :--- |
  | Syntax | Verbose, event-based (`onreadystatechange`) | Clean, Promise-based (`.then()` / `await`) |
  | JSON parsing | Manual using `JSON.parse(xhr.responseText)` | Built-in via `response.json()` |
  | Streaming | Complex to stream responses | Native Streams API support |
  | Code Readability | Cluttered with callbacks | Modular and linear |

### Q2: What is a Promise in JavaScript? What are its three states?
- A **Promise** represents the eventual completion (or failure) of an asynchronous operation and its resulting value.
- **Three States:**
  1. `pending`: Initial state; neither fulfilled nor rejected.
  2. `fulfilled` (resolved): The asynchronous operation completed successfully. Handled by `.then()`.
  3. `rejected`: The operation failed (e.g. 404 error, network down). Handled by `.catch()`.

### Q3: Why is `response.json()` itself an asynchronous method returning a Promise?
- The HTTP response body arrives over the network in sequential data chunks (streams). `response.json()` reads the entire body stream to the end and parses it as JSON. Because reading large streams takes time, it returns a Promise to avoid blocking the main JavaScript thread.

### Q4: Why does opening `index.html` via `file:///` cause a CORS error when using `fetch()`?
- The browser security model treats the `file://` protocol as having an opaque origin (`null`). For security reasons (to prevent local malicious scripts from reading arbitrary files from the user's hard drive), browsers restrict CORS for `null` origins. Serving the files over `http://localhost` allows the browser to verify the origin and execute `fetch()`.

### Q5: How do array methods `filter()` and `slice()` enable client-side search and pagination?
- `filter(callback)`: Iterates through the full dataset and returns a new array containing only elements that match the search keyword or selected category.
- `slice(startIndex, endIndex)`: Extracts a shallow slice of the filtered array representing the current page's slice (e.g., from `(page - 1) * itemsPerPage` to `startIndex + itemsPerPage`), keeping rendering performant.
