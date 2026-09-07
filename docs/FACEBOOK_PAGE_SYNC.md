# Facebook Page sync

GradConn accepts signed Facebook Page `feed` webhook events at:

`https://YOUR-DOMAIN/webhooks/facebook`

Set these Render environment variables before configuring the Meta webhook:

- `FACEBOOK_PAGE_ID`: the Facebook Page ID.
- `FACEBOOK_PAGE_ACCESS_TOKEN`: a Page access token permitted to read the Page's posts.
- `FACEBOOK_WEBHOOK_VERIFY_TOKEN`: a private random string you choose and enter in both Render and Meta.
- `FACEBOOK_APP_SECRET`: the Meta app secret, used to validate every webhook signature.
- `FACEBOOK_GRAPH_VERSION`: the Graph API version enabled for the Meta app.

In the Meta developer dashboard, add the Webhooks product, select the Page object, set the callback URL above, enter the same verify token, and subscribe the Page to the `feed` field. The Meta app and Page access token must have the Page permissions required by Meta for reading Page engagement/content.

After deployment, run `php artisan migrate --force`. New Page posts are then imported into `events` and appear in the shared Community Feed for alumni, admin, alumni officer, and employer accounts. Updates overwrite the matching imported post. Removed Facebook posts are archived in GradConn. Facebook comments and GradConn comments remain separate.

Only Facebook Page posts are supported. Meta does not provide this workflow for ordinary personal-profile posts.
