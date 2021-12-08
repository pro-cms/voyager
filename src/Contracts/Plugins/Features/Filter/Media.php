<?php

namespace Voyager\Admin\Contracts\Plugins\Features\Filter;

use Illuminate\Support\Collection;

/**
 * An interface for plugins that want to filter media files shown in the media manager.
 */
interface Layouts
{    
    /**
    * Filter media files.
    *
    * @param Collection $media A Collection containing all files in the current directory.
    *
    * @return Collection The filtered layouts
    */
    public function filterMedia(Collection $files): Collection;
}