<template>
    <Card title="UI Elements">
        <div>
            <div class="flex flex-wrap space-x-1 w-full">
                <button class="button accent my-2" v-scroll-to="'ui-headings'">Headings</button>
                <button class="button accent my-2" v-scroll-to="'ui-icons'">Icons</button>
                <button class="button accent my-2" v-scroll-to="'ui-buttons'">Buttons</button>
                <button class="button accent my-2" v-scroll-to="'ui-inputs'">Inputs</button>
                <button class="button accent my-2" v-scroll-to="'ui-color-picker'">Color picker</button>
                <button class="button accent my-2" v-scroll-to="'ui-datetime'">Date/Time picker</button>
                <button class="button accent my-2" v-scroll-to="'ui-tags'">Tag input</button>
                <button class="button accent my-2" v-scroll-to="'ui-sliders'">Sliders</button>
                <button class="button accent my-2" v-scroll-to="'ui-badges'">Badges</button>
                <button class="button accent my-2" v-scroll-to="'ui-alerts'">Alerts</button>
                <button class="button accent my-2" v-scroll-to="'ui-tooltips'">Tooltips</button>
                <button class="button accent my-2" v-scroll-to="'ui-notifications'">Notifications</button>
                <button class="button accent my-2" v-scroll-to="'ui-pagination'">Pagination</button>
                <button class="button accent my-2" v-scroll-to="'ui-draggable'">Draggable</button>
                <button
                    class="button accent my-2"
                    v-for="el in store.ui"
                    v-scroll-to="`ui-${slugify(el.title, { lower: true })}`"
                    :key="`ui-${el.title}`"
                >{{ el.title }}</button>
            </div>
        </div>
    </Card>

    <Card no-header>
        <div class="w-full flex">
            <div class="w-6/12">
                <Collapsible title="Headings" id="ui-headings" class="h-full">
                    <h1>H1 Heading</h1>
                    <h2>H2 Heading</h2>
                    <h3>H3 Heading</h3>
                    <h4>H4 Heading</h4>
                    <h5>H5 Heading</h5>
                    <h6>H6 Heading</h6>
                </Collapsible>
            </div>
            <div class="w-6/12">
                <Collapsible title="Icons" id="ui-icons" class="h-full">
                    <IconPicker />
                </Collapsible>
            </div>
        </div>
    </Card>

    <Collapsible title="Buttons" id="ui-buttons">
        <div class="w-full flex space-x-1">
            <div class="w-4/12">
                <Collapsible title="Default" :title-size="5">
                    <div class="flex flex-wrap space-x-1 justify-center">
                        <button class="button mb-1">Default</button>
                        <button
                            v-for="color in colors"
                            :key="'button-' + color"
                            :class="['button', color, 'mb-1']"
                        >{{ __('voyager::generic.color_names.' + color) }}</button>
                    </div>
                </Collapsible>
            </div>
            <div class="w-4/12">
                <Collapsible title="Active" :title-size="5">
                    <div class="flex flex-wrap space-x-1 justify-center">
                        <button class="button active mb-1">Default</button>
                        <button
                            v-for="color in colors"
                            :key="'button-' + color"
                            :class="['button', color, 'mb-1', 'active']"
                        >{{ __('voyager::generic.color_names.' + color) }}</button>
                    </div>
                </Collapsible>
            </div>
            <div class="w-4/12">
                <Collapsible title="Disabled" :title-size="5">
                    <div class="flex flex-wrap space-x-1 justify-center">
                        <button class="button mb-1" disabled>Default</button>
                        <button
                            v-for="color in colors"
                            :key="'button-' + color"
                            :class="['button', color, 'mb-1']"
                            disabled
                        >{{ __('voyager::generic.color_names.' + color) }}</button>
                    </div>
                </Collapsible>
            </div>
        </div>
        <div class="w-full flex">
            <div class="w-4/12">
                <Collapsible title="With Icon" :title-size="5">
                    <div class="flex flex-wrap space-x-1 justify-center">
                        <button class="button small mb-1">
                            <Icon icon="information-circle" class="mr-1" :size="4" />Default
                        </button>
                        <button
                            v-for="color in colors"
                            :key="'button-' + color"
                            :class="['button', 'small', color, 'mb-1']"
                        >
                            <Icon icon="information-circle" class="mr-1" :size="4" />
                            {{ __('voyager::generic.color_names.' + color) }}
                        </button>
                    </div>
                </Collapsible>
            </div>
            <div class="w-4/12">
                <Collapsible title="Responsive" :title-size="5">
                    <div class="flex flex-wrap space-x-1 justify-center">
                        <button :class="['button', 'small', 'mb-1']">
                            <Icon icon="information-circle" :size="4" />
                            <span>Default</span>
                        </button>
                        <button
                            v-for="color in colors"
                            :key="'button-' + color"
                            :class="['button', 'small', color, 'mb-1']"
                        >
                            <Icon icon="information-circle" :size="4" />
                            <span>{{ __('voyager::generic.color_names.' + color) }}</span>
                        </button>
                    </div>
                </Collapsible>
            </div>
            <div class="w-4/12">
                <Collapsible title="Button group" :title-size="5">
                    <div class="flex flex-wrap space-x-1 justify-center">
                        <div class="button-group">
                            <button
                                v-for="color in colors"
                                :key="'button-' + color"
                                :class="['button', color, 'mb-1']"
                            >{{ __('voyager::generic.color_names.' + color) }}</button>
                        </div>
                    </div>
                </Collapsible>
            </div>
        </div>
    </Collapsible>

    <Collapsible title="Inputs" id="ui-inputs">
        <template #actions>
            <select class="input small" v-model="inputColor">
                <option value="">{{ __('voyager::generic.none') }}</option>
                <option
                    v-for="color in colors"
                    :key="color"
                    :value="color"
                >
                    {{ __('voyager::generic.color_names.'+color) }}
                </option>
            </select>
        </template>
        <Card title="Inputs">
            <div class="flex w-full">
                <Collapsible title="Default" :title-size="5" class="w-1/3">
                    <input type="text" class="input w-full" :class="inputColor" placeholder="Placeholder" />
                </Collapsible>
                <Collapsible title="Disabled" :title-size="5" class="w-1/3">
                    <input type="text" class="input w-full" :class="inputColor" disabled placeholder="Placeholder" />
                </Collapsible>
                <Collapsible title="Small" :title-size="5" class="w-1/3">
                    <input type="text" class="input w-full small" :class="inputColor" placeholder="Placeholder" />
                </Collapsible>
            </div>
        </Card>
        <Card title="Textarea">
            <div class="flex w-full">
                <Collapsible title="Default" :title-size="5" class="w-1/2">
                    <textarea class="input w-full" :class="inputColor" placeholder="Placeholder"></textarea>
                </Collapsible>
                <Collapsible title="Disabled" :title-size="5" class="w-1/2">
                    <textarea class="input w-full" :class="inputColor" disabled placeholder="Placeholder"></textarea>
                </Collapsible>
            </div>
        </Card>
        <Card title="Checkboxes">
            <div class="flex w-full">
                <Collapsible title="Default" :title-size="5" class="w-full md:w-1/3">
                    <div class="inline-flex items-center space-x-1.5 w-full">
                        <input type="checkbox" class="input" :class="inputColor" checked />
                        <label class="label">Checkbox 1</label>
                    </div>
                    <div class="inline-flex items-center space-x-1.5 w-full">
                        <input type="checkbox" class="input" :class="inputColor" />
                        <label class="label">Checkbox 1</label>
                    </div>
                </Collapsible>
                <Collapsible title="Disabled" :title-size="5" class="w-full md:w-1/3">
                    <div class="inline-flex items-center space-x-1.5 w-full">
                        <input type="checkbox" class="input" :class="inputColor" disabled checked />
                        <label class="label">Checkbox 1</label>
                    </div>
                    <div class="inline-flex items-center space-x-1.5 w-full">
                        <input type="checkbox" class="input" :class="inputColor" disabled />
                        <label class="label">Checkbox 1</label>
                    </div>
                </Collapsible>
                <Collapsible title="Inline" :title-size="5" class="w-full md:w-1/3">
                    <div class="inline-flex space-x-2">
                        <div class="inline-flex items-center space-x-1.5">
                            <input type="checkbox" class="input" :class="inputColor" checked />
                            <label class="label">Checkbox 1</label>
                        </div>
                        <div class="inline-flex items-center space-x-1.5">
                            <input type="checkbox" class="input" :class="inputColor" />
                            <label class="label">Checkbox 1</label>
                        </div>
                    </div>
                </Collapsible>
            </div>
        </Card>
        <Card title="Radios">
            <div class="flex w-full">
                <Collapsible title="Default" :title-size="5" class="w-full md:w-1/3">
                    <div class="inline-flex items-center space-x-1.5 w-full">
                        <input type="radio" class="input" :class="inputColor" name="radio-normal" checked />
                        <label class="label">Radio 1</label>
                    </div>
                    <div class="inline-flex items-center space-x-1.5 w-full">
                        <input type="radio" class="input" :class="inputColor" name="radio-normal" />
                        <label class="label">Radio 1</label>
                    </div>
                </Collapsible>
                <Collapsible title="Disabled" :title-size="5" class="w-full md:w-1/3">
                    <div class="inline-flex items-center space-x-1.5 w-full">
                        <input type="radio" class="input" :class="inputColor" disabled name="radio-disabled" checked />
                        <label class="label">Radio 1</label>
                    </div>
                    <div class="inline-flex items-center space-x-1.5 w-full">
                        <input type="radio" class="input" :class="inputColor" disabled name="radio-disabled" />
                        <label class="label">Radio 1</label>
                    </div>
                </Collapsible>
                <Collapsible title="Inline" :title-size="5" class="w-full md:w-1/3">
                    <div class="inline-flex space-x-2">
                        <div class="inline-flex items-center space-x-1.5">
                            <input type="radio" class="input" :class="inputColor" name="radio-inline" checked />
                            <label class="label">Radio 1</label>
                        </div>
                        <div class="inline-flex items-center space-x-1.5">
                            <input type="radio" class="input" :class="inputColor" name="radio-inline" />
                            <label class="label">Radio 1</label>
                        </div>
                    </div>
                </Collapsible>
            </div>
        </Card>
        <Card title="Select">
            <div class="flex w-full">
                <Collapsible title="Default" :title-size="5" class="w-full md:w-1/3">
                    <select class="input w-full" :class="inputColor">
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>
                </Collapsible>
                <Collapsible title="Disabled" :title-size="5" class="w-full md:w-1/3">
                    <select class="input w-full" disabled :class="inputColor">
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>
                </Collapsible>
                <Collapsible title="Small" :title-size="5" class="w-full md:w-1/3">
                    <select class="input w-full small" :class="inputColor">
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>
                </Collapsible>
            </div>
        </Card>
        <Card title="Toggle">
            <div class="flex w-full">
                <Collapsible title="Default" :title-size="5" class="w-full">
                    <Toggle v-model="toggle" :color="inputColor || 'accent'" />
                </Collapsible>
            </div>
        </Card>
    </Collapsible>

    <Collapsible title="Color picker" id="ui-color-picker">
        <template #actions>
            <div class="space-x-1">
                <button class="button" @click="colorSizePlus">
                    <Icon icon="plus" />
                </button>
                <button class="button" @click="colorSizeMinus">
                    <Icon icon="minus" />
                </button>
            </div>
        </template>
        <Collapsible title="Colors">
            <ColorPicker :size="colorSize" v-model="color"></ColorPicker>
        </Collapsible>
        <Collapsible title="Colors (allow none)">
            <ColorPicker :size="colorSize" v-model="color" add-none></ColorPicker>
        </Collapsible>
    </Collapsible>

    <Collapsible title="Date/Time picker" id="ui-datetime">
        <Card title="Single" :title-size="6">
            <DateTime
                v-model="dtData.from"
                v-bind="datetime"
            />
        </Card>
        <Card title="Range" :title-size="6">
            <DateTime-range
                v-model:from="dtData.from"
                v-model:to="dtData.to"
                v-bind="datetime"
            />
        </Card>
        <Card title="Settings">
            <div class="w-full inline-flex space-x-2 mb-2">
                <div class="input-group w-auto">
                    <label class="label">Inline</label>
                    <input type="checkbox" class="input" v-model="datetime.inline">
                </div>
                <div class="input-group w-auto">
                    <label class="label">Sunday first</label>
                    <input type="checkbox" class="input" v-model="datetime.sundayFirst">
                </div>
                <div class="input-group w-auto">
                    <label class="label">Close on select</label>
                    <input type="checkbox" class="input" v-model="datetime.closeOnSelect" :disabled="datetime.inline">
                </div>
                <div class="input-group w-full">
                    <label class="label">Mode</label>
                    <select class="input w-full" :value="datetime.type" @change="datetime.displayFormat = dtFormats[$event.target.value]; datetime.type = $event.target.value">
                        <option v-for="(format, type) in dtFormats" :key="type" :value="type">
                            {{ titleCase(type) }}
                        </option>
                    </select>
                </div>
                <div class="input-group w-full">
                    <label class="label">Display format</label>
                    <input type="text" class="input w-full" v-model="datetime.displayFormat">
                </div>
                <div class="input-group w-full">
                    <label class="label">Distance</label>
                    <input type="number" class="input w-full" v-model.number="datetime.distance" min="0">
                </div>
            </div>
            <div class="w-full inline-flex space-x-2 mb-2">
                <div class="input-group w-full">
                    <label class="label">Model value (From)</label>
                    <input type="text" class="input w-full" v-model="dtData.from">
                </div>
                <div class="input-group w-full">
                    <label class="label">Model value (To)</label>
                    <input type="text" class="input w-full" v-model="dtData.to">
                </div>
            </div>
            <div class="w-full inline-flex space-x-2 mb-2">
                <div class="input-group w-full">
                    <label class="label">Dropdown placement</label>
                    <select class="input w-full" :value="datetime.placement" @change="datetime.placement = $event.target.value" :disabled="datetime.inline">
                        <option v-for="placement in placements" :key="placement" :value="placement">
                            {{ titleCase(placement) }}
                        </option>
                    </select>
                </div>
            </div>
        </Card>
    </Collapsible>

    <Collapsible title="Tag input" id="ui-tags">
        <template #actions>
            <select class="input small" v-model="tagColor">
                <option
                    v-for="color in colors"
                    :key="color"
                    :value="color"
                >
                    {{ __('voyager::generic.color_names.'+color) }}
                </option>
            </select>
        </template>
        <TagInput v-model="tags" :badgeColor="tagColor" />
    </Collapsible>

    <Collapsible title="Sliders" id="ui-sliders">
        <template #actions>
            <select class="input small" v-model="sliderColor">
                <option
                    v-for="color in colors"
                    :key="color"
                    :value="color"
                >
                    {{ __('voyager::generic.color_names.'+color) }}
                </option>
            </select>
        </template>
        <div class="flex">
            <Card title="From 1 to 100" class="w-full md:w-1/2">
                <Slider v-model:lower="range.lower" :range="false" :min="1" class="mt-2" :color="sliderColor" />
            </Card>
            <Card title="Range from 1 to 100" class="w-full md:w-1/2">
                <Slider v-model:lower="range.lower" v-model:upper="range.upper" :min="1" class="mt-2" :color="sliderColor" />
            </Card>
        </div>
        <div class="flex">
            <Card title="No inputs" class="w-full md:w-1/2">
                <Slider v-model:lower="range.lower" v-model:upper="range.upper" :min="1" :inputs="false" class="mt-2" :color="sliderColor" />
            </Card>
            <Card title="With distance 10" class="w-full md:w-1/2">
                <Slider v-model:lower="range.lower" v-model:upper="range.upper" :min="1" :distance="10" class="mt-2" :color="sliderColor" />
            </Card>
        </div>
    </Collapsible>

    <Collapsible title="Badges" id="ui-badges">
        <div class="w-full flex">
            <Collapsible title="Default" :title-size="5" class="w-1/4">
                <div class="flex flex-wrap space-x-1 w-full">
                    <Badge
                        v-for="color in colors"
                        :color="color"
                        class="my-1"
                        :key="'badge-' + color"
                    >{{ __('voyager::generic.color_names.' + color) }}</Badge>
                </div>
            </Collapsible>
            <Collapsible title="Large" :title-size="5" class="w-1/4">
                <div class="flex flex-wrap space-x-1 w-full">
                    <Badge
                        v-for="color in colors"
                        :color="color"
                        class="my-1"
                        :key="'badge-' + color"
                        large
                    >{{ __('voyager::generic.color_names.' + color) }}</Badge>
                </div>
            </Collapsible>
            <Collapsible title="With icon" :title-size="5" class="w-1/4">
                <div class="flex flex-wrap space-x-1 w-full">
                    <Badge
                        v-for="color in colors"
                        :color="color"
                        class="my-1"
                        :key="'badge-' + color"
                        icon="x"
                    >{{ __('voyager::generic.color_names.' + color) }}</Badge>
                </div>
            </Collapsible>
            <Collapsible title="Large with icon" :title-size="5" class="w-1/4">
                <div class="flex flex-wrap space-x-1 w-full">
                    <Badge
                        v-for="color in colors"
                        :color="color"
                        class="my-1"
                        :key="'badge-' + color"
                        icon="information-circle"
                        large
                    >{{ __('voyager::generic.color_names.' + color) }}</Badge>
                </div>
            </Collapsible>
        </div>
    </Collapsible>

    <Collapsible title="Alerts" id="ui-alerts">
        <template #actions>
            <select class="input small" v-model="alertColor">
                <option
                    v-for="color in colors"
                    :key="color"
                    :value="color"
                >
                    {{ __('voyager::generic.color_names.'+color) }}
                </option>
            </select>
        </template>
        <Alert :color="alertColor" class="mb-3">
            <template #title>{{ __('voyager::generic.color_names.' + alertColor) }}</template>
            <p>{{ lorem }}</p>
        </Alert>
        <Alert :color="alertColor" class="mb-3" :icon="null">
            <template #title>{{ __('voyager::generic.color_names.' + alertColor) }}</template>
            <p>{{ lorem }}</p>
        </Alert>
    </Collapsible>

    <Collapsible title="Tooltips" id="ui-tooltips">
        <div class="w-full flex justify-center">
            <button class="button" v-tooltip:top="'Tooltip on top'">Top</button>
        </div>
        <div class="w-full inline-flex space-x-1 justify-center my-2">
            <button class="button" v-tooltip:left="'Tooltip on the left'">Left</button>
            <button class="button" v-tooltip:right="'Tooltip on the right'">Right</button>
        </div>
        <div class="w-full flex justify-center">
            <button class="button" v-tooltip:bottom="'Tooltip on the bottom'">Bottom</button>
        </div>
        <div class="w-full flex justify-center mt-2">
            <button class="button" v-tooltip:bottom.show="'Permanent tooltip on the bottom'">Permanent</button>
        </div>
    </Collapsible>

    <Collapsible title="Notifications" id="ui-notifications">
        <template #actions>
            <select class="input small" v-model="notificationColor">
                <option
                    v-for="color in colors"
                    :key="color"
                    :value="color"
                >
                    {{ __('voyager::generic.color_names.'+color) }}
                </option>
            </select>
        </template>
        <Collapsible
            :title="__('voyager::generic.color_names.'+notificationColor)"
            :title-size="5"
        >
            <div class="flex flex-wrap space-x-1">
                <button
                    @click="new $notification(lorem).title(ucFirst(notificationColor)).color(notificationColor).show()"
                    class="button mb-1"
                    :class="notificationColor"
                >Message and title</button>
                <button
                    @click="new $notification(lorem).color(notificationColor).show()"
                    class="button mb-1"
                    :class="notificationColor"
                >Message only</button>
                <button
                    @click="new $notification(lorem).title(ucFirst(notificationColor)).color(notificationColor).indeterminate().show()"
                    class="button mb-1"
                    :class="notificationColor"
                >Indeterminate</button>
                <button
                    @click="new $notification(lorem).title(ucFirst(notificationColor)).color(notificationColor).timeout().show()"
                    class="button mb-1"
                    :class="notificationColor"
                >With timeout</button>
            </div>
        </Collapsible>
        <Collapsible title="Confirm" :title-size="5">
            <div class="flex flex-wrap space-x-1">
                <button
                    @click="new $notification('Are you sure?').color(notificationColor).confirm().show().then((r) => { })"
                    class="button mb-1"
                    :class="notificationColor"
                >Simple</button>
                <button
                    @click="new $notification('Are you sure?').color(notificationColor).confirm().indeterminate().show()"
                    class="button mb-1"
                    :class="notificationColor"
                >Indeterminate</button>
                <button
                    @click="new $notification('Are you sure?').color(notificationColor).confirm().timeout().show()"
                    class="button mb-1"
                    :class="notificationColor"
                >With timeout</button>
                <button
                    @click="new $notification('Are you sure?').color(notificationColor).confirm().addButton({ key: true, value: 'Yup', color: 'green' }).addButton({ key: false, value: 'Nah', color: 'red' }).show()"
                    class="button mb-1"
                    :class="notificationColor"
                >Custom buttons</button>
            </div>
        </Collapsible>
        <Collapsible title="Prompt" :title-size="5">
            <div class="flex flex-wrap space-x-1">
                <button
                    @click="new $notification('Enter your name').color(notificationColor).prompt('').show()"
                    class="button mb-1"
                    :class="notificationColor"
                >Simple</button>
                <button
                    @click="new $notification('Enter your name').color(notificationColor).prompt('').timeout().show()"
                    class="button mb-1"
                    :class="notificationColor"
                >With timeout</button>
                <button
                    @click="new $notification('Enter your name').color(notificationColor).prompt('').addButton({ key: true, value: 'Save', color: 'green' }).addButton({ key: false, value: 'Abort', color: 'red' }).show()"
                    class="button mb-1"
                    :class="notificationColor"
                >Custom buttons</button>
                <button
                    @click="new $notification('Enter your name').color(notificationColor).prompt(name).show().then((r) => { if (r !== false) { name = r; } })"
                    class="button mb-1"
                    :class="notificationColor"
                >Value: {{ name }}</button>
            </div>
        </Collapsible>
    </Collapsible>

    <Collapsible title="Pagination" id="ui-pagination">
        <template #actions>
            <select class="input small" v-model="paginationColor">
                <option
                    v-for="color in colors"
                    :key="color"
                    :value="color"
                >
                    {{ __('voyager::generic.color_names.'+color) }}
                </option>
            </select>
        </template>
        <div class="flex">
            <Collapsible title="Default" :title-size="5" class="w-full md:w-1/2">
                <Pagination :page-count="100" v-model="page" :color="paginationColor" />
            </Collapsible>

            <Collapsible title="No previous/next button" :title-size="5" class="w-full md:w-1/2">
                <Pagination :page-count="100" v-model="page" :prev-next-buttons="false" :color="paginationColor" />
            </Collapsible>
        </div>
        <div class="flex">
            <Collapsible title="No first/last button" :title-size="5" class="w-full md:w-1/2">
                <Pagination :page-count="100" v-model="page" :first-last-buttons="false" :color="paginationColor" />
            </Collapsible>

            <Collapsible title="Only page-buttons" :title-size="5" class="w-full md:w-1/2">
                <Pagination
                    :page-count="100"
                    v-model="page"
                    :first-last-buttons="false"
                    :prev-next-buttons="false" :color="paginationColor"
                />
            </Collapsible>
        </div>
    </Collapsible>

    <Collapsible title="Draggable" id="ui-draggable">
        <Card title="Drag from everywhere">
            <Draggable v-model="draggable" class="w-full">
                <template v-slot:item="{item}">
                    <Card no-header>
                        {{ item.title }}
                    </Card>
                </template>
            </Draggable>
        </Card>
        <Card title="Drag with handle and custom item attributes">
            <Draggable v-model="draggable" class="w-full mt-4 flex flex-wrap" handle=".dd-handle" tag="div" itemTag="span" :transition="200" :itemAttrs="dragCB">
                <template v-slot:item="{item}" class="w-1/3">
                    <Card :title="item.title">
                        <template #actions>
                            <button class="button dd-handle">Drag</button>
                        </template>
                    </Card>
                </template>
            </Draggable>
        </Card>
    </Collapsible>

    <Collapsible
        v-for="el in store.ui"
        :id="`ui-${slugify(el.title, { lower: true })}`"
        :title="el.title"
        :key="`ui-${el.title}`"
    >
        <component :is="el.component" />
    </Collapsible>
