use dagger_sdk::{Directory, Query};
use eyre::WrapErr;

use crate::containers::php_builder;

/// Install dependencies and run PHPUnit tests.
pub async fn run(client: &Query, source: Directory) -> eyre::Result<String> {
    let output = php_builder(client, source)
        .with_exec(vec!["composer", "install", "--no-interaction"])
        .with_exec(vec!["vendor/bin/phpunit", "tests/"])
        .with_exec(vec!["sh", "-c", "echo 'test: all tests passed'"])
        .stdout()
        .await
        .wrap_err("test failed")?;

    Ok(output)
}
