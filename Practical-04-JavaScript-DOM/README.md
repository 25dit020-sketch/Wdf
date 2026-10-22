# Practical 4: JavaScript DOM Manipulation, Event Handling, and UI Interactivity

**Subject:** Web Development Frameworks (ITUE203)  
**Semester:** 3rd, 2026-27 (ODD)  
**College:** Faculty of Technology and Engineering (FTE), CHARUSAT  

---

## 1. Problem Definition
Add dynamic UI components such as:
1. Collapsible FAQ (accordion)
2. Modal popup
3. Image/content slider
4. Notification banner (dismissible)
5. Hamburger menu (mobile responsive)
6. Light/Dark theme switcher using JavaScript and `localStorage`

---

## 2. Answers to Key Questions (For Viva)

### Q1: How are DOM elements selected and modified?
**Answer:**  
- **Selection:** Elements are selected in JavaScript using standard methods like `document.getElementById('id')` for single elements and `document.querySelectorAll('.class')` for groups of elements.
- **Modification:** Elements are modified by:
  - Changing classes: `element.classList.toggle('dark-mode')`, `element.classList.add('active')`.
  - Changing text: `element.textContent = '🌙 Dark Mode'`.
  - Changing display style: `element.style.display = 'none'` or `'flex'`.

### Q2: Are event listeners attached correctly?
**Answer:**  
Yes. Event listeners are cleanly attached using `addEventListener('click', function() { ... })` inside a `DOMContentLoaded` event block, ensuring the HTML tags are fully loaded in the browser before scripts run.

### Q3: Is `localStorage` used for remembering theme choice?
**Answer:**  
Yes. When the user clicks the theme button:
```javascript
localStorage.setItem('theme', 'dark');
```
When the page is opened or refreshed, it reads:
```javascript
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark') {
  document.body.classList.add('dark-mode');
}
```
This guarantees that the user's theme choice persists across page reloads.

### Q4: Does interactivity improve usability without breaking accessibility?
**Answer:**  
Yes. Interactive elements like the notification banner, modal close button, and accordion use standard HTML `<button>` elements that can be focused and triggered using keyboard keys (Enter/Space), and the modal popup closes when clicking outside the dialog.

---

## 3. How to Run
Open `index.html` in any browser:
- Click **Dark Mode** to toggle themes and reload the page to verify it stays saved.
- Click **View Hackathon Rules** to see the popup modal.
- Click **Previous / Next** to browse highlights.
- Click FAQ questions to expand/collapse answers.
