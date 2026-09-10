Build a modern, clean, and user-friendly Expense Tracker mobile application using NativePHP and only free/open-source components and packages.

The application must be fully functional offline and should not depend on any external API or cloud service for its core functionality.

==================================================
TECH STACK
==================================================

Use:

- PHP
- Laravel
- NativePHP Mobile
- SQLite for local offline storage
- Tailwind CSS for styling
- Livewire where appropriate for reactive UI
- Alpine.js for lightweight frontend interactions
- Only free and open-source packages/components

Do not use paid services or paid UI component libraries.

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
11. Use the application completely offline

All user data must be stored locally using SQLite.

==================================================
DESIGN REQUIREMENTS
==================================================

The UI design is extremely important.

Create a modern, premium-looking, clean interface inspired by current 2025/2026 mobile UI trends.

Use a subtle Glassmorphism / Glassy UI effect.

Design principles:

- Clean
- Minimal
- Modern
- Premium
- User-friendly
- Mobile-first
- Spacious layout
- Smooth rounded corners
- Soft shadows
- Subtle blur effects
- Good visual hierarchy
- Accessible typography
- Touch-friendly buttons

Do NOT overuse glass effects.

Use glassmorphism selectively for:

- Summary cards
- Modals
- Bottom navigation
- Floating action buttons
- Important dashboard sections

Glass effect example:

- Semi-transparent backgrounds
- backdrop-blur
- subtle borders
- soft shadows
- layered backgrounds

Example styling direction:

backdrop-blur-xl
bg-white/10
border border-white/20
shadow-lg
rounded-2xl

The UI should feel elegant and lightweight, not overly decorative.

==================================================
THEME SYSTEM
==================================================

Implement a customizable theme/accent color system.

Requirements:

1. The application should have a default theme color.

2. The user can select a different accent/system color.

3. Provide several predefined colors, for example:

- Blue (Default)
- Purple
- Pink
- Red
- Orange
- Green
- Teal

4. The selected theme should dynamically affect:

- Primary buttons
- Active navigation items
- Icons
- Charts
- Highlights
- Progress indicators
- Selected states

5. Store the selected theme locally.

6. The selected theme should persist after:

- Closing the app
- Restarting the app

7. Include a:

"Reset to Default"

button.

When clicked:

- Restore the default theme color
- Update the UI immediately
- Persist the default setting locally

Use CSS variables where appropriate so the theme system is clean and maintainable.

Example concept:

--primary-color
--primary-light
--primary-dark

Do not hardcode the primary color throughout the application.

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

The application should work without:

- Internet
- Authentication
- External APIs
- Cloud database

Design the architecture so that cloud synchronization could potentially be added later, but DO NOT implement cloud synchronization now.

==================================================
DATABASE DESIGN
==================================================

Create appropriate migrations and models.

Suggested tables:

categories

- id
- name
- type (income / expense)
- icon
- color
- timestamps


transactions

- id
- category_id
- type (income / expense)
- amount
- note
- transaction_date
- timestamps


settings

- id
- key
- value
- timestamps


Use proper:

- Foreign keys
- Relationships
- Validation
- Database constraints

==================================================
APPLICATION SCREENS
==================================================

Create the following screens.

--------------------------------------------------
1. SPLASH / APP INITIALIZATION
--------------------------------------------------

Create a simple elegant splash/loading experience.

Display:

- App logo/icon
- App name

Keep it minimal.

--------------------------------------------------
2. HOME / DASHBOARD
--------------------------------------------------

This should be the main screen.

Display:

- Greeting/header
- Current month
- Total balance
- Total income
- Total expenses

Use a visually attractive balance card with a subtle glass effect.

Example:

Total Balance
৳ 25,000

Income        Expense
৳ 40,000     ৳ 15,000


Below the summary:

- Recent transactions
- Quick add transaction button

Show the latest 5 transactions.

Each transaction item should display:

- Category icon
- Category name
- Note
- Date
- Amount

Income should visually indicate positive values.

Expenses should visually indicate negative values.

--------------------------------------------------
3. ADD TRANSACTION
--------------------------------------------------

Create a clean transaction form.

Fields:

Transaction Type

[ Income ] [ Expense ]

Amount

Category

Date

Optional Note


The transaction type selector should be visually attractive.

Use:

- Segmented controls
or
- Modern toggle buttons


Category selection should be easy to use.

Use a modal, bottom sheet, or dedicated selector screen.

The Save button should be prominent.

Validate:

- Amount is required
- Amount must be greater than 0
- Category is required
- Date is required

--------------------------------------------------
4. TRANSACTIONS SCREEN
--------------------------------------------------

Display all transactions.

Features:

- Scrollable transaction list
- Group transactions by date
- Show transaction amount
- Show category
- Show note
- Show date

Include filters:

- All
- Income
- Expense

Also include:

- Date range filter
- Category filter

Keep filtering simple and user-friendly.

Allow:

- Edit transaction
- Delete transaction

Use a confirmation dialog before deleting.

--------------------------------------------------
5. STATISTICS SCREEN
--------------------------------------------------

Create a clean statistics screen.

Show:

Monthly Summary:

- Income
- Expenses
- Balance

Include simple charts.

Examples:

- Expense by category
- Monthly income vs expenses

Charts must work offline.

Use a free chart library compatible with the chosen stack.

Do not overload the screen.

Keep the data visualization clean.

--------------------------------------------------
6. CATEGORIES SCREEN
--------------------------------------------------

Allow users to manage categories.

Default Expense Categories:

- Food
- Transportation
- Shopping
- Bills
- Entertainment
- Health
- Education
- Others


