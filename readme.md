# Laravel SMS (via Chikka SMS API)

Send (receive coming soon) SMS messages through a web interface powered by the Chikka SMS API!

This is a forkable, ready-to-use SMS Messaging system written in Laravel 5.2.

## Features

* Send SMS to any Philippine carrier (Globe, Smart, Sun, TM)
* Built-in contact saver
* Per-account Credit Limiting

## Coming Soon

* **Inbox** and **Sent Messages** folders
* Purchase credits through Paypal (and other payment methods)

## Installation

Clone this repository and run `composer install` as you would on a normal Laravel-based system. Run `php artisan migrate` to prepare your MySQL database (assuming you have your database access information already set in `.env`). Then, open your `.env` file and add these variables:

    CHIKKA_CLIENT_ID=<YOUR API CLIENT ID HERE>
    CHIKKA_CLIENT_SECRET=<YOUR API SECRET KEY HERE>
    CHIKKA_CLIENT_SHORTCODE=<YOUR SHORTCODE HERE>

Your **short code** is found on your Developer Dashboard menu (the number that starts with 29290). Make sure it has **no spaces** inside the `.env` file.

## Licensing

This system is released under the [MIT Open Source License](https://opensource.org/licenses/MIT).