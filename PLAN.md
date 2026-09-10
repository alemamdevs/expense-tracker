Build a modern, clean, and user-friendly Expense Tracker mobile application using NativePHP Mobile v4 ("SuperNative") and only free/open-source components and packages.

The application must be fully functional offline and should not depend on any external API or cloud service for its core functionality.

==================================================
TECH STACK
==================================================

Use:

- PHP
- Laravel
- NativePHP Mobile v4 (native SuperNative UI — SwiftUI on iOS, Jetpack Compose on Android)
- `nativephp/native-ui` plugin (free/OSS) for theme tokens and typed icon enums
- SQLite for local offline storage
- Tailwind utility classes (the EDGE class parser — no browser CSS)
- Only free and open-source packages/components

Do NOT use:

- Paid services or paid UI component libraries
- Livewire, Alpine.js, or Inertia (not part of a native v4 app)
- A web view as the foundation of any screen
- A JavaScript chart library (charts are drawn natively — see Statistics)

The application should be designed with a mobile-first approach.

==================================================
CORE REQUIREMENTS
==================================================

Create a simple but polished Expense Tracker application where users can:

1. Add income
2. Add expenses
3. Create and manage categories
4. View transaction history
5. Edit transactions
6. Delete transactions
7. View financial summaries
8. Filter transactions
9. Change the application's theme/accent color
10. Reset the theme color to the default
11. Change the display currency (default BDT Taka)
12. Use the application completely offline

All user data must be stored locally using SQLite.

==================================================
UI ARCHITECTURE (Native, not web)
==================================================

Every screen is a `NativeComponent` PHP class rendering EDGE Blade elements
(`<native:column>`, `<native:button>`, `<native:top-bar>`, `<native:bottom-nav>`, …),
registered via `Route::native()` in `routes/mobile.php`.

- Build native screens only. Never scaffold web-view / Livewire / Inertia screens.
- Shared chrome (bottom nav) lives in a `NativeLayout` (or inline chrome elements).
- Reusable UI is extracted into nested child `NativeComponent`s (see Component Architecture).
- Iconography uses `native:icon` with the generated enums `App\Icons\Ios` / `App\Icons\Android`.
  Never use emoji characters in UI labels or buttons.
- Style with theme tokens (`bg-theme-*`, `text-theme-*`, `border-theme-*`) and Tailwind
  utility classes via `class="…"`. Never inline `style="…"` attributes.

==================================================
DESIGN REQUIREMENTS
==================================================

The UI design is extremely important.

Create a modern, premium-looking, clean interface inspired by current 2025/2026 mobile UI trends.

Use a subtle Glassmorphism / Glassy effect, implemented natively:

- NativePHP's Liquid Glass classes: `glass`, `glass:prominent`, `glass:interactive`, `glass:clear`
- Semi-transparent theme surface tokens (`bg-theme-surface`, opacity modifiers like `bg-theme-primary/15`)
- Subtle borders (`border-theme-outline`), soft shadows/elevation, rounded corners (`rounded-*`)

Design principles:

- Clean, minimal, modern, premium, user-friendly, mobile-first
- Spacious layout, smooth rounded corners, soft shadows
- Subtle blur effects (via native glass), good visual hierarchy
- Accessible typography, touch-friendly buttons

Do NOT overuse glass effects.

Use glassmorphism selectively for:

- Summary cards
- Modals / bottom sheets
- Bottom navigation
- Floating action buttons
- Important dashboard sections

The UI should feel elegant and lightweight, not overly decorative.

==================================================
THEME SYSTEM
==================================================

Implement a customizable theme/accent color system built on the `nativephp/native-ui`
theme tokens (`config/native-ui.php`) plus runtime theming.

Requirements:

1. The application has a default theme color (Blue).
2. The user can select a different accent/system color.
3. Provide several predefined colors:

- Blue (Default)
- Purple
- Pink
- Red
- Orange
- Green
- Teal

4. The selected theme dynamically affects:

- Primary buttons
- Active navigation items
- Icons
- Charts
- Highlights
- Progress indicators
- Selected states

5. Store the selected theme locally (in the `settings` table).
6. The selected theme persists after closing and restarting the app
   (re-applied on boot, e.g. in a service provider or the splash screen).
7. Include a "Reset to Default" button that:

- Restores the default theme color (via `Theme::reset()`)
- Updates the UI immediately
- Persists the default setting locally (removes the stored override)

Implementation approach:

