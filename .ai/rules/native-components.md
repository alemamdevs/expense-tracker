---
paths:
  - 'app/NativeComponents/**'
---

# Native Components

## Child components must be registered explicitly on Windows
NativePHP mobile's registerChildComponents() auto-discovery under app/NativeComponents is broken on Windows (it builds the class name from getPathname() with a forward-slash str_replace that never matches backslash paths), so nested child components are silently NOT registered as tags (ComponentRegistry::all() stays empty). Register every child component explicitly via ComponentRegistry::components(['kebab-name' => Class::class]) in AppServiceProvider::boot(). Add new components there as they're created; a missing entry means `<native:foo>` fails to resolve.

## Date input uses the built-in native:date-picker element
Date selection uses the built-in `<native:date-picker>` element from nativephp/native-ui (already installed) — do NOT add a date-picker plugin or hand-roll a bottom-sheet. It's self-closing, `mode="date"` (default) is what transactions.transaction_date needs, and its wire value is a wall-clock `Y-m-d` string that feeds `Carbon::parse()` directly. Props: `value`/`min`/`max` (ISO string or DateTimeInterface), `label`, `placeholder`, `picker-style` (compact|inline|wheel), `title`/`confirm-label`/`cancel-label` (Android only), `locale`/`timezone`/`hour-format`. Two-way bind with `native:model` (only `live` sync is allowed; `.blur`/`.debounce` throw) or `@change="method"` which fires with the ISO string. Theme-only colors. Test with `pickDate()`/`assertPickerValue()` macros.
