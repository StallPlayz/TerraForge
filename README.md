# TerraForge

TerraForge is an advanced, web-based combat and loadout simulator for Terraria. It features a fully dynamic, frame-perfect combat engine built to accurately replicate Terraria's internal math, including defense reduction, critical strikes, damage variance, invincibility frames (I-frames), and complex gear stacking.

## Core Features

*   **Dynamic Combat Engine:** A `requestAnimationFrame` game loop that calculates real-time DPS and combat logs based on 60Hz tick rates.
*   **Accurate Terraria Math:** Faithfully replicates the game's core formulas:
    *   Randomized damage variance (85% - 115%).
    *   Classic Mode defense reduction (Effective Defense / 2).
    *   Additive armor penetration and critical strike chances.
    *   Weapon use-time and cooldowns.
*   **Advanced Loadout System:** Mix and match weapons, ammo, armor pieces, and accessories. 
*   **Live Stat Breakdown:** A dynamic UI that aggregates all equipped items and provides a detailed, scrollable breakdown of exactly where your stats are coming from (e.g., set bonuses, specific accessories).
*   **The Data Forge:** A secured backend interface to easily input and manage database schemas for new weapons, enemies, armor, accessories, and ammo.

## Tech Stack

*   **Backend:** Laravel 13, PHP
*   **Frontend:** Vue 3, Inertia.js
*   **Styling:** Tailwind CSS
*   **Database:** PostgreSQL / Supabase

## Local Installation

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/yourusername/terraforge.git](https://github.com/yourusername/terraforge.git)
    cd terraforge
    ```

2.  **Install PHP and Node dependencies:**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup:**
    Copy the example `.env` file and generate your application key:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Update your `.env` file with your database credentials.*

4.  **Database Migration & Seeding:**
    Build the database schema and inject the dummy loadout data (e.g., Minishark, Necro Armor, Eye of Cthulhu):
    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Run the application:**
    Open two terminal windows to run both the backend server and the frontend Vite compiler:
    ```bash
    php artisan serve
    npm run dev
    ```
    OR
    ```bash
    composer run dev
    ```
    For your convenience.

## Development Roadmap

- [x] Base Combat Engine (DPS, Variance, Defense)
- [x] Enemy Invincibility Frames (I-Frames)
- [x] Gear Loadout UI (Armor, Accessories)
- [x] Ranged Weapons & Ammunition Logic
- [x] Dynamic Stat Aggregation & UI Breakdown
- [ ] **In Progress:** Magic Weapons & Mana Regeneration System
- [ ] Reforge System (Legendary, Unreal, Mythical modifiers)
- [ ] Melee Attack Speed Logic
- [ ] Potion Buffs

---
## Disclaimer & Legal

*TerraForge* is an open-source, non-commercial fan project created solely for educational and community utility purposes. 

* **Intellectual Property:** Terraria, including its item names, weapon stats, enemy designs, combat mechanics, and underlying mathematical formulas, is the registered trademark and intellectual property of [Re-Logic](https://re-logic.com/). 
* **No Affiliation:** This simulator is not officially affiliated with, endorsed by, sponsored by, or approved by Re-Logic or 505 Games. 
* **Mechanical Accuracy:** While every effort is made to accurately replicate the game's internal combat engine based on source code analysis and community resources (such as the Official Terraria Wiki), this simulator is an approximation and may not reflect 100% of the edge-case behaviors found in the live game. 

If you enjoy using this tool to plan your builds, please support the developers by purchasing and playing the official release of Terraria!