- Define the default palette in the `theme` block of `config/native-ui.php`
  (`primary` / `on-primary` for both `light` and `dark`).
- Define the 7 preset accents in ONE PHP home (e.g. an enum or a `ThemeService`
  constant map), each with `primary` / `on-primary` for light and dark.
- Apply a chosen accent at runtime via `Theme::merge([...])`; reset via `Theme::reset()`.
- Read colors in views with the `theme('primary')` helper and `bg-theme-*` classes.
- Do not hardcode the primary color throughout the application.

(Note: the "CSS variables" concept from an earlier draft maps to these theme tokens
and the `theme()` helper — no literal CSS variables are used in native UI.)

==================================================
OFFLINE FUNCTIONALITY
==================================================

The application must work completely offline.

Use SQLite as the local database.

Store locally:

- Transactions
- Categories
- User preferences
- Theme settings

The application works without:

- Internet
- Authentication
- External APIs
- Cloud database

Design the architecture so cloud synchronization could be added later, but DO NOT
implement cloud synchronization now.

==================================================
DATABASE DESIGN
==================================================

Create appropriate migrations and models.

categories

- id
- name
- type (income / expense)
- icon
- color (nullable hex — data-driven category color, may be empty)
- timestamps

transactions

- id
- category_id (foreign key, restrict on delete)
- type (income / expense)
- amount (integer, stored in paisa — the minor currency unit; no floats)
- note (nullable)
- transaction_date (date)
- timestamps

settings

- id
- key (unique)
- value
- timestamps

currencies

- id
- code (ISO 4217, unique)
- name
- symbol
- decimal_places (integer, default 2)
- timestamps

Use proper:

- Foreign keys (with restrict-on-delete for transaction → category)
- Relationships (Category hasMany Transaction; Transaction belongsTo Category)
- Validation
- Database constraints

==================================================
MONEY / CURRENCY
==================================================

- Amounts are stored as integer **minor units** (e.g. paisa) to avoid float rounding
  errors (e.g. ৳25,000 stored as 2,500,000).
- The display currency is a **user preference**. The default currency is Bangladeshi
  Taka (BDT, ৳).
- A `currencies` table is seeded with common currencies (see Default Data). The active
  currency is stored in the `settings` table and applied across the app.
- Changing currency affects **display only** (symbol + decimal formatting). No FX
  conversion is performed — the app is offline and must not call external exchange-rate
  APIs. Stored minor units are not rescaled when the currency changes.
- Create a centralized, currency-aware formatting helper (e.g. `App\Support\Money`) that
  resolves the active currency and formats minor units into a display string (symbol,
  thousands separators, correct decimal places). Do not hardcode the symbol or format
  anywhere else.
- Structure the helper so currencies can be added later without touching views.

==================================================
APPLICATION SCREENS
==================================================

Create the following screens as `NativeComponent` classes.

--------------------------------------------------
1. SPLASH / APP INITIALIZATION
--------------------------------------------------

- Use NativePHP's native splash assets (`public/splash.png` / `public/splash-dark.png`).
- Optionally include a minimal branded `NativeComponent` splash that applies the
  persisted theme color and then navigates to Home.
- Display: app logo/icon and app name. Keep it minimal.

--------------------------------------------------
2. HOME / DASHBOARD
--------------------------------------------------

The main screen. Display:

- Greeting/header
- Current month
- Total balance
- Total income
- Total expenses

Use a visually attractive balance card with a subtle glass effect:

Total Balance
৳ 25,000

Income        Expense
৳ 40,000     ৳ 15,000

Below the summary:

- Recent transactions (latest 5)
- Quick add transaction button (FAB)

Each transaction item displays: category icon, category name, note, date, amount.
Income visually indicates positive; expenses visually indicate negative.

--------------------------------------------------
3. ADD / EDIT TRANSACTION
--------------------------------------------------

A clean transaction form. Fields:

- Transaction Type — segmented control (`native:button-group`): [ Income ] [ Expense ]
- Amount (numeric/decimal keyboard)
- Category (bottom-sheet or selector screen)
- Date
- Optional Note

Validation:

- Amount is required and greater than 0
- Category is required
- Date is required

--------------------------------------------------
4. TRANSACTIONS SCREEN
--------------------------------------------------

Display all transactions:

- Scrollable transaction list (`native:list` or `native:scroll-view`)
- Group transactions by date
- Show amount, category, note, date

Filters:

