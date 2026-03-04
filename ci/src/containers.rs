use dagger_sdk::{Container, Directory, Query};

/// Base PHP container with composer installed and cache volume.
pub fn php_builder(client: &Query, source: Directory) -> Container {
    let composer_cache = client.cache_volume("forge-sdk-php-composer");

    client
        .container()
        .from("php:8.3-cli")
        .with_exec(vec![
            "sh", "-c",
            "apt-get update && apt-get install -y unzip curl git \
             && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer",
        ])
        .with_mounted_directory("/build", source)
        .with_workdir("/build")
        .with_mounted_cache("/root/.composer/cache", composer_cache)
}
