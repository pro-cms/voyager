# Authorization

The following names/parameters are used to authorize users:

## BREAD Builder

- `browse`      => `Bread::instance`                => Browse all BREADs
- `add`         => `Bread::instance, table-name`    => Create a BREAD
- `edit`        => `Bread::instance`                => Edit/update a BREAD
- `delete`      => `Bread::instance`                => Delete a BREAD
- `backup`      => `Bread::instance`                => Backup a BREAD
- `restore`     => `Bread::instance`                => Restore a backed-up BREAD

## BREAD

To be implemented

## Media manager


- `browse`      => `media`
- `upload`      => `media`
- `rename`      => `media`
- `delete`      => `media`
- `move`        => `media`

## Others

- `browse`      => `voyager`                    => User is able to access Voyager