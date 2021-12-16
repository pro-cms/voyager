# Relationships

This page describes how to implement relationships in your model. 
For informations on how to use the relationship formfields visit [this page](../formfields/relationship)!


Voyager currently supports the following relationship types:

- Has one
- Has many
- Belongs to
- Belongs to many

Morphing and `Has many through` relationships can be displayed when browsing but are not yet add/editable.

## Defining the relationship

When defining the relationship in your model you **have** to provide the return type of the method, otherwise Voyager can not know about them:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    public function belongsTo(): BelongsTo
    {
        return $this->belongsTo(...);
    }

    public function belongsToMany(): BelongsToMany
    {
        return $this->belongsToMany(...);
    }

    public function hasOne(): HasOne
    {
        return $this->hasOne(...);
    }

    public function hasMany(): HasMany
    {
        return $this->hasMany(...);
    }

}
```
