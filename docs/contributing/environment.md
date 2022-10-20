# Setting up your environment

This chapter shows you how to setup an environment that allows for easy modifications without the need to worry about asset compilation, etc.

## Create and clone your fork

Go to [Voyagers Github page](https://github.com/voyager-admin/voyager) and click the `Fork` button. 
You now have your own copy of Voyager with which you can work.

The next step is to clone your fork to your local machine. You can do this by running the following command:

```bash
git clone https://github.com/your-name/voyager.git
```

Where `your-name` is your Github username or the organization where you forked to repository to.

## Update your composer.json

In your Laravel application open the `composer.json` file and add the following to the top level:

```json
"minimum-stability": "dev",
"require": {
    "voyager-admin/voyager": "*"
},
"repositories": [
    {
        "type": "path",
        "url": "path/to/voyager"
    }
]
```

Where `path/to/voyager` is the relative or absolute path to your local Voyager clone.

Now run `composer update` and Composer will symlink your local fork as the Voyager package.

## Create a new branch

Before you start working on your changes, you should create a new branch. This will allow you to easily switch between branches and keep your main branch clean.

```bash
git checkout -b your-branch-name
```

Where `your-branch-name` is the name of the branch you want to create.

::: warning
You should create a new branch for every feature you want to add or bug you want to fix.
:::

## Enable hot reloading

Everytime you want to change Javascript or CSS you should activate development mode.  
This allows you to make changes in code and immediately see the results in your browser.

Go into the Settings, click the `Admin` group and enable the `Dev Server` option.

Now, in your fork, run `npm install` and `npm run watch` to start the development server.