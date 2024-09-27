# PHP MVC Framework

## Description

This project is a custom PHP MVC framework developed to provide a clear separation between the Model, View, and Controller layers of an application. It helps developers create well-structured, reusable, and maintainable code, similar to the popular Laravel framework.

## Features

- **MVC Architecture**: Separation of concerns with Models, Views, and Controllers.
- **Routing System**: Custom routing system for handling HTTP requests.
- **Lightweight Framework**: Minimal dependencies, making it easy to understand and extend.
- **Configuration Files**: Centralized configuration for environment variables and settings.

## Directory Structure

- **app/**: Contains the core application files, including Controllers, Models, and Views.
  - **Controllers/**: Handles the application logic and processes user inputs.
  - **Models/**: Handles data-related logic and database interactions.
  - **Views/**: Contains the UI representation of the data.
  
- **config/**: Holds configuration files for the application, such as database settings.
  
- **public/**: The web server’s document root, contains the index.php file, which acts as the front controller.
  
- **routes/**: Defines the available routes for the application.

- **bootstrap/**: Loads the required files and initializes the application.

- **vendor/**: Contains third-party dependencies (if any).

## Requirements

- PHP 7.4 or higher
- Composer (for dependency management)
- Web server (e.g., Apache, Nginx)

## Installation

1. **Clone the Repository**:
   ```shell
   git clone https://github.com/osamaradwan2003/MVC.git
   cd MVC
   ```

2. **Install Dependencies**:
   If the project uses Composer, install dependencies using:
   ```shell
   composer install
   ```

3. **Configure Environment**:
   - Copy the `.env.example` file to `.env` and update the necessary environment variables (e.g., database connection).
  
4. **Set Up Virtual Host** (optional):
   - Configure your web server (Apache/Nginx) to point to the `public/` directory as the document root.

## Usage

1. **Routing**:
   - Define your application routes in the `routes/web.php` file. You can specify the HTTP method and the corresponding controller action.
  
2. **Controllers**:
   - Create controllers in the `app/Controllers` directory to handle application logic.
   - Controllers should extend a base controller to utilize common functionality.
  
3. **Models**:
   - Create models in the `app/Models` directory for handling database interactions.
  
4. **Views**:
   - Create views in the `app/Views` directory for rendering HTML pages. Use the controller to pass data to the views.

## Example

Here’s a simple example of defining a route and creating a controller action:

1. **Define a Route** in `routes/web.php`:
   ```php
   $router->get('/home', 'HomeController@index');
   ```

2. **Create a Controller** in `app/Controllers/HomeController.php`:
   ```php
   namespace App\Controllers;

   class HomeController {
       public function index() {
           return view('home');
       }
   }
   ```

3. **Create a View** in `app/Views/home.php`:
   ```html
   <h1>Welcome to the Home Page</h1>
   ```

## Contributing

Contributions are welcome! To contribute:

1. Fork the repository.
2. Create a new branch:
   ```shell
   git checkout -b feature-branch
   ```
3. Make your changes.
4. Commit your changes:
   ```shell
   git commit -m "Add new feature"
   ```
5. Push to the branch:
   ```shell
   git push origin feature-branch
   ```
6. Open a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For questions or support, please contact me.
