# capistrano-php-wrapper

A PHP library for executing Capistrano tasks.

## Description
This script will execute Capistrano tasks and start and stop services.

## Usage

```bash
Usage: wrap [--help | --version] | [-v | verbose ] | [--config=existing_configuration_filename.json [-v | --verbose ]].

DESCRIPTION
This script will execute Capistrano tasks and start and stop services.

COMMANDS
-h      --help                  This help menu
-s      --simulate              Print the tasks to be executed without executing them
-v      --verbose               Print debug informartion while running the commands
-V      --version               Print the version of the script
```

## Testing

You can test this library executing the following command:
```bash
wrap --simulate
```

This will print:
```
[SIMULATE] cap project1:staging service service_name='httpd' service_command='stop' service_parameter='graceful'
[SIMULATE] cap project1:staging deploy:check branch='branch_name'
[SIMULATE] cap project2:staging deploy branch='branch_name'
[SIMULATE] cap project3:staging deploy:check branch='branch_name'
[SIMULATE] cap project3:staging service service_name='httpd' service_command='start'
```
without effectively executing the commands.