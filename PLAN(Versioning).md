# Versioned Banking Website Plan With Concurrency Testing

## Summary
Build a standalone **React SPA + CodeIgniter 4 API + MySQL** banking simulation as a **transactional monolith**. The roadmap is versioned so each release teaches a specific backend/database concept, with **concurrency and load testing built into the later versions**, not bolted on at the end.

Primary learning goals:
- secure backend design
- ACID transactions
- locking and isolation behavior
- indexing and query tuning
- service-layer architecture
- concurrent-user correctness and load analysis

## Version Breakdown
### V1 - Foundation and Secure Auth
Deliver:
- New repo with `/client` and `/server`
- React app shell, routing, auth pages, dashboard shell
- CodeIgniter API with session auth, CSRF, password hashing, session rotation
- Roles: `customer`, `ops_admin`
- MySQL schema, migrations, seeders
- Basic customer accounts dashboard

Concepts:
- schema design
- foreign keys and unique constraints
- secure session auth
- controller vs service responsibilities

Acceptance:
- customer and admin can log in
- protected routes enforce roles
- seeded users/accounts render correctly

### V2 - Ledger and Account History
Deliver:
- `bank_accounts`, `transactions`, `ledger_entries`
- account detail page and transaction history
- balance logic derived from controlled ledger posting
- pagination and filtering for history

Concepts:
- relational modeling
- decimal money handling
- query design for reads
- invariant-driven backend logic

Acceptance:
- account balance matches ledger-derived history
- no direct balance edits outside service layer
- transaction history is correct and paginated

### V3 - Transfer Engine With ACID Guarantees
Deliver:
- own-account and beneficiary transfers
- `transfers` table with lifecycle states
- `TransferService` and `LedgerService`
- DB transaction wrappers for money movement
- idempotency key support
- insufficient-funds and account-status checks

Concepts:
- ACID
- rollback behavior
- row locking
- pessimistic concurrency
- retry-safe writes

Acceptance:
- successful transfers post exactly once
- failed transfers leave no partial writes
- duplicate submit does not duplicate ledger changes

### V3.5 - Concurrency Correctness Testing
Deliver:
- `k6` scripts for concurrent transfer scenarios
- seed scripts for many users/accounts
- correctness assertions after runs
- test fixtures for same-account contention cases

Scenarios:
- two or more concurrent transfers from the same account
- same transfer retried with same idempotency key
- concurrent freeze action during transfer submission
- many users hitting login and dashboard while transfers are active

Concepts:
- race conditions
- lock contention
- idempotency verification
- post-run DB integrity checks

Acceptance:
- no duplicate ledger entries
- no negative balances unless explicitly allowed by rules
- transfer status always matches ledger result
- concurrency test reports clear pass/fail outcomes

### V4 - Admin Operations and Auditability
Deliver:
- admin panel for customer/account lookup
- freeze/unfreeze account
- flagged/high-value transfer review queue
- audit logs and security events
- MFA for admins and step-up MFA for sensitive actions

Concepts:
- RBAC
- approval workflows
- audit trails
- security event modeling

Acceptance:
- admin actions are permission-checked
- all sensitive actions create audit rows
- frozen accounts cannot transfer funds

### V5 - Indexing, Query Tuning, and Load Testing
Deliver:
- larger seed dataset
- composite indexes for high-frequency queries
- `EXPLAIN` analysis for major queries
- `k6` load scripts for login, account history, transfers, and admin review
- metrics comparison before/after indexing

Concepts:
- composite vs single-column indexes
- read/write tradeoffs
- query-plan analysis
- pagination at scale
- throughput vs correctness under load

Key indexed access patterns:
- transaction history by `account_id + created_at`
- transfer review queue by `status + created_at`
- audit logs by `actor_id + created_at`
- security events by `user_id + created_at`

Acceptance:
- important screens remain responsive under seeded load
- each index has a documented reason
- at least one slow query is measurably improved through index/query changes

### V6 - Advanced Hardening and Operational Safety
Deliver:
- account holds or reserved funds
- password reset and session/device management
- rate limiting and lockout rules
- reconciliation/admin repair tooling for simulation use
- optional async jobs for notifications only

Concepts:
- operational safeguards
- compensating actions vs rollback boundaries
- security hardening
- maintenance tooling

Acceptance:
- security-sensitive flows have operational controls
- admins can inspect and remediate simulated issues safely
- transactional core remains consistent under advanced flows

## Public APIs / Interfaces
Core API groups:
- `/api/auth/*`
- `/api/accounts/*`
- `/api/transactions/*`
- `/api/beneficiaries/*`
- `/api/transfers/*`
- `/api/security/*`
- `/api/admin/*`

Core backend services:
- `AuthService`
- `AccountService`
- `LedgerService`
- `TransferService`
- `AuditLogService`
- `SecurityEventService`

Testing assets:
- `k6` scenario scripts for correctness and load
- seed scripts for users, accounts, transfers, ledger rows
- DB verification scripts to confirm post-test integrity

## Test Plan
- V1: auth, session, role access, validation
- V2: ledger posting, balance derivation, history queries
- V3: rollback, insufficient funds, duplicate submit, transfer invariants
- V3.5: concurrent-user correctness with `k6` plus DB verification
- V4: permissions, MFA, audit, freeze/unfreeze behavior
- V5: load/performance tests, `EXPLAIN`, index validation, large-seed scenarios
- V6: rate limiting, reset flows, session revocation, reconciliation behavior

## Assumptions and Defaults
- This is a learning simulation, not a real bank integration
- Backend focus is **single-node transactional correctness first**
- MySQL/InnoDB is the database
- Single currency in all planned versions
- Session/cookie auth remains the default
- `k6` is the standard tool for concurrent-user and load scripts
- Concurrency validation must check both API responses and final DB state