Default Income Categories:

- Salary
- Freelance
- Business
- Investment
- Gift
- Others


Users should be able to:

- Add category
- Edit category
- Delete category

Each category should support:

- Name
- Icon
- Optional color

Do not allow deleting a category if doing so would create data integrity problems.

Handle this gracefully.

--------------------------------------------------
7. SETTINGS SCREEN
--------------------------------------------------

Create a clean Settings page.

Include:

Appearance

- Theme Color

Show color options as visually attractive color circles.

Example:

🔵 🟣 🔴 🟢 🟠

Clearly indicate the currently selected color.

Include:

Reset Theme

Button:

"Reset to Default"


Other sections can include:

Data

- Total transactions
- Clear all data

For dangerous actions:

- Show confirmation dialog
- Clearly explain the consequences

==================================================
NAVIGATION
==================================================

Use a modern bottom navigation.

Include:

Home
Transactions
Add
Statistics
Settings

The Add button should stand out.

Consider using a floating center action button.

Example:

Home | Transactions | (+) | Statistics | Settings


The active navigation item should use the currently selected theme color.

==================================================
USER EXPERIENCE
==================================================

Focus heavily on UX.

Requirements:

- Smooth transitions
- Responsive interactions
- Clear feedback
- Empty states
- Loading states
- Error messages
- Confirmation dialogs

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

Create reusable components.

Suggested components:

- AppLayout
- BottomNavigation
- GlassCard
- BalanceCard
- TransactionItem
- TransactionList
- CategorySelector
- AmountInput
- ThemeSelector
- ConfirmationModal
- EmptyState
- PageHeader

Avoid duplicating UI code.

Keep components modular and reusable.

==================================================
CODE QUALITY
==================================================

Follow Laravel best practices.

Use:

- Form Request validation where appropriate
- Eloquent relationships
- Database migrations
- Reusable components
- Clean naming
- Proper separation of concerns

Avoid putting unnecessary business logic inside views.

Use services/actions if business logic becomes complex.

==================================================
RESPONSIVENESS
==================================================

The primary target is mobile devices.

Design for:

- Small phones
- Large phones

Make sure:

- Buttons are easy to tap
- Text is readable
- Forms are easy to use
- Bottom navigation works correctly
- No horizontal scrolling occurs

==================================================
ANIMATIONS
==================================================

Use subtle animations.

Examples:

- Button press feedback
- Modal transitions
- Card appearance
- Tab transitions

Do NOT use excessive animations.

Animations should improve the experience, not distract the user.

==================================================
ACCESSIBILITY
==================================================

Ensure:

- Good text contrast
- Proper button sizes
- Clear labels
- Icons should not be the only indicator
- Forms should have clear validation messages

==================================================
DEFAULT DATA
==================================================

Seed the application with default categories.

Expense categories:

- Food
- Transportation
- Shopping
- Bills
- Entertainment
- Health
- Education
- Others

Income categories:

- Salary
- Freelance
- Business
- Investment
- Gift
- Others


==================================================
CURRENCY
==================================================

Initially use:

Bangladeshi Taka (৳)

However, structure the application so currency configuration can easily be added later.

Do not hardcode the currency symbol everywhere.

Create a centralized helper/configuration.

==================================================
IMPLEMENTATION PROCESS
==================================================

Build the application step by step.

Follow this order:

STEP 1
Set up the NativePHP + Laravel project.

STEP 2
Configure SQLite for offline storage.

STEP 3
Create database migrations.

STEP 4
Create models and relationships.

STEP 5
Create seeders for default categories.

STEP 6
Create the reusable UI component system.

STEP 7
Build the application layout and bottom navigation.

STEP 8
Build the Dashboard.

STEP 9
Implement transaction CRUD functionality.

STEP 10
Build category management.

STEP 11
Build statistics and charts.

STEP 12
Implement the theme/accent color system.

STEP 13
Implement persistent settings.

STEP 14
Add offline-friendly error handling.

STEP 15
Polish UI and UX.

STEP 16
Test the entire application.

==================================================
IMPORTANT DEVELOPMENT RULES
==================================================

- Do not implement authentication.
- Do not require internet access.
- Do not use paid services.
- Do not use paid UI libraries.
- Do not use external APIs.
- Everything should work locally.
- Use SQLite.
- Keep the architecture simple.
- Avoid unnecessary complexity.
- Prioritize a polished user experience.
- Write maintainable and clean code.
- Use reusable components.
- Follow Laravel conventions.
- Do not create placeholder functionality.
- All CRUD operations must actually work.

==================================================
FINAL RESULT
==================================================

The final application should feel like a polished modern mobile application.

The user should be able to:

1. Open the app offline.
2. Add income.
3. Add expenses.
4. Manage categories.
5. View their balance.
6. View transaction history.
7. Filter transactions.
8. View basic financial statistics.
9. Change the application accent/theme color.
10. Reset the theme to the default.
11. Close and reopen the app without losing data or settings.

Before considering the project complete, verify that:

- All database operations work.
- All CRUD operations work.
- Theme changes persist.
- Reset theme works.
- The app works without internet.
- Navigation works.
- SQLite data persists.
- UI works correctly on mobile screen sizes.
- No unnecessary paid dependencies exist.

IMPORTANT:
First analyze the current NativePHP Mobile documentation and available FREE components/packages compatible with the current project version before choosing implementation details.

If there is a conflict between an older package/tutorial and the currently installed NativePHP version, follow the current official NativePHP documentation and the project's installed versions.

Build production-quality code, but keep the application simple and focused.
