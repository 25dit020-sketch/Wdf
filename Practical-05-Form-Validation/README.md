# Practical 5: Client-Side Form Validation using JavaScript

## Aim
To implement interactive, client-side form validation for the **StudentHub** registration form using JavaScript (Regular Expressions, real-time input feedback, password strength calculation, and custom Canvas CAPTCHA).

---

## Features Implemented
1. **Full Name Validation:** Alphabetic characters and spaces only, minimum length 3 characters.
2. **Email Validation:** Regex checking for standard email structure (`user@domain.ext`).
3. **Mobile Number Validation:** Exactly 10 digits starting with 6, 7, 8, or 9 (Indian mobile format).
4. **Department Selection:** Ensures the student selects an option from the dropdown.
5. **Real-time Password Strength Meter:** Evaluates password complexity (length, uppercase, digits, symbols) and dynamically renders a colored bar (Weak / Medium / Strong).
6. **Password Confirmation:** Matches the re-typed password against the initial password in real time.
7. **HTML5 Canvas CAPTCHA:** Generates a random alphanumeric code with noise lines and provides a refresh button.
8. **Real-Time & On-Submit Feedback:** Inline error messages and color-coded input borders (`.valid` / `.invalid`). Prevents form submission if any field fails.

---

## Test Case Matrix

| Test ID | Field | Test Input | Expected Behavior | Result |
| :--- | :--- | :--- | :--- | :--- |
| **TC01** | Full Name | `""` (Empty) | Displays error: "Full Name is required." | Pass |
| **TC02** | Full Name | `Ra123` | Displays error: "Name must contain only letters." | Pass |
| **TC03** | Full Name | `Rahul Patel` | Input border turns green, error clears. | Pass |
| **TC04** | Email | `rahulpatel` | Displays error: "Please enter a valid email address." | Pass |
| **TC05** | Email | `rahul@charusat.edu.in` | Border turns green, valid format accepted. | Pass |
| **TC06** | Mobile | `12345` | Displays error: "Enter a valid 10-digit Indian mobile number." | Pass |
| **TC07** | Mobile | `9876543210` | Border turns green, valid mobile number. | Pass |
| **TC08** | Department | `""` (Not selected) | Displays error: "Please select your department." | Pass |
| **TC09** | Password | `pass` | Strength bar turns red (Weak), shows error (min 8 chars). | Pass |
| **TC10** | Password | `Admin@123` | Strength bar turns green (Strong), input accepted. | Pass |
| **TC11** | Confirm Password | `Admin@999` | Displays error: "Passwords do not match." | Pass |
| **TC12** | CAPTCHA | `WRONG` | Displays error: "CAPTCHA code is incorrect." | Pass |
| **TC13** | Submit Button | All fields valid | Submits, shows green success banner, resets form. | Pass |

---

## Viva Questions & Answers

### Q1: What is the difference between client-side and server-side validation? Why do we need both?
- **Client-Side Validation (JavaScript):**
  - Runs inside the user's browser before data is transmitted over the network.
  - **Pros:** Instant feedback, reduces unnecessary HTTP requests, better user experience (UX).
  - **Limitation:** Can be bypassed easily by disabling JavaScript or using tools like Postman or curl.
- **Server-Side Validation (PHP/Node.js):**
  - Runs on the server after receiving the HTTP request.
  - **Pros:** Ensures security, data integrity, prevents SQL Injection and XSS attacks. Cannot be bypassed by disabling browser JS.
- **Why Both:** Client-side is for **User Experience (speed)**, server-side is for **Security and Data Integrity**.

### Q2: Explain the Regular Expression used for Mobile Number validation.
- Expression: `/^[6-9][0-9]{9}$/`
  - `^` : Asserts the start of the string.
  - `[6-9]` : Ensures the first digit is 6, 7, 8, or 9 (standard for Indian mobile operators).
  - `[0-9]{9}` : Exactly 9 subsequent numeric digits (0 through 9).
  - `$` : Asserts the end of the string.
  - Total length is guaranteed to be exactly 10 digits.

### Q3: What is the purpose of `event.preventDefault()` during form submission?
- By default, submitting an HTML form (`<button type="submit">`) refreshes the page and attempts to send an HTTP GET/POST request to the URL in the `action` attribute.
- `event.preventDefault()` stops this default browser action so that JavaScript can first execute validation checks. If errors exist, submission is cancelled without reloading the page.

### Q4: Compare `input`, `blur`, and `change` events.
- **`input`**: Fires immediately whenever the value of an `<input>` changes (every keystroke). Ideal for password strength meters.
- **`blur`**: Fires when an element loses focus (the user clicks outside the field). Ideal for showing field-level error messages without disturbing typing.
- **`change`**: Fires when an element loses focus after its value was modified (or immediately on dropdown `<select>` selection).

### Q5: Is client-side CAPTCHA completely secure?
- No. In this client-side implementation, the CAPTCHA code is generated and stored in a JavaScript variable in the browser memory (`currentCaptcha`). An automated bot could inspect the DOM or JavaScript variables to read it.
- **Production Solution:** CAPTCHA must be generated on the server, saved in a secure server-side session (`$_SESSION['captcha']`), rendered as an image, and verified on the server.
