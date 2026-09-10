---
paths:
  - 'app/NativeComponents/**'
---

# Native Components

## Child components must be registered explicitly on Windows
NativePHP mobile's registerChildComponents() auto-discovery under app/NativeComponents is broken on Windows (it builds the class name from getPathname() with a forward-slash str_replace that never matches backslash paths), so nested child components are silently NOT registered as tags (ComponentRegistry::all() stays empty). Register every child component explicitly via ComponentRegistry::components(['kebab-name' => Class::class]) in AppServiceProvider::boot(). Add new components there as they're created; a missing entry means `<native:foo>` fails to resolve.
