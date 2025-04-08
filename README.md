# 🏐 MYVC Database Application System

This repository contains the full implementation of a **Database Application System** for the **Montréal Youth Volleyball Club (MYVC)**, developed as part of the **COMP 353 - Databases** course at Concordia University (Winter 2025).

---

## 📌 Project Overview

The application is designed to help MYVC manage its club operations by storing, organizing, and processing information about:

- Club members and family relationships
- Club locations (Head and Branches)
- Personnel (managers, coaches, treasurers, etc.)
- Team formations and training/game sessions
- Membership payments and financial tracking
- Automatic email notifications and activity logs

It features a **relational database schema**, advanced **SQL queries**, **triggers**, and a **GUI** to facilitate user interaction with the system.

---

## ⚙️ Features

- 🧍 Member registration, deactivation, and age validation  
- 🏠 Location and personnel management with roles & mandates  
- 🧾 Membership fee tracking with payment methods and donation handling  
- 🏐 Team formations with role assignment, session conflict prevention  
- ✉️ Automatic email generation for training/game schedules and deactivations  
- 📜 Logging system for all outgoing emails  
- 📊 Complex SQL queries for reporting and system insights  
- 🧠 ER Diagram, 3NF/BCNF normalization, and integrity constraints  

---

## 🧰 Tech Stack

- **Database**: MySQL
- **Backend**: SQL, Triggers, Views  
- **Frontend/GUI**: HTML/CSS, HTMX, PHP
- **Tools**: VS Code

---

## 📈 Schema & Design

The database schema is based on a detailed E/R model and normalized to **3NF** (with analysis for **BCNF**). Key integrity constraints, functional dependencies, and relationships are implemented through:

- Primary/foreign keys  
- Not null/unique constraints  
- SQL triggers for business logic  
- Relational refinements for redundancy elimination

---

## 📄 Key SQL Features Demonstrated

- DDL & DML commands (create, insert, update, delete)  
- Triggers for age-based deactivation and email generation  
- Advanced queries with grouping, filtering, sorting, and joins  
- Constraints enforcement to prevent conflicting team assignments  
- Email logs with subject/body previews and timestamps

---
