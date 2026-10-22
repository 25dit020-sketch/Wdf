# Practical 2: Semantic HTML5 Pages with Accessibility-Ready Structure

**Subject:** Web Development Frameworks (ITUE203)  
**Semester:** 3rd, 2026-27 (ODD)  
**College:** Faculty of Technology and Engineering (FTE), CHARUSAT  

---

## 1. Problem Definition
Develop static HTML5 skeletons for at least 10 StudentHub pages such as Home, About, Register, Login, Dashboard, Events, Profile, Contact, Admin, FAQ, and Feedback using semantic tags and accessibility-friendly structure.

---

## 2. Answers to Key Questions (For Viva)

### Q1: Are semantic tags such as `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, and `<footer>` used properly?
**Answer:**  
Yes. Instead of using plain `<div>` tags everywhere, semantic elements are used according to their purpose:
- `<header>`: Contains university title and sub-heading.
- `<nav>`: Contains all navigation links.
- `<main>`: Contains the primary content of each page.
- `<section>`: Groups related content together (e.g., Welcome section, Featured events section).
- `<article>`: Used for self-contained items like individual event cards or FAQ items.
- `<aside>`: Used for campus notices on the side.
- `<footer>`: Contains copyright information.

### Q2: Are form labels, headings, image alt text, and navigation links accessible?
**Answer:**  
Yes. Accessibility features included:
- Every `<input>` has a matching `<label for="...">` with the input's `id`.
- Headings follow proper hierarchical order (`<h1>` followed by `<h2>` followed by `<h3>`).
- Navigation links use clear text like "Events" and "Register" instead of vague words like "click here".
- Breadcrumb navigation is provided on top of content to help users know their current page location.

### Q3: Is the page structure consistent across all pages?
**Answer:**  
Yes. All 11 pages follow the exact same template structure:
1. Top `<header>` with university title.
2. `<nav>` with links to all other pages.
3. Breadcrumb trail.
4. `<main>` containing page sections.
5. Bottom `<footer>`.
This makes the website familiar and easy to browse.

---

## 3. List of Pages Created
1. `index.html` - Home Page
2. `about.html` - About FTE & Departments
3. `events.html` - Campus Events Catalog
4. `register.html` - Student Registration Form
5. `login.html` - User Login Page
6. `dashboard.html` - Student Dashboard & Enrolled Events
7. `profile.html` - Student Profile Details
8. `contact.html` - Contact Information & Inquiry Form
9. `admin.html` - Admin Summary Panel
10. `faq.html` - Frequently Asked Questions
11. `feedback.html` - Student Feedback Form
