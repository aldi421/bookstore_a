# LENTERA — Books & Stories

Final stabilized version of the native PHP + MySQL bookstore project.

## Main fixes included

- Premium LENTERA visual redesign and page transitions.
- Login/register layout and registration query fixes.
- User dashboard helper (`h()`) fatal error fix.
- User orders, order detail, checkout, messages, and message history fixes.
- Admin dashboard counters read directly from the active database.
- Admin books/users/orders/messages/revenue pages made more tolerant of missing relations.
- Revenue calculation supports paid statuses (`Sudah Bayar`, `Lunas`, `Paid`).
- Payment confirmation and order-status update validation improvements.
- Helper files added for shared user/admin functions.

## Local setup (Laragon)

1. Put the `bookstore_a_LENTERA_FINAL_FIX` folder inside `C:\laragon\www\`.
2. Create/import the database from `bookstore.sql`.
3. Default local connection in `config/koneksi.php`:
   - host: `localhost`
   - user: `root`
   - password: empty
   - database: `bookstore`
4. Open the project from Laragon.

### Seed login

Admin:
- Email: `admin@gmail.com`
- Password: `admin123`

User:
- Email: `rangers@gmail.com`
- Password: `123456`

## Hosting

Do **not** commit hosting database passwords to GitHub.

For InfinityFree or another host, update `config/koneksi.php` on the server with the hosting DB host, username, password, and database name.

## Database check

`CEK_DATABASE_ADMIN.sql` contains quick queries to verify book, user, order, category, message, and revenue data.

## Stack

- PHP Native
- MySQL / MariaDB
- HTML / CSS
- Vanilla JavaScript

Brand: **LENTERA — Books & Stories**