- All / Income / Expense
- Date range filter
- Category filter

Allow edit and delete (confirmation dialog before delete).

--------------------------------------------------
5. STATISTICS SCREEN
--------------------------------------------------

Clean statistics screen showing:

Monthly Summary: Income, Expenses, Balance.

Simple native charts (drawn with `native:canvas` + `native:rect` / `native:line` /
`native:circle` shapes — no JavaScript chart library):

- Expense by category (bar chart)
- Monthly income vs expenses (bar chart)

Charts must work offline. Do not overload the screen; keep the visualization clean.

--------------------------------------------------
6. CATEGORIES SCREEN
--------------------------------------------------

Manage categories. Default Expense Categories:

- Food, Transportation, Shopping, Bills, Entertainment, Health, Education, Others

Default Income Categories:

- Salary, Freelance, Business, Investment, Gift, Others

Users can add, edit, and delete categories. Each category supports name, icon, optional color.

Do not allow deleting a category that would create data-integrity problems
(transactions referencing it). Handle this gracefully with a clear message.

--------------------------------------------------
7. SETTINGS SCREEN
--------------------------------------------------

Clean Settings page:

Preferences

- Currency — select from the seeded common currencies (default BDT). Changes display
  only; persisted in settings.

Appearance

- Theme Color — color circles (7 presets), current selection clearly indicated.
- "Reset to Default" button.

Data

- Total transactions
- Clear all data

Dangerous actions show a confirmation dialog and explain the consequences
(use the native `Dialog::alert` / `Dialog` facade).

==================================================
NAVIGATION
==================================================

Modern bottom navigation with a standout center action:

- 4 tabs: Home · Transactions · Statistics · Settings
- Center floating action button (FAB) for Add (outstanding, theme-colored)

Example:

Home | Transactions | (+) | Statistics | Settings

The active navigation item uses the currently selected theme color (`theme('primary')`).

==================================================
USER EXPERIENCE
==================================================

Focus heavily on UX:

- Smooth transitions, responsive interactions, clear feedback
- Empty states, loading states, error messages, confirmation dialogs
- Feedback via `Dialog::toast` where appropriate

Examples:

Empty Transactions:
"No transactions yet"
"Start tracking your expenses and take control of your finances."
[ Add Transaction ]

Empty Statistics:
"Not enough data yet."
"Add some transactions to see your financial insights."

==================================================
COMPONENT ARCHITECTURE
==================================================

Create reusable native components. In v4 these are nested child `NativeComponent`
classes (auto-registered as tags under `app/NativeComponents`, e.g. `TransactionItem`
→ `<native:transaction-item>`) plus shared EDGE partials.

Suggested components:

- Layout / bottom navigation (a `NativeLayout` with a `TabBar` + FAB, or inline chrome)
- BalanceCard (glass summary card)
- SurfaceCard / GlassCard
- TransactionItem (child component, keyed by stable id `key="transaction-{{ $id }}"`)
- TransactionList (grouped-by-date list)
- CategorySelector (bottom sheet)
- AmountInput
- ThemeColorCircle / ThemeSelector
- ConfirmationModal (wraps native `Dialog::alert`)
- EmptyState
- PageHeader
- MoneyText (renders formatted currency)

Avoid duplicating UI code. Keep components modular and reusable.

==================================================
CODE QUALITY
==================================================

Follow Laravel best practices:

- Eloquent relationships
- Database migrations
- Reusable components
- Clean naming
- Proper separation of concerns

