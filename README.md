# UMed – Union for the Mediterranean summit app

> **Academic integrity notice:** this work was submitted for the IB Diploma Programme (IB Computer Science Internal Assessment) and has been assessed. It is shared as a portfolio piece only. Do not copy or submit any part of it for assessment; doing so is academic misconduct under the IB Academic Integrity Policy.
>
> © 2025 Júlia Muria Solé. All rights reserved. See [COPYRIGHT](COPYRIGHT). Not endorsed by the International Baccalaureate Organization.

A web app I built for my IB Computer Science Internal Assessment (2024–25). My school runs a yearly mock **Union for the Mediterranean** assembly, where students represent countries, submit proposals, ask to speak and vote. The teacher running it was relying on an old tool plus manual work, so I built one platform that handles the whole event.

## What it does

- **Login and registration** with hashed passwords (`password_hash` / `password_verify`) and two roles: student (delegate) and admin
- **Proposal uploads** (.txt / .pdf) and a document list for each country
- **Speaking requests** and a live **speaking map** so the admin can manage the queue
- **Voting sessions**: the admin opens a vote, delegates vote and results update live
- **PDF reports** of the event, generated with [FPDF](http://www.fpdf.org/)

## Stack

- PHP with PDO, MySQL / MariaDB
- HTML, CSS and vanilla JavaScript
- Apache on Ubuntu (LAMP)
- FPDF 1.86 (bundled in `fpdf/`, under its own licence)
- `index.js`, `app.js` and `public/` are an early Node/Express prototype I tried before moving to PHP. They are not part of the final app.

## Running it locally

1. Install PHP, MySQL and Apache (or run `php -S localhost:8000` in this folder).
2. Create a `umed` database and the tables `users`, `countries`, `documents`, `requests`, `speaking_map`, `voting_sessions` and `votes` (see the queries in the PHP files).
3. Copy `config.example.php` to `config.php` and enter your database details. `config.php` is gitignored.
4. Make sure `uploads/` is writable by the web server.
5. Open `login.php` and register a user.

## What I learned

- Designing a relational schema from a real client's needs, and interviewing and evaluating with that client
- Writing secure logins: hashed passwords, sessions and prepared statements against SQL injection
- Building live views (speaking queue, voting results) by polling PHP endpoints from JavaScript
- Deploying and debugging a LAMP server on Ubuntu
- Planning, building and evaluating a project against success criteria
