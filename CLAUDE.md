<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.3.22
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- phpunit/phpunit (PHPUNIT) - v11

## Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture
- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## UI/CSS Approach (Desktop-First with Responsive Mobile)
- This application uses a **desktop-first** CSS approach with Tailwind CSS.
- Always design for desktop first, then add responsive styles for smaller screens.
- Use Tailwind's `max-*` responsive prefixes for mobile adjustments:
  - `max-lg:` for tablet and below (< 1024px)
  - `max-md:` for medium screens and below (< 768px)
  - `max-sm:` for mobile screens (< 640px)
- Example: `grid-cols-4 max-lg:grid-cols-2 max-sm:grid-cols-1`
- Ensure all components are fully functional and visually appealing on mobile devices.
- For tables with many columns, consider using card layouts on mobile (`hidden sm:block` for table, `sm:hidden` for cards).
- Test mobile views to ensure content doesn't overflow or get cut off.

## Replies
- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files
- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs
- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches when dealing with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The `search-docs` tool is perfect for all Laravel-related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments
- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless there is something very complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums
- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database
- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation
- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources
- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation
- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues
- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization
- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation
- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration
- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing
- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

### Vite Error
- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

## Laravel 12

- Use the `search-docs` tool to get version-specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

### Laravel 12 Structure
- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app\Console\Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database
- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models
- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.

=== phpunit/core rules ===

## PHPUnit

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit {name}` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should test all of the happy paths, failure paths, and weird paths.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files; these are core to the application.

### Running Tests
- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test --compact`.
- To run all tests in a file: `php artisan test --compact tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --compact --filter=testName` (recommended after making a change to a related file).
</laravel-boost-guidelines>

## Project Documentation

### Development Roadmap
- Main documentation: `docs/plan2-singleclinic/` (ACTIVE - use this)
- Old documentation: `docs/plan-notused/` (deprecated, do not use)

### Development Phases (Single Clinic)
| Phase | Description | Status |
|-------|-------------|--------|
| Phase 1 | Foundation & Landing Page | DONE |
| Phase 2 | Authentication & Dashboard | In Progress |
| Phase 3 | Master Data (Categories, Services, Staff, Settings) | Pending |
| Phase 4 | Customer Management | Pending |
| Phase 5 | Appointment System | Pending |
| Phase 6 | Treatment Records | Pending |
| Phase 7 | Package Management | Pending |
| Phase 8 | POS & Checkout | Pending |
| Phase 9 | Reports | Pending |
| Phase 10 | Polish & Deployment | Pending |
| Phase 11 | Mobile API | DONE |
| Phase 12 | Import Data | DONE |

### Phase 11: Mobile API (Implemented)
Mobile API sudah diimplementasikan dengan fitur:
- Laravel Sanctum untuk authentication (token-based)
- API versioning dengan prefix `/api/v1`
- Semua responses menggunakan Eloquent API Resources

#### API Endpoints:

**Auth**
- `POST /api/v1/login` - Login dengan email/password, return token
- `POST /api/v1/logout` - Logout (revoke token)
- `GET /api/v1/profile` - Get current user profile
- `PUT /api/v1/profile` - Update profile

**Customers**
- `GET /api/v1/customers` - List customers (with search, pagination)
- `POST /api/v1/customers` - Create customer
- `GET /api/v1/customers/{id}` - Get customer detail
- `PUT /api/v1/customers/{id}` - Update customer
- `DELETE /api/v1/customers/{id}` - Delete customer
- `GET /api/v1/customers/{id}/stats` - Get customer statistics
- `GET /api/v1/customers/{id}/treatments` - Get customer treatments
- `GET /api/v1/customers/{id}/packages` - Get customer packages
- `GET /api/v1/customers/{id}/appointments` - Get customer appointments

**Services**
- `GET /api/v1/service-categories` - List categories (with services)
- `GET /api/v1/service-categories/{id}` - Get category detail
- `GET /api/v1/services` - List services
- `GET /api/v1/services/{id}` - Get service detail

**Appointments**
- `GET /api/v1/appointments` - List appointments (with filters)
- `POST /api/v1/appointments` - Create appointment
- `GET /api/v1/appointments/{id}` - Get appointment detail
- `PUT /api/v1/appointments/{id}` - Update appointment
- `DELETE /api/v1/appointments/{id}` - Delete appointment
- `PATCH /api/v1/appointments/{id}/status` - Update status
- `GET /api/v1/appointments-today` - Get today's appointments
- `GET /api/v1/appointments-calendar` - Get appointments for calendar
- `GET /api/v1/appointments-available-slots` - Get available slots

**Treatments**
- `GET /api/v1/treatments` - List treatment records
- `POST /api/v1/treatments` - Create treatment record (with photo upload)
- `GET /api/v1/treatments/{id}` - Get treatment detail
- `PUT /api/v1/treatments/{id}` - Update treatment
- `DELETE /api/v1/treatments/{id}` - Delete treatment

**Packages**
- `GET /api/v1/packages` - List available packages
- `GET /api/v1/packages/{id}` - Get package detail
- `GET /api/v1/customer-packages` - List customer packages
- `GET /api/v1/customer-packages/{id}` - Get customer package detail

**Transactions**
- `GET /api/v1/transactions` - List transactions
- `GET /api/v1/transactions/{id}` - Get transaction detail
- `GET /api/v1/transactions/{id}/receipt` - Get receipt data

#### API Files:
- Routes: `routes/api.php`
- Controllers: `app/Http/Controllers/Api/V1/`
- Resources: `app/Http/Resources/`
- Tests: `tests/Feature/Api/`

### Phase 12: Import Data (Implemented)
Fitur import data untuk migrasi klinik ke GlowUp:

#### Jenis Import yang Tersedia:
1. **Import Pelanggan** - Import data customer dari CSV
2. **Import Layanan** - Import layanan dan kategori dari CSV
3. **Import Paket** - Import paket treatment dari CSV

#### Fitur:
- Preview data sebelum import
- Download template CSV
- Deteksi duplikat (update jika sudah ada)
- Error reporting detail per baris
- Riwayat import dengan log lengkap

#### Menu:
- Lokasi: Sidebar > Import Data (hanya untuk Owner/Admin)

#### Files:
- Controller: `app/Http/Controllers/ImportController.php`
- Services: `app/Services/Import/`
- Views: `resources/views/imports/`
- Tests: `tests/Feature/ImportTest.php`

#### Routes:
| Method | URI | Nama Route |
|--------|-----|------------|
| GET | /imports | imports.index |
| GET | /imports/{entity}/create | imports.create |
| POST | /imports/{entity}/upload | imports.upload |
| GET | /imports/{entity}/preview | imports.preview |
| POST | /imports/{entity}/process | imports.process |
| GET | /imports/{entity}/template | imports.template |
| GET | /imports/log/{import} | imports.show |
| DELETE | /imports/log/{import} | imports.destroy |
