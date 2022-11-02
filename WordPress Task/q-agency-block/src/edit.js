import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
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
    const blockProps = useBlockProps();
    const favQuotes = props.attributes.favQuotes // To bind attribute number 1

    function onChangeFavQuotes(content) {
        props.setAttributes({ favQuotes: content })
    }

    return ( <
        div {...blockProps }
        id = "fav-movie-quotes-box" >
        <
        label > Your favourite movie quotes: < /label> <
        RichText className = { props.className }
        onChange = { onChangeFavQuotes } // onChange event callback
        value = { favQuotes } // Binding
        placeholder = { props.attributes.favQuotes }
        / > < /
        div >
    )
}