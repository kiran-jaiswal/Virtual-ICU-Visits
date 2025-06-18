
# 🏥 Virtual Visit to ICU

The **Virtual Visit to ICU** is a web application developed using HTML, CSS, JavaScript, and PHP. It allows family and friends to visit patients in the ICU virtually through a secure and user-friendly platform. This system is especially useful during health crises like the COVID-19 pandemic or for remote family members.

---

## 📌 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [System Architecture](#system-architecture)
- [Screenshots](#screenshots)
- [Installation](#installation)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Future Enhancements](#future-enhancements)
- [Contributors](#contributors)
- [License](#license)

---

## ✨ Features

🔒 **User Authentication**  
- Signup/Login system for visitors and admin
- Secure sessions with PHP session management

📅 **Appointment Scheduling**  
- Visitors can request virtual visit time slots
- Admin can approve or reject visit requests

📞 **Video Call Integration**  
- Embedded video call (using Jitsi Meet / WebRTC API)

🧑‍⚕️ **Admin Dashboard**  
- Manage patients
- View and approve appointment requests
- Track call logs

📝 **Patient Details View**  
- View patient information (name, condition, ICU room, etc.)

📬 **Email Notifications**  
- Confirmation emails sent to visitors when appointments are approved

🧾 **Visitor Pass Generation**  
- Auto-generated virtual visitor pass after approval

💬 **Feedback and Chat (Optional)**  
- Visitors can submit post-call feedback or emotional messages

---

## 🛠 Tech Stack

### 🌐 Frontend:
- **HTML5** – Structure
- **CSS3** – Styling
- **JavaScript** – Interaction & Form validation
- **Bootstrap (optional)** – For responsive UI design

### 🧠 Backend:
- **PHP (Core PHP)** – Server-side scripting
- **MySQL** – Database for storing user, patient, and appointment info

### 📦 Others:
- **PHPMailer / SMTP** – For email notifications
- **Jitsi Meet / WebRTC** – For real-time video conferencing

---

## 🧩 System Architecture

```plaintext
[Visitor/Admin] ⇄ [Browser] ⇄ [HTML/CSS/JS] ⇄ [PHP Backend] ⇄ [MySQL DB]
                                           ⇓
                                  [Video Call API]
