# Guestbook Application

## Requirements

- PHP 7+
- MySQL 5.2+
- Apache/Nginx

## Installation

1. Clone the repository:
    ```
    git clone https://github.com/Nointoter/project-guestbook.git
    ```

2. Import the database:
    ```
    mysql -u username -p guestbook < dump.sql
    ```

3. Update the database configuration in `config.php`.

4. Configure your web server to serve the `guestbook` directory.

5. Access the application in your browser.

## Usage

- Visit the main page to add and view messages.
- Visit `/admin` to manage messages.

- Test project on http://gmcrollers.ddns.net/