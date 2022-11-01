/**
 * Registers a new block provided a unique name and an object defining its behavior.
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { RichText } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import Edit from './edit';
import save from './save';

/**
 * Every block starts by registering a new block type definition.
 */
registerBlockType(
    'gutenberg-blocks-sample/block-dynamic', // Name of the block with a required name space
    {
        title: __('Dynamic Sum Block (Sample)'), // Title, displayed in the editor
        icon: 'universal-access-alt', // Icon, from WP icons
        category: 'common', // Block category, where the block will be added in the editor

        /**
         * Object with all binding elements between the view HTML and the functions
         * It lets you bind data from DOM elements and storage attributes
         */
        attributes: {
            // Number 1
            // It doesn't use source attribute, so it doesn't come from save() rendered DOM
            // They'll be saved on the block's source code as a JSON
            number1: {
                type: 'string',
            },
            // Number 2
            // It doesn't use source attribute, so it doesn't come from save() rendered DOM
            // They'll be saved on the block's source code as a JSON
            number2: {
                type: 'string',
            },
        },
        /**
         * @see ./edit.js
         */
        edit: Edit,

        /**
         * @see ./save.js
         */
        save,
    });