# Coding School Management System (ERP/LMS)
### -- Project Overview --
A custom-built **Enterprise Resource Planning (ERP)** and **Learning Management System (LMS)** designed for a technical educational center. The platform automates student enrollment, course management, and internal administrative workflows.

This project was developed in a collaborative environment, where I focused on **Frontend Engineering**, UI/UX architecture, and integration with the PHP-based service layer.

---
### -- Key Features --
* **Role-Based Access Control (RBAC):** Distinct interfaces and permissions for Students and Administrators.
* **Administrative Dashboard:** Full-cycle management of clients, services, and internal inventory.
* **User Lifecycle Management:** Secure registration, authentication, and personalized student profiles.
* **Automated Workflow:** Real-time tracking of course applications and payment statuses.
---
### -- Technical Architecture --
The system follows a modular structure to ensure separation of concerns between the presentation layer and business logic.
 
```text
├── admin/                  # Centralized Administration Hub
│   ├── controllers/        # Core business logic handlers
│   │   ├── clients/        # Client base management module
│   │   ├── inventory/      # Educational materials & assets tracking
│   │   ├── mechanics/      # Staff performance & shift management
│   │   ├── services/       # Course & service catalog management
│   │   └── vehicles/       # Specialized equipment/assets registry
│   └── index.php           # Admin panel entry point
├── auth/                   # IAM: Authentication & Authorization logic
├── function/               # System Core: DB connection & global helpers
├── inc/                    # UI Components: Reusable Header, Footer, Nav
├── profile/                # Student/Employee Personal Dashboard
├── register/               # New user onboarding & registration flow
└── assets/                 # Frontend assets: Styles (CSS), Fonts, JS, Media
```
---

### -- Tech Stack --

• **Frontend:** HTML5, CSS3 (Custom Grid/Flexbox layouts), JavaScript. Focus on modularity and reusable UI components.

• **Backend:** PHP (Legacy-based architectural support).

• **Database:** MySQL (Relational data modeling for users, orders, and inventory).

• **Version Control:** Git (Collaborative workflow with PRs and role-based commits).

---
### -- Professional Context --

This repository serves as a showcase of my ability to:

• **Collaborate in a Team:** Working effectively with backend engineers to integrate complex business logic.

• **Design Scalable Interfaces:** Building a cohesive UI that handles large datasets in the admin dashboard.

• **Bridge the Gap:** Understanding full-stack interactions, which ultimately led to my transition into high-performance backend development with Go.
