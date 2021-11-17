# Authorization

The following names/parameters are used to authorize users:

## BREAD Builder

- `browse`      => `Bread::class`               => Browse all BREADs
- `add`         => `Bread::class, table-name`   => Create a BREAD
- `edit`        => `Bread::instance`            => Edit/update a BREAD
- `delete`      => `Bread::instance`            => Delete a BREAD
- `backup`      => `Bread::instance`            => Backup a BREAD
- `restore`     => `Bread::instance`            => Restore a backed-up BREAD

## Media manager


- `browse`      => `media`
- `upload`      => `media`
- `rename`      => `media`
- `delete`      => `media`
- `move`        => `media`

## Others

- `browse`      => `voyager`                    => User is able to access Voyager