Business logic lives in Services/Actions (per the project's `.ai/rules`):

- `App\Services\TransactionService`
- `App\Services\CategoryService`
- `App\Services\StatsService`
- `App\Services\ThemeService`
- `App\Services\CurrencyService`

Components stay thin. Validate with Laravel's `Validator` inside the component/service
(`FormRequest` does not apply to native screens — there is no HTTP request). Avoid
putting unnecessary business logic inside views.

==================================================
RESPONSIVENESS
==================================================

Primary target is mobile devices. Design for small and large phones:

- Buttons easy to tap (44pt iOS / 48dp Android targets)
- Readable text
- Easy-to-use forms
- Correct bottom navigation behavior
- No horizontal scrolling

==================================================
ANIMATIONS
==================================================

Use subtle animations:

- Button press feedback (`press-scale` / `press-opacity` on pressable elements)
- Modal / bottom-sheet transitions
- Card appearance
- Tab transitions

Do NOT use excessive animations. Animations should improve, not distract.

==================================================
ACCESSIBILITY
==================================================

Ensure:

- Good text contrast (WCAG AA; `on-*` theme tokens at 4.5:1)
- Proper button sizes
- Clear labels
- `a11y-label` / `a11y-hint` on icon-only controls; icons are never the only indicator
- Clear validation messages

==================================================
DEFAULT DATA (SEEDING)
==================================================

Seed the application with default categories.

Expense categories: Food, Transportation, Shopping, Bills, Entertainment, Health,
Education, Others.

Income categories: Salary, Freelance, Business, Investment, Gift, Others.

Seeding MUST go through a migration (there is no `db:seed` on device). Create a seed
migration (e.g. `seed_default_categories`) whose `up()` inserts the defaults, optionally
calling a `CategorySeeder` class. It must be safe for both fresh installs and existing
user databases.

Seed a `currencies` table with common currencies via a second seed migration
(e.g. `seed_common_currencies`). Suggested defaults (code — name — symbol):

- BDT — Bangladeshi Taka — ৳ (default)
- USD — US Dollar — $
- EUR — Euro — €
- GBP — British Pound — £
- INR — Indian Rupee — ₹
- JPY — Japanese Yen — ¥
- PKR — Pakistani Rupee — ₨
- SAR — Saudi Riyal — ﷼
- AED — UAE Dirham — د.إ
- MYR — Malaysian Ringgit — RM

==================================================
IMPLEMENTATION PROCESS
==================================================

Build the application step by step:

STEP 1 — Install `nativephp/native-ui`, publish `config/native-ui.php`, generate icon enums.
STEP 2 — Configure SQLite for offline storage.
STEP 3 — Create database migrations (categories, transactions, settings, currencies).
STEP 4 — Create models, relationships, and factories.
STEP 5 — Create the seed migrations (default categories + common currencies).
STEP 6 — Create the money/currency helper.
STEP 7 — Create services (Transaction, Category, Stats, Theme, Currency).
STEP 8 — Build the layout and bottom navigation (+ FAB).
STEP 9 — Build the Dashboard (Home screen).
STEP 10 — Implement transaction CRUD (add/edit/delete + filters).
STEP 11 — Build category management.
STEP 12 — Build statistics and native charts.
STEP 13 — Implement the theme/accent color system (runtime `Theme::merge`/`reset` + persist).
STEP 14 — Implement persistent settings (theme + currency + clear data).
STEP 15 — Add empty/loading/error states and polish UI/UX.
STEP 16 — Test the entire application.

==================================================
IMPORTANT DEVELOPMENT RULES
==================================================

- Do not implement authentication.
- Do not require internet access.
- Do not use paid services or paid UI libraries.
- Do not use external APIs.
- Everything works locally.
- Use SQLite.
- Store amounts as integer minor units (paisa).
- Currency is a user preference (default BDT); changing it affects display only — no FX conversion.
- Build native SuperNative screens (no Livewire/Alpine/Inertia/web-view screens).
- Use theme tokens (`bg-theme-*`) and `theme('primary')`; never hardcode the primary color.
- Use `native:icon` + typed icon enums; no emoji icons.
- Keep the architecture simple; avoid unnecessary complexity.
- Prioritize a polished user experience.
- Write maintainable, clean code; use reusable components.
- Follow Laravel conventions.
- Do not create placeholder functionality — all CRUD operations must actually work.

==================================================
FINAL RESULT
==================================================

The final application should feel like a polished modern mobile application. The user
should be able to:

1. Open the app offline.
2. Add income and expenses.
3. Manage categories.
4. View their balance and transaction history.
5. Filter transactions.
6. View basic financial statistics.
7. Change the application accent/theme color.
8. Reset the theme to the default.
9. Change the display currency (default BDT).
10. Close and reopen the app without losing data or settings.

Before considering the project complete, verify that:

- All database operations work.
- All CRUD operations work.
- Theme changes persist and reset works.
- The app works without internet.
- Navigation works.
- SQLite data persists.
- UI works correctly on mobile screen sizes.
- No unnecessary paid dependencies exist.

IMPORTANT:
First analyze the current NativePHP Mobile documentation and available FREE
components/packages compatible with the current project version before choosing
implementation details. If there is a conflict between an older package/tutorial and
the currently installed NativePHP version, follow the current official NativePHP v4
documentation and the project's installed versions.

Build production-quality code, but keep the application simple and focused.
