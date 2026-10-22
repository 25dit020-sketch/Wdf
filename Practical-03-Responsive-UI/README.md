# Practical 3: Responsive UI Design using CSS Grid and Flexbox

**Subject:** Web Development Frameworks (ITUE203)  
**Semester:** 3rd, 2026-27 (ODD)  
**College:** Faculty of Technology and Engineering (FTE), CHARUSAT  

---

## 1. Problem Definition
Design responsive layouts for Home, About, Registration, Dashboard, and Events pages using CSS Grid and Flexbox. Ensure mobile-first responsiveness so layouts display cleanly across mobile smartphones (<768px) and desktop monitors.

---

## 2. Answers to Key Questions (For Viva)

### Q1: How does the layout adapt for mobile, tablet, and desktop screens?
**Answer:**  
- **On Desktop / Laptops:** 
  - The navigation items sit side-by-side in a horizontal row using `display: flex`.
  - Statistics and event cards display in a 3-column layout using `grid-template-columns: repeat(3, 1fr)`.
  - Form inputs appear in 2-column rows side-by-side.
- **On Mobile (< 768px):**
  - Using media queries (`@media (max-width: 768px)`), the navigation menu stacks vertically.
  - The 3-column grid collapses to 1 single column (`grid-template-columns: 1fr`) so boxes fit naturally without horizontal scrolling.
  - Form rows change from row to column (`flex-direction: column`).

### Q2: Which layout technique is used and why?
**Answer:**  
- **CSS Flexbox (`display: flex`)** is used for 1-dimensional layouts (e.g. navigation bar, button groups, and form input pairs) because flexbox easily manages spacing, alignment, and wrapping along a single line.
- **CSS Grid (`display: grid`)** is used for 2-dimensional layouts (e.g. event cards and stat counter boxes) because grid allows dividing page space into exact columns and rows with clean gutters (`gap`).

### Q3: Are typography, spacing, colors, and contrast readable?
**Answer:**  
Yes. 
- Dark text `#333` on light grey background `#f4f7f6` ensures high readability and comfort for eyes.
- Standard legible fonts (Arial/sans-serif) with clear line-height of 1.5.
- Card padding of `15px` to `20px` gives clean breathing room between elements.

### Q4: Is the framework used consistently without unnecessary inline CSS?
**Answer:**  
Yes. All responsive rules, grids, and flexbox containers are maintained in `css/style.css` using reusable classes like `.btn`, `.card-box`, `.stats-grid`, and `.events-grid`.

---

## 3. Pages Included
- `index.html` - Responsive Home page with hero, stats grid, and event cards.
- `about.html` - Responsive About page with department cards.
- `events.html` - Responsive 3-column event catalog grid.
- `register.html` - Responsive registration form with flexbox rows.
- `dashboard.html` - Responsive student dashboard with stats and table.
