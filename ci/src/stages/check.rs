use dagger_sdk::{Directory, Query};
use eyre::WrapErr;

use crate::containers::php_builder;

/// Lint PHP source files for syntax errors.
pub async fn run(client: &Query, source: Directory) -> eyre::Result<String> {
    let output = php_builder(client, source)
        .with_exec(vec![
            "sh", "-c",
            "php -l src/*.php src/Enums/*.php && echo 'check: syntax ok'",
        ])
        .stdout()
        .await
        .wrap_err("check failed")?;

    Ok(output)
}
