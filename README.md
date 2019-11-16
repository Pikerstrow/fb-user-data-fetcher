# fb-user-data-fetcher

Fetch Facebook user data by provided access token.

### USAGE

```
try {
    $fb_user_fetcher = new FBUserDataFetcher();
    $fields_for_fetching = [
        'id',
        'email',
        'name',
        'picture' => [
            'width' => 200,
            'height' => 200
        ]
    ];
    $user = $fb_user_fetcher
                ->url('https://graph.facebook.com/v5.0/me')
                ->fields($fields_for_fetching)
                ->token($data['token'])
                ->fetch();
    $fb_user_fetcher->close();

    // TODO: do what you want with user data
} catch (\Throwable $error) {
    // TODO: implement your logic
}
```