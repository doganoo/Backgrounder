# Backgrounder

Backgrounder is a simple and intuitive library for handling background jobs. Backgrounder provides two types of background jobs:

1 One Time Jobs: jobs that are designed to run only one time (such as a file migration from one directory to another)
2 Regular (recurrent) Jobs: jobs that are designed to run after a defined amount of time

## Installation

You can install the package via composer:

```bash
composer require doganoo/backgrounder
```

## Usage
The main class for running background jobs is doganoo\Backgrounder\Backgrounder. In order to run Backgrounder, you need to pass
a list of jobs that should be executed, the DI Container which is used to query the jobs and a Logger to get any log messages.

## Contributions

Feel free to send a pull request to add more functionalities.

## Maintainer/Creator

Doğan Uçar ([@doganoo](https://www.dogan-ucar.de))

## License

MIT
