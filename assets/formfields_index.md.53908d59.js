import{_ as e,c as t,o as a,a as r}from"./app.e6a18615.js";const u='{"title":"Formfields","description":"","frontmatter":{},"headers":[{"level":2,"title":"Common options","slug":"common-options"},{"level":3,"title":"Column","slug":"column"},{"level":3,"title":"Translatable","slug":"translatable"},{"level":3,"title":"Title","slug":"title"},{"level":3,"title":"Description","slug":"description"},{"level":3,"title":"Component","slug":"component"},{"level":3,"title":"Classes","slug":"classes"},{"level":3,"title":"Validation","slug":"validation"},{"level":2,"title":"Overview","slug":"overview"}],"relativePath":"formfields/index.md"}',d={},i=r('<h1 id="formfields" tabindex="-1">Formfields <a class="header-anchor" href="#formfields" aria-hidden="true">#</a></h1><p>Formfields are the heart of every layout.<br> They display, parse and handle incoming data and input.</p><h2 id="common-options" tabindex="-1">Common options <a class="header-anchor" href="#common-options" aria-hidden="true">#</a></h2><h3 id="column" tabindex="-1">Column <a class="header-anchor" href="#column" aria-hidden="true">#</a></h3><p>Here you select which column this formfield consumes.<br> This can be table columns, accessor, relationship properties or relationship methods. Please be aware that (most of the time) the column is necessary.<br> When saving the BREAD with a formfield that does not have a column assigned, a warning will be shown.</p><h3 id="translatable" tabindex="-1">Translatable <a class="header-anchor" href="#translatable" aria-hidden="true">#</a></h3><p>If you want your data to be translatable, check this box.<br> Please note that some formfields, like the relationship formfield, can not be <a href="http://translated.In" target="_blank" rel="noopener noreferrer">translated.In</a> this case the checkbox is not shown.<br> Read more about multilanguage <a href="./../bread/multilanguage.html">here</a></p><h3 id="title" tabindex="-1">Title <a class="header-anchor" href="#title" aria-hidden="true">#</a></h3><p>The title shown above the formfield.<br> This field is translatable.</p><h3 id="description" tabindex="-1">Description <a class="header-anchor" href="#description" aria-hidden="true">#</a></h3><p>The description shown below the formfield.<br> This field is translatable.</p><h3 id="component" tabindex="-1">Component <a class="header-anchor" href="#component" aria-hidden="true">#</a></h3><p>Here you can provide a name of a custom Vue component.<br> Read more how to add components to Voyager <a href="./../plugins/components.html">here</a></p><h3 id="classes" tabindex="-1">Classes <a class="header-anchor" href="#classes" aria-hidden="true">#</a></h3><p>This input allows you to enter additional CSS classes applied to the parent formfield element.</p><h3 id="validation" tabindex="-1">Validation <a class="header-anchor" href="#validation" aria-hidden="true">#</a></h3><p>Here you enter all your validation rules and messages which wil be displayed when the rule fails.<br> The message field is translatable.<br> Learn more about validation <a href="./../bread/validation.html">here</a></p><h2 id="overview" tabindex="-1">Overview <a class="header-anchor" href="#overview" aria-hidden="true">#</a></h2><p>This table gives you an overview of all built-in formfields and their recommended column type</p><table><thead><tr><th><strong>Formfield</strong></th><th><strong>Description</strong></th><th><strong>Recommended column type</strong></th></tr></thead><tbody><tr><td><a href="./checkboxes.html">Checkboxes</a></td><td>Check one or many given options</td><td>JSON*</td></tr><tr><td><a href="./datetime.html">Date &amp; Time</a></td><td>Select date and/or time. Single or range</td><td>Date, Timestamp, JSON*</td></tr><tr><td><a href="./dynamic-input.html">Dynamic input</a></td><td>A dynamic form containing user generated data/inputs</td><td>Depending on your resulting key(s)</td></tr><tr><td><a href="./media-picker.html">Media picker</a></td><td>Select one or many files with the media manager</td><td>JSON*</td></tr><tr><td><a href="./number.html">Number</a></td><td>Enter a number, float or double</td><td>Int, Float, Double</td></tr><tr><td><a href="./password.html">Password</a></td><td>A password formfield</td><td>Text, Varchar</td></tr><tr><td><a href="./radios.html">Radios</a></td><td>Select one of many given options</td><td>Text, Number, ... (depending on your value)</td></tr><tr><td><a href="./relationship.html">Relationship</a></td><td>Display a relationship</td><td>Depending on your resulting key(s)</td></tr><tr><td><a href="./repeater.html">Repeater</a></td><td>Display a repeatable set of formfields</td><td>JSON*</td></tr><tr><td><a href="./select.html">Select</a></td><td>Select one or multiple given options</td><td>Text, Varchar, JSON</td></tr><tr><td><a href="./simple-array.html">Simple array</a></td><td>Enter multiple values of any kind</td><td>JSON*</td></tr><tr><td><a href="./slider.html">Slider</a></td><td>Select a numeric value from a slider/range</td><td>Int, JSON*</td></tr><tr><td><a href="./slug.html">Slug</a></td><td>Generate a slug from a given formfield</td><td>Text, Varchar</td></tr><tr><td><a href="./tags.html">Tags</a></td><td>Allows you to enter tags</td><td>JSON*</td></tr><tr><td><a href="./text.html">Text</a></td><td>A standard text formfield</td><td>Text, Longtext, Varchar</td></tr><tr><td><a href="./toggle.html">Toggle</a></td><td>A binary switch</td><td>Varchar, Integer, Binary</td></tr></tbody></table><p>Formfields with an asterisk <strong>require</strong> the column to be real JSON as the result is always an array.</p>',21),o=[i];function n(l,s,h,m,c,p){return a(),t("div",null,o)}var b=e(d,[["render",n]]);export{u as __pageData,b as default};
