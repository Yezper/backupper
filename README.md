# Backupper

Backupper is a simple backup script for Linux.

## Installation

### Requirements

- PHP ^8.2
- Composer
- tar

### Installation

```bash
git clone
cd backupper
composer install
```

### Configuration

Edit the ```config/config.php``` file to fit your needs.

### Usage

#### Via command line

```bash
php run.php
```

#### Via cron

Set up a cron job to run the script however often you want to back up
your files.

```bash
0 0 * * * /usr/bin/env php /path/to/run.php
```

## Testing

Not yet implemented.

```bash
composer test
```

## Security

If you discover any security related issues, please create an issue on
GitHub.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

Backupper is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
