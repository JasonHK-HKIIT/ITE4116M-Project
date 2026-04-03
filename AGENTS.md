# ITE4116M Project

The is a group project for ITE4116M Final Year Project. Our topic is the VTC MyPortal student and teacher information system.

## Project Structure

The information system consist of two separate sub-system:

- `/`: Laravel-based frontend and backend
- `/assistant`: LangChain-based AI assistant (based on [OpenGPTs](https://github.com/langchain-ai/opengpts))

### Frontend and Backend

The frontend is built primarily with [Livewire 4](https://livewire.laravel.com/docs/4.x/). You must ensure only Livewire 4 syntaxes were used, DO NOT confused with the older Livewire 3 syntaxes. The UI framework is using [MaryUI](https://mary-ui.com/). Since this is a Laravel-based project, the directory structure follows the Laravel recommended layout.

## Common Commands

### Frontend and Backend

Commands MUST run under `/`.

- `composer run setup`: Setup the development environment
- `composer run dev`: Starts the development server
- `php artisan migrate:fresh`: Drop and rebuild the database tables
- `php artisan db:seed`: Insert predefined records into the tables

### AI Assistant

Commands MUST run under `/assistant`.

- `docker compose up`: Starts the development server, add `--build` flag when source code was updated
