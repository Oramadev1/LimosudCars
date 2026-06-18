# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {SANCTUM_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Admin endpoints use Laravel Sanctum personal access tokens. Send the token as <code>Authorization: Bearer {SANCTUM_TOKEN}</code>.
