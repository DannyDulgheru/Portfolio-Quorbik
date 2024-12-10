# Portfolio Quorbik CMS

Portfolio Quorbik is a simple and efficient CMS designed for managing and showcasing video portfolios. It includes an intuitive admin panel for managing video content and uses SQLite as the database backend.

## Features

- **Video Management**: Add, delete, and organize video content.
- **Admin Dashboard**: A simple dashboard for managing portfolio entries.
- **Database Integration**: Built with SQLite for a lightweight database solution.
- **Customizable Layout**: Easily adaptable to various design needs.
- **Secure Authentication**: Includes login and logout functionality.

## Project Structure

- **`index.php`**: The main landing page displaying the portfolio.
- **`admin.php`**: Admin login and control panel.
- **`dashboard.php`**: The admin dashboard for managing content.
- **`add_video.php`**: Page for uploading new videos.
- **`update_video_order.php`**: Handles reordering of videos.
- **`delete_video.php`**: Deletes a video entry.
- **`assets/`**: Contains static files like CSS, JavaScript, and images.
- **`db/`**: Contains the SQLite database.

## Prerequisites

- A local server environment like XAMPP, WAMP, or LAMP with PHP support.
- SQLite installed (included with most PHP installations).

## Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/DannyDulgheru/Portfolio-Quorbik.git
   cd Portfolio-Quorbik-main
   
2. **Set Up the Database:**

Navigate to the db/ folder and ensure the SQLite database file is in place.
If the database file is missing, create one using the schema provided in the project.

3. **Configure the Project:**

Edit the config.php file to update any necessary settings, such as database connection details or site configurations.

4. **Deploy on Local Server:**

Move the project folder to your server's root directory (e.g., htdocs for XAMPP).
Start the server and navigate to http://localhost/Portfolio-Quorbik-main in your browser.
Usage
Admin Panel:

Access the admin panel at http://localhost/Portfolio-Quorbik-main/admin.php.
Log in with the default credentials (set in config.php or the database).
Manage Videos:

Add, update, reorder, or delete videos through the admin dashboard.
View Portfolio:

The portfolio will display on the main index.php page.
