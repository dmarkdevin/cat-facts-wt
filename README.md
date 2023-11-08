# CatFacts 101: Cat Lovers' Delight

## Introduction
CatFacts 101 is an application that consumes the Cat Facts API to fetch interesting cat facts, displays them to users, and synchronizes this data into a database for future reference. This simple yet engaging application allows users to explore an array of fascinating cat trivia while seamlessly storing the facts in a database for easy access and management

## Prerequisites
- PHP (minimum version 8.0)
- MySQL
- Composer
- Web server (e.g., Apache, Nginx)

## Installation

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/dmarkdevin/catfacts.git
   cd catfacts/
   ```

2. **Run Composer Install:**
   - Install project dependencies using Composer.
     ```bash
     composer install
     ```

3. **Database Setup:**
   - Create a MySQL database named 'your_database'.
   - Create a table named `facts` in your database.
     ```sql
     CREATE TABLE IF NOT EXISTS `facts` (
      `_id` VARCHAR(100) NOT NULL,
      `status` TEXT,
      `user` TEXT,
      `text` TEXT,
      `type` VARCHAR(50),
      `deleted` TINYINT(1),
      `updatedAt` DATETIME,
      `createdAt` DATETIME,
      `source` VARCHAR(50),
      `sentCount` INT(11),
      `__v` INT(11),
      `used` TINYINT(1),
      PRIMARY KEY (`_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
     ```

4. **Create and Configure .env file:**
   - Copy the example `env` file to `.env`.
     ```bash
     cp env .env
     ```
   - Open the `.env` file in a text editor and update the database connection settings to match your MySQL database credentials.

5. **Web Server Configuration (if needed):**
   - Ensure your web server is configured to serve the CodeIgniter application.
   - Set the document root to the `public` directory.

## Running the Application

1. **Start PHP Built-in Server:**
   - Open a terminal or command prompt.
   - Navigate to the root directory of your CodeIgniter application.
   - Run the PHP built-in server.
     ```bash
     php -S localhost:8000 -t public/
     ```
   - Access the application by visiting `http://localhost:8000` in your web browser.

## Additional Notes
- Ensure you have PHP version 8.0 installed or later.