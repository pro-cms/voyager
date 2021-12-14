<?php

namespace Voyager\Admin\Traits\Voyager;

use Illuminate\Support\Collection;

trait Messages
{
    protected array $messages = [];

    /**
     * Flash a message to the UI.
     */
    public function flashMessage(array|string|null $message, string $color, ?int $timeout = 5000, bool $next = false): void
    {
        $this->messages[] = [
            'message' => $message,
            'color'   => $color,
            'timeout' => $timeout,
        ];
        if ($next) {
            session()->push('voyager-messages', [
                'message' => $message,
                'color'   => $color,
                'timeout' => $timeout,
            ]);
        }
    }

    /**
     * Get all messages.
     */
    public function getMessages(): Collection
    {
        $messages = array_merge($this->messages, session()->get('voyager-messages', []));
        session()->forget('voyager-messages');

        return collect($messages)->unique();
    }
}