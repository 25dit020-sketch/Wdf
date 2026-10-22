# Practical 1: Project Initiation, Requirement Analysis, Sitemap, Wireframe, and GitHub Setup

**Subject:** Web Development Frameworks (ITUE203)  
**Semester:** 3rd, 2026-27 (ODD)  
**College:** Faculty of Technology and Engineering (FTE), CHARUSAT  
**Project Name:** StudentHub Portal  

---

## 1. Problem Definition & Scope

### Problem Definition
College campuses often handle student information, campus events, and student inquiries across multiple separate systems or paper notices. **StudentHub** is a simple web portal designed for our college campus where students can see upcoming events, register themselves, view their dashboard, and administrators can manage students and event records.

### User Roles
1. **Guest / Visitor:** Can view public pages like Home, About Us, Events, FAQ, and Contact. Can also register for an account.
2. **Student:** Can log into the portal, view personal dashboard, see registered events, and submit feedback.
3. **Admin:** Can log in, view student lists, add/edit/delete students, create campus events, and manage records.

### Minimum 10 Pages Planned
1. `index.html` - Home page with welcome message and latest events.
2. `about.html` - Information about CHARUSAT and FTE departments.
3. `events.html` - List of campus workshops and tech competitions.
4. `register.html` - Registration form for new students.
5. `login.html` - Login form for students and admin.
6. `dashboard.html` - Student personal dashboard.
7. `profile.html` - Student profile details.
8. `contact.html` - Contact details and inquiry form.
9. `admin.html` - Admin control panel.
10. `faq.html` - Frequently asked questions with answers.
11. `feedback.html` - Student feedback form.

---

## 2. Answers to Key Questions (For Viva / Lab Exam)

### Q1: What is URL and parts of URL?
**Answer:**  
A **URL** (Uniform Resource Locator) is the web address used to find any resource on the Internet (like a webpage or an image).

**Example:**
`http://localhost:8080/studenthub/events.php?category=workshop#top`

**Parts of a URL:**
1. **Protocol (Scheme):** `http://` or `https://` — defines the rules for transferring data.
2. **Domain / Host:** `localhost` or `www.charusat.ac.in` — the name of the server where the site is stored.
3. **Port:** `:8080` or `:80` — the communication endpoint on the server.
4. **Path:** `/studenthub/events.php` — the specific folder and file on the server.
5. **Query String:** `?category=workshop` — extra data sent to the page as key-value pairs.
6. **Fragment / Anchor:** `#top` — jumps directly to a specific section on the page.

---

### Q2: How is an HTML file processed in a web browser?
**Answer:**  
When a user opens an HTML file, the browser performs the following steps:
1. **Reading Bytes:** The browser reads the raw bytes from the file and converts them into characters (using UTF-8).
2. **Parsing & Tokens:** It reads tags (like `<html>`, `<p>`, `<a>`) and turns them into tokens.
3. **DOM Tree:** It creates the **DOM** (Document Object Model) tree representing all tags and their parent-child relationships.
4. **CSSOM Tree:** It parses linked CSS files to create the CSS Object Model.
5. **Render Tree:** It combines the DOM and CSSOM to decide which elements are visible on the screen.
6. **Layout & Painting:** It calculates the size and position of every element, then paints the pixels on the screen.

---

### Q3: How will page navigation flow managed among all HTML pages?
**Answer:**  
Navigation will be managed by:
- Including a standard `<nav>` menu on top of every page.
- Using simple relative file links (such as `href="events.html"` and `href="index.html"`).
- Marking the active page with a class like `class="active"` so the user knows where they are.
- Having a breadcrumb trail (e.g. Home > Events) and a consistent footer on all pages.

---

### Q4: How will GitHub commits be maintained after each practical?
**Answer:**  
After completing each practical, a git commit will be made with a clear simple message describing the practical number and task:
```bash
git add .
git commit -m "Practical 1: Project setup and wireframes"
git push origin main
```
This ensures step-by-step progress is tracked throughout the semester.

---

## 3. Files in This Folder
- `sitemap.html`: Simple visual sitemap tree showing how all 10+ pages link together.
- `sitemap.xml`: XML format sitemap.
- `wireframes.html`: Clean, simple visual wireframe diagrams for the main StudentHub pages.
