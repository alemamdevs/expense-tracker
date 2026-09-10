---
paths:
  - 'app/Services/**'
---

# Services

## Service classes for cohesive domain logic
Use a Service class under `app/Services` when a domain concern groups several related operations (e.g. `TransactionService`, `CategoryService`). Inject repositories/models via the constructor (property promotion). Services contain no HTTP concerns and no direct response/redirect logic; controllers and actions call them. Prefer single-purpose Action classes under `app/Actions/` for one-off operations.
