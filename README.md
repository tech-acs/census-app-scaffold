## Census App Scaffold

[![Latest Version on Packagist](https://img.shields.io/packagist/v/uneca/census-app-scaffold.svg?style=flat-square)](https://packagist.org/packages/uneca/census-app-scaffold)
[![Total Downloads](https://img.shields.io/packagist/dt/uneca/census-app-scaffold.svg?style=flat-square)](https://packagist.org/packages/uneca/census-app-scaffold)

This is a Laravel package (built with Laravel Jetstream) that has some census/survey related functionalities built into it.
It provides a basic structure for building census applications and is meant to be used within the UNECA/ACS org. 

It helps speed up the development process and reduce the amount of code that needs to be 
written from scratch. It also ensures that all applications in the organization have the same look
and feel as it provides basic UI components.

## Features

- User management (with invitation based registration)
- Role based permissions 
- Announcements (to role or everyone)
- Area hierarchy definition
- Areas (shapefile and csv import)
- Usage stats
- Notifications (with inbox)
- Adminify command (for creating super user accounts)
- Data import and export (for production)
- Dockerization (command)
- Production checklist command
- i18n
- 2FA enforcement
- Queued jobs for scalability

### Included Livewire Components
- Area Filter
- Area Restriction
- Language Switcher
- Notification (bell, dropdown and inbox)
- Role Manager

### Installation
```shell
composer require uneca/census-app-scaffold
```

[Documentation](https://tech-acs.github.io/chimera-docs/)