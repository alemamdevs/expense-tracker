---
paths:
  - 'app/Http/Requests/**'
---

# Requests

## Use FormRequest classes for all validation
Every incoming request is validated by a dedicated FormRequest (extend `Illuminate\Foundation\Http\FormRequest`) under `app/Http/Requests`, never inline `$request->validate()` in a controller. Type-hint the FormRequest on the controller method so validation runs before the action executes. Keep `authorize()`, `rules()`, and `messages()` in the request; use `messages()` for friendly user-facing error text. Name them per action: `StoreTransactionRequest`, `UpdateTransactionRequest`.