</template>
<script>
import { placements } from '@popperjs/core/lib/enums';
import scrollTo from '@directives/scroll-to';
import Draggable from './UI/Draggable.vue';
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';

dayjs.extend(utc);
dayjs.extend(timezone);

import { FORMATS } from './UI/DateTime.vue';
import Store from '@/store';

export default {
    directives: { scrollTo: scrollTo },
    components: {
        Draggable
    },
    data() {
        return {
            store: Store,
            name: 'Voyager',
            lorem: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid pariatur, ipsum similique veniam quo totam eius aperiam dolorum.',
            tags: ['Lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipisicing', 'elit'],
            color: this.colors[0],
            colorSize: 4,
            inputColor: '',
            alertColor: 'accent',
            notificationColor: 'accent',
            sliderColor: 'accent',
            tagColor: 'accent',
            paginationColor: 'accent',
            page: 1,
            range: {
                lower: 1,
                upper: 100
            },
            datetime: {
                type: 'date',
                inline: false,
                displayFormat: 'YYYY-MM-DD',
                closeOnSelect: false,
                distance: 0,
                sundayFirst: false,
                closeOnSelect: false,
                dayNames: this.__('voyager::datetime.day_names'),
                monthNames: this.__('voyager::datetime.month_names'),
                placement: 'bottom-start',
            },
            dtData: {
                from: dayjs().tz(dayjs.tz.guess()).toISOString(),
                to: dayjs().add(3, 'day').tz(dayjs.tz.guess()).toISOString(),
            },
            toggle: false,
            placements,
            draggable: [
                {
                    id: 0,
                    title: 'Number 1',
                    class: 'w-1/3'
                },
                {
                    id: 1,
                    title: 'Number 2',
                    class: 'w-1/3'
                },
                {
                    id: 2,
                    title: 'Number 3',
                    class: 'w-1/3'
                },
                {
                    id: 3,
                    title: 'Number 4',
                    class: 'w-1/2'
                },
                {
                    id: 4,
                    title: 'Number 5',
                    class: 'w-1/2'
                },
            ]
        };
    },
    computed: {
        dtFormats() {
            return FORMATS;
        }
    },
    methods: {
        colorSizePlus() {
            if (this.colorSize < 10) {
                this.colorSize += 1;
            }
        },
        colorSizeMinus() {
            if (this.colorSize > 1) {
                this.colorSize -= 1;
            }
        },
        dragCB(item) {
            return {
                class: item.class,
            };
        }
    },
    mounted() {
        this.$watch(() => this.range.minprice, (min) => {
            this.range.minthumb = ((this.range.minprice - this.range.min) / (this.range.max - this.range.min)) * 100;
        }, { immediate: true });

        this.$watch(() => this.range.maxprice, (min) => {
            this.range.maxthumb = 100 - (((this.range.maxprice - this.range.min) / (this.range.max - this.range.min)) * 100); 
        }, { immediate: true });
    }
}
</script>

