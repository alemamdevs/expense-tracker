---
paths:
  - 'app/Actions/**'
---

# Actions

## Action classes for single business operations
Encapsulate each business operation in a single-responsibility Action class under `app/Actions` (one public `handle()` method, or `__invoke()`). Use constructor property promotion for dependencies. Actions must not touch HTTP concerns (request, session, redirect). Controllers call actions; models stay persistence-focused. For cohesive, multi-method domain logic use a Service class under `app/Services/` instead.
