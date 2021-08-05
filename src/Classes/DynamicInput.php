<?php

namespace Voyager\Admin\Classes;

use Illuminate\Support\Collection;

class DynamicInput implements \JsonSerializable
{
    protected Collection $inputs;

    public function __construct()
    {
        $this->inputs = collect();
    }

    /**
     * Add a select input to the dynamic input.
     */
    public function addSelect(?string $key = null, string|array|null $title = null, ?array $options = [], ?bool $multiple = false, mixed $value = null): self
    {
        $this->addMultipleChoiceInput('select', $key, $title, $options, $multiple, $value);

        return $this;
    }

    /**
     * Add a text input to the dynamic input.
     */
    public function addText(?string $key = null, string|array|null $title = null, string|array|null $placeholder = null, ?string $value = null): self
    {
        $this->inputs->push([
            'type'          => 'text',
            'key'           => $key,
            'title'         => $title,
            'placeholder'   => $placeholder,
            'value'         => $value,
        ]);

        $this->checkInputs();

        return $this;
    }

    /**
     * Add a number input to the dynamic input.
     */
    public function addNumber(?string $key = null, string|array|null $title = null, string|array|null $placeholder = null, ?int $value = null, ?int $min = null, ?int $max = null): self
    {
        $this->inputs->push([
            'type'          => 'number',
            'key'           => $key,
            'title'         => $title,
            'placeholder'   => $placeholder,
            'value'         => $value,
            'min'           => $min,
            'max'           => $max,
        ]);

        $this->checkInputs();

        return $this;
    }

    /**
     * Add checkboxed to the dynamic input.
     */
    public function addCheckboxes(?string $key = null, string|array|null $title = null, ?array $options = [], mixed $value = null): self
    {
        $this->addMultipleChoiceInput('checkbox', $key, $title, $options, true, $value);

        return $this;
    }

    /**
     * Add radios to the dynamic input.
     */
    public function addRadios(?string $key = null, string|array|null $title = null, ?array $options = [], mixed $value = null): self
    {
        if (is_array($value)) {
            throw new \Exception('The default value for a radio-input in a dynamic select can not be an array!');
        }
        $this->addMultipleChoiceInput('radio', $key, $title, $options, false, $value);

        return $this;
    }

    /**
     * Add a switch to the dynamic input.
     */
    public function addSwitch(?string $key = null, string|array|null $title = null, ?bool $value = false): self
    {
        $this->inputs->push([
            'type'      => 'switch',
            'key'       => $key,
            'title'     => $title,
            'value'     => $value,
        ]);

        $this->checkInputs();

        return $this;
    }

    /**
     * Add a multiple choice input to the dynamic input.
     */
    private function addMultipleChoiceInput(string $type, ?string $key = null, string|array|null $title = null, ?array $options = [], ?bool $multiple = false, mixed $value = null): void
    {
        $this->inputs->push([
            'type'      => $type,
            'key'       => $key,
            'title'     => $title,
            'options'   => $options,
            'multiple'  => $multiple,
            'value'     => $value,
        ]);

        $this->checkInputs();
    }

    /**
     * Check if only one input without a key exist, or all inputs provide a key.
     */
    private function checkInputs(): void
    {
        $inputsWithoutKey = $this->inputs->where('key', null)->count();

        if ($inputsWithoutKey > 1 || ($inputsWithoutKey > 0 && $this->inputs->count() > 1)) {
            throw new \Exception('Only one input without a key can exist!');
        }
    }

    public function jsonSerialize(): Collection
    {
        return $this->inputs;
    }
}
