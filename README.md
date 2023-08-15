# Codeflix Hir Staff Product Codeigniter 3

## Authors

- [@vijay.codeflix@gmail.com](https://www.github.com/vijay-codeflix)

## Documentation

[Documentation](https://docs.google.com/document/d/1RdryKH1Sr5UZdbSvJocxqSaeEO_aXB3-P14wZZjjwWA/edit)

## Deployment

Take Clone

```bash
git clone  https://github.com/Codeflix-Projects/codeflix-staff
Github repo https://github.com/Codeflix-Projects/codeflix-staff (private) contact(https://github.com/Codeflix-Projects/ for access)

```

Database Connection

```bash
Locate (application\config\database.php)
Change username
Password
Database

```

Procedure Changes

```base
After importing database update both procedure
(if localhost) chage definer `u780765189_staff`@`127.0.0.1` to `root`@`localhost` (user@server)

```

(If not have ssl/https)??jump next

```bash
Locate application\config\constants.php
Change
1) [ define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST'] . '/staff/'); to define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/your folder name/'); ]
2) [ Change $getCurl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; to $getCurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ]

```
