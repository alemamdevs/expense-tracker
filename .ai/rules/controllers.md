---
paths:
  - 'app/Http/Controllers/**'
---

# Controllers

## Keep controllers thin — delegate to actions/services
Controllers handle HTTP concerns only: type-hint a FormRequest, inject and call one service/action class, and return a view, redirect, or response. No business logic, no direct Eloquent queries, and no validation in controllers. Each method should be a few lines that read like a recipe. Follow Laravel resource controller conventions (`index`, `store`, `update`, `destroy`) with `--model` binding.
