const path = require('path')
const { VueLoaderPlugin } = require('vue-loader')
const TerserJSPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
//process.argv.push('--watch');
module.exports = env => {
  let mode = 'development';
  if (env && env.hasOwnProperty('prod')) {
    mode = 'production';
    process.env.NODE_ENV = 'production';
  }
  
  return {
    mode: mode,
    watchOptions: {
      aggregateTimeout: 200,
      ignored: /node_modules/,
    },
    entry: {
      '': [
        path.resolve(__dirname, './resources/assets/js/voyager.js'),
        path.resolve(__dirname, './resources/assets/sass/voyager.scss')
      ]
    },
    output: {
      path: path.resolve(__dirname, './resources/assets/dist'),
      filename: 'js/voyager.js'
    },
    resolve: {
      alias: {
        // this isn't technically needed, since the default `vue` entry for bundlers
        // is a simple `export * from '@vue/runtime-dom`. However having this
        // extra re-export somehow causes webpack to always invalidate the module
        // on the first HMR update and causes the page to reload.
        'vue': '@vue/runtime-dom'
      }
    },
    module: {
      rules: [
        {
          test: /\.vue$/,
          use: 'vue-loader'
        },
        {
          test: /\.css$/,
          use: [
            {
              loader: MiniCssExtractPlugin.loader
            },
            {
              loader: 'css-loader',
              options: { url: false, importLoaders: 1 }
            },
            {
              loader: 'postcss-loader',
              options: {}
            }
          ]
        },
        {
          test: /\.scss$/,
          use: [
            {
              loader: MiniCssExtractPlugin.loader,
            },
            {
              loader: 'css-loader',
              options: { url: false, importLoaders: 1 }
            },
            {
              loader: 'postcss-loader',
              options: {}
            },
            {
              loader: 'sass-loader',
              options: {
                implementation: require('dart-sass'),
              }
            }
          ]
        }
      ]
    },
    plugins: [
      new VueLoaderPlugin(),
      new MiniCssExtractPlugin({
        filename: 'css/voyager.css',
      })
    ],
    optimization: {
      minimizer: [new TerserJSPlugin({}), new OptimizeCSSAssetsPlugin({})],
    },
    performance: {
      hints: false
    }
  };
};