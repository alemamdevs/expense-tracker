# TASKS.md — Expense Tracker (NativePHP Mobile v4)

Target platforms: **Android + iOS**.
Source of truth for scope: `PLAN.md`.

## Conventions

- **ID**: `T###` — unique, sequential within this file.
- **Status**: `pending` → `in-progress` → `done` (all tasks start `pending`).
- **Depends on**: task IDs that must be `done` before this task can start.
- Tasks are grouped by **phase** in the recommended build order.

---

## Phase 0 — Foundation & Setup

| ID | Task | Status | Depends on |
|----|------|--------|------------|
| T001 | Install `nativephp/native-ui` (composer require + `native:plugin:register` + verify with `native:plugin:list`) | done | — |
| T002 | Publish `config/native-ui.php` and define the default theme palette (`primary`/`on-primary` for light + dark) | done | T001 |
| T003 | Generate typed icon enums (`native-ui:generate-icons` → `App\Icons\Ios`/`Android`/`AndroidOutlined`) | done | T001 |
| T004 | Configure SQLite offline storage (ensure `DB_CONNECTION=sqlite` and `database/database.sqlite` exists) | done | — |
| T005 | Verify `.env` app id / version / appearance for both Android and iOS | done | — |

## Phase 1 — Data Layer

| ID | Task | Status | Depends on |
|----|------|--------|------------|
| T006 | Create `categories` migration (name, type, icon, color, timestamps) | done | T004 |
| T007 | Create `transactions` migration (category_id FK restrict, type, amount integer minor units, note, transaction_date) | done | T006 |
| T008 | Create `settings` migration (key unique, value, timestamps) | done | T004 |
| T009 | Create `Category` model (fillable, casts, `transactions()` relation) | done | T006 |
| T010 | Create `Transaction` model (fillable, casts, `category()` relation) | done | T007, T009 |
| T011 | Create `Setting` model (get/set helpers) | done | T008 |
| T012 | Create `CategoryFactory` and `TransactionFactory` for tests | done | T009, T010 |
| T013 | Create seed migration `seed_default_categories` (14 defaults via `CategorySeeder`, safe for existing DBs) | done | T006 |
| T014 | Create `currencies` migration (code ISO 4217 unique, name, symbol, decimal_places default 2) | done | T004 |
| T015 | Create `Currency` model (fillable, casts, default BDT resolution) | done | T014 |
| T016 | Create seed migration `seed_common_currencies` (BDT default + USD, EUR, GBP, INR, JPY, PKR, SAR, AED, MYR) | done | T014 |
| T017 | Create money helper `App\Support\Money` (currency-aware: resolves active currency, formats minor units → symbol + separators + decimals) | done | T015, T011 |

## Phase 2 — Domain Services

| ID | Task | Status | Depends on |
|----|------|--------|------------|
| T018 | Create `App\Services\TransactionService` (create/update/delete/list + filters) | pending | T010 |
| T019 | Create `App\Services\CategoryService` (create/update/delete with transaction guard) | pending | T009 |
| T020 | Create `App\Services\StatsService` (balance/income/expense, monthly summary, by-category grouping) | pending | T010 |
| T021 | Create `App\Services\ThemeService` (7 accent presets map, apply via `Theme::merge`, reset via `Theme::reset`, persist key) | pending | T008, T011, T002 |
| T022 | Create `App\Services\CurrencyService` (list currencies, get/set active currency, persist to settings) | pending | T015, T011 |

## Phase 3 — App Shell & Navigation

| ID | Task | Status | Depends on |
|----|------|--------|------------|
| T023 | Create `NativeLayout` with 4-tab bottom nav (Home/Transactions/Statistics/Settings) + center FAB | pending | T003, T001 |
| T024 | Register `routes/mobile.php` with `Route::native()` entries (updated as screens land) | pending | T023 |

## Phase 4 — Dashboard (Home)

| ID | Task | Status | Depends on |
|----|------|--------|------------|
| T025 | Create `BalanceCard` component (glass summary card, currency-aware) | pending | T017, T001 |
| T026 | Create `TransactionItem` child component (keyed `transaction-{{ $id }}`) | pending | T009, T017 |
| T027 | Create `EmptyState` component | pending | T001 |
| T028 | Build `Home` screen (greeting/header, current month, BalanceCard, recent 5, empty state) | pending | T020, T025, T026, T027, T023 |

## Phase 5 — Transaction CRUD

| ID | Task | Status | Depends on |
|----|------|--------|------------|
| T029 | Confirm date-input approach from v4 docs (native date-picker plugin vs lightweight date bottom-sheet) | pending | — |
| T030 | Create `AmountInput` component (decimal keyboard, currency-aware minor-unit handling) | pending | T017 |
| T031 | Create `CategorySelector` bottom sheet component | pending | T009, T001 |
| T032 | Build `Add/EditTransaction` screen (segmented type, amount, category, date, note + validation) | pending | T018, T030, T031, T029 |
| T033 | Build `Transactions` screen (grouped by date; all/income/expense + date range + category filters; edit/delete w/ confirm) | pending | T018, T026, T023 |

## Phase 6 — Categories

| ID | Task | Status | Depends on |
|----|------|--------|------------|
| T034 | Build `Categories` screen (list income/expense, add/edit/delete, guarded delete message) | pending | T019, T023, T003 |

## Phase 7 — Statistics

| ID | Task | Status | Depends on |
|----|------|--------|------------|
| T035 | Build `Statistics` screen (monthly summary + native `canvas`/`rect` bar charts: by-category, income-vs-expense) | pending | T020, T023, T001 |

## Phase 8 — Theme & Settings

| ID | Task | Status | Depends on |
|----|------|--------|------------|
| T036 | Wire runtime theming: apply persisted accent on boot, `Theme::merge` on select, `Theme::reset` on default | pending | T021, T002 |
| T037 | Build `Settings` screen (currency selector default BDT, 7 color circles w/ selected state, "Reset to Default", data section + clear all) | pending | T021, T022, T036, T018, T023 |
| T038 | Add splash assets (`public/splash.png` / `splash-dark.png`) + minimal branded splash screen | pending | T036, T023 |

## Phase 9 — Polish & Accessibility

| ID | Task | Status | Depends on |
|----|------|--------|------------|
| T039 | Add empty/loading/error states + `Dialog::toast` feedback across all screens | pending | T028, T033, T034, T035 |
| T040 | Add subtle animations / press feedback (`press-scale`/`press-opacity`, transitions) | pending | T028, T032, T033, T034, T035 |
| T041 | Accessibility pass (`a11y-label` on icon controls, contrast, touch targets) | pending | T028, T032, T033, T034, T035, T037 |

## Phase 10 — Testing & Verification

| ID | Task | Status | Depends on |
|----|------|--------|------------|
| T042 | Feature tests: transaction CRUD + validation (amount > 0, category/date required) | pending | T018, T010 |
| T043 | Feature tests: stats math (balance/income/expense, by-category totals) | pending | T020 |
| T044 | Feature tests: category delete guard | pending | T019 |
| T045 | Feature tests: theme apply / persist / reset | pending | T021, T036 |
| T046 | Feature tests: currency (default BDT, change + persist, formatting) | pending | T022, T017, T015 |
| T047 | Native component tests (`Native::test`) for Home, Transactions, Settings | pending | T028, T033, T037 |
| T048 | Final verification checklist (offline, CRUD, theme persist/reset, currency change, navigation, SQLite persistence) on **both Android + iOS** | pending | T042–T047 |
