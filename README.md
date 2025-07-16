<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://sylius.com/wp-content/uploads/2021/03/sylius-logo_sylius-logo-light-768x317.jpg" />
    </a>
</p>

<h1 align="center">Omnisend Plugin</h1>

## Documentation

This plugin implements Omnisend API and all required tracking scripts.

   - [Instalation](docs/instalation.md) 
   - [Configuration](docs/configuration.md) 
   - [Fixtures](docs/fixtures.md) 
   - [Omnisend tracking scripts](docs/async.md) 
   - [Omnisend API](docs/api/client.md) 
   - [Batch commands](docs/commands.md) 
   - [Async implementation](docs/async.md) 

## Tests

### Running plugin tests

  - PHPUnit

    ```bash
    composer test
    ```

  - Behat (non-JS scenarios)

    ```bash
    vendor/bin/behat --strict --tags="~@javascript"
    ```

  - Behat (JS scenarios)
 
    1. [Install Symfony CLI command](https://symfony.com/download).
 
    2. Start Headless Chrome:
    
      ```bash
      google-chrome-stable --enable-automation --disable-background-networking --no-default-browser-check --no-first-run --disable-popup-blocking --disable-default-apps --allow-insecure-localhost --disable-translate --disable-extensions --no-sandbox --enable-features=Metal --headless --remote-debugging-port=9222 --window-size=2880,1800 --proxy-server='direct://' --proxy-bypass-list='*' http://127.0.0.1
      ```
    
    3. Install SSL certificates (only once needed) and run test application's webserver on `127.0.0.1:8080`:
    
      ```bash
      wget https://get.symfony.com/cli/installer -O - | bash
      symfony server:ca:install
      APP_ENV=test symfony server:start --port=8080 --dir=tests/Application/public --daemon
      ```
    
    4. Run Behat:
    
      ```bash
      vendor/bin/behat --strict --tags="@javascript"
      ```
    
  - Static Analysis
  
    - Psalm
    
      ```bash
      vendor/bin/psalm
      ```
      
    - PHPStan
    
      ```bash
      composer phpstan  
      ```

### Opening Sylius with your plugin

- Using `test` environment:

    ```bash
    (cd tests/Application && APP_ENV=test bin/console sylius:fixtures:load)
    (cd tests/Application && APP_ENV=test bin/console server:run -d public)
    ```
    
- Using `dev` environment:

    ```bash
    (cd tests/Application && APP_ENV=dev bin/console sylius:fixtures:load)
    (cd tests/Application && APP_ENV=dev bin/console server:run -d public)
    ```
