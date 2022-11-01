/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { RichText } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit(props) {
    var number1 = props.attributes.number1 // To bind attribute number 1
    var number2 = props.attributes.number2 // To bind attribute number 2

    function onChangeNumber1(content) {
        props.setAttributes({ number1: content })
    }

    function onChangeNumber2(content) {
        props.setAttributes({ number2: content })
    }

    return ( <
        div id = "block-dynamic-box" > { /* You have to have a wrapper tag when your markup has more than 1 tag */ } <
        h1 > Sample dynamic PHP server - side block < /h1> <
        p > This block will sum the numbers and render HTML on the server side < /p> <
        label > Number 1: < /label> <
        RichText className = { props.className } // Automatic class: gutenberg-blocks-sample-block-editable
        onChange = { onChangeNumber1 } // onChange event callback
        value = { number1 } // Binding
        placeholder = "First number" /
        >
        <
        label > Number 2: < /label> <
        RichText className = { props.className } // Automatic class: gutenberg-blocks-sample-block-editable
        onChange = { onChangeNumber2 } // onChange event callback
        value = { number2 } // Binding
        placeholder = "Second number" /
        >
        <
        /div>
    )
}