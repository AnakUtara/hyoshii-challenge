# Hyoshii Packing Performance Dashboard

A modern dashboard for Hyoshii, a premium strawberry farm in Bandung, designed to track daily packing efficiency per hour with rich data visualizations.  
**Built with Laravel 11 & FilamentPHP in 4 days as an interview technical challenge.**

---

## 📖 Project Overview

This project was developed as a first-round interview test for Hyoshii, a Bandung-based business specializing in premium strawberries. The goal was to build a dashboard to efficiently track and visualize the packing performance of each Person In Charge (PIC) using real-world datasets. The application enables admins to monitor productivity, analyze reject ratios, and optimize workflow based on actionable data.

---

## ✨ Features

- **CRUD for Packing Data:** Easy management of hourly packing records.
- **Bulk Data Entry:** Rapid data input using a repeater form—add multiple hourly entries in a single action.
- **Role-based Authentication:** Secure access powered by Filament.
- **Interactive Charts & Reports:** Visualize key metrics and trends with user-friendly charts.
- **Responsive UI:** Clean, modern interface optimized for both desktop and mobile.

---

## 🗃️ Data Structure

Each data entry includes:

| Field                                | Description                                      |
|-------------------------------------- |--------------------------------------------------|
| Datetime                             | Timestamp of record                              |
| PIC Pengerjaan                       | Responsible PIC                                  |
| Berat Kotor Strawberry yang di pack   | Gross weight of strawberries packed (kg/hour)    |
| Qty Pack A per jam                   | Quantity of Pack A per hour                      |
| Qty Pack B per jam                   | Quantity of Pack B per hour                      |
| Qty Pack C per jam                   | Quantity of Pack C per hour                      |
| Reject (kg) per jam                  | Weight of rejected strawberries (kg/hour)        |

---

## 📊 Visualizations

- **Hourly Accumulation by PIC:** Track each PIC’s total output per hour.
- **Hourly Accumulation by Pack Model:** Monitor output for each pack type (A, B, C).
- **PIC Productivity:** Measure output per 60 minutes and per full day (600 minutes).
- **Reject Ratio:** Visualize reject-to-weight percentages by hour and day.
- **Pack Model Ratios:** See the proportional output of each pack type (A/B/C) by hour and day.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11
- **Admin Panel & Charts:** FilamentPHP
- **Frontend:** FilamentPHP (Blade & Livewire)
- **Database:** SQLite

---

## 🖼️ Screenshots

Below are sample screenshots of the dashboard:

![Sign In Screen](https://github.com/user-attachments/assets/a5c8e509-346a-4d11-ac9f-6436617e264d)
*Sign in screen – secure authentication powered by Filament.*

![Chart Data Visualizations](https://github.com/user-attachments/assets/5c7afee0-e623-4e47-8373-32c7df0a8426)
*Charts dashboard – track productivity, reject ratios, and output trends at a glance.*

![Data Table View](https://github.com/user-attachments/assets/b68a8b21-a1a0-416d-babf-5cee40c42b3f)
*Comprehensive data table – view, filter, and manage all packing performance records.*

![Create Repeater Form](https://github.com/user-attachments/assets/221bf108-e46e-4fd7-beab-f353815bf603)
*Bulk data entry using a repeater form – add multiple hourly records efficiently.*

![Create New PIC with Relation](https://github.com/user-attachments/assets/dbdb2827-bdc7-4b82-b832-bf41823bfd0c)
*Create new PIC on the fly – leverage FilamentPHP’s relation manager for seamless data entry.*

![Edit Form](https://github.com/user-attachments/assets/c226d39f-48b3-477c-b9f4-3755a861b95f)
*Edit performance data – intuitive, user-friendly forms for updating records.*

---

## 🚀 Challenge & Solution

**Challenge:**  
Build a robust, visually appealing dashboard for tracking strawberry packing performance, with at least 30 sample entries, within 4 days and using any tech stack.

**Solution:**  
Opted for Laravel 11 with FilamentPHP to rapidly scaffold CRUD resources, leverage built-in authentication, and integrate advanced charting. Designed a bulk entry form to streamline data input, enabling efficient recording of hourly productivity. Focused on clear, actionable visualizations to help admins optimize operations.

---

## 📌 Outcome

Successfully completed all requested features and received an offer letter from Hyoshii. Politely declined the offer for personal reasons, but proud of the work and the technical solution delivered under a tight deadline.

---

## 📦 Getting Started

1. Clone the repository  
   `git clone https://github.com/AnakUtara/hyoshii-challenge.git`
2. Install dependencies  
   `composer install && npm install && npm run build`
3. Configure your `.env` file
4. Run migrations and seeders  
   `php artisan migrate --seed`
5. Start the development server  
   `php artisan serve`

---

## 📚 Credits

- **Hyoshii** for the challenging case study.
- **FilamentPHP** for the powerful admin toolkit.
- **Laravel** for its speed and flexibility.
