<?php

namespace Voyager\Admin\Contracts\Plugins\Features\Filter;

use Illuminate\Support\Collection;

/**
 * An interface for plugins that want to filter media files shown in the media manager.
 */
interface Media
{    
    /**
    * Filter media files.
    *
    * @param Collection $media A Collection containing all files in the current directory.
    *
    * @return Collection The filtered media files
    */
    public function filterMedia(Collection $files): Collection;
